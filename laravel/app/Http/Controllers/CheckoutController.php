<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cartController = new CartController;

        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->get()->toArray();
        } else {
            $items = session('cart', []);
            foreach ($items as $i => &$item) {
                $item['id'] ??= $i + 1;
            }
            unset($item);
        }

        $enriched = $cartController->enrichItems($items);
        $tickets = array_values(array_filter($enriched['items'], fn ($i) => $i['type'] === 'ticket'));
        $souvenirs = array_values(array_filter($enriched['items'], fn ($i) => $i['type'] === 'souvenir'));
        $subtotal = $enriched['total'];
        $shipping = count($souvenirs) > 0 ? 5.00 : 0.00;
        $taxes = round($subtotal * 0.11, 2);
        $grandTotal = round($subtotal + $shipping + $taxes, 2);

        return view('checkout', compact('tickets', 'souvenirs', 'subtotal', 'shipping', 'taxes', 'grandTotal'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',

            'delivery' => 'required|in:courier,locker,pickup',
            'address1' => 'required_if:delivery,courier,locker|nullable|string|max:255',
            'city' => 'required_if:delivery,courier,locker|nullable|string|max:100',
            'postal' => 'required_if:delivery,courier,locker|nullable|string|max:20',
            'country' => 'required_if:delivery,courier,locker|nullable|string|max:100',

            'payment' => 'required|in:card,paypal,google-pay,apple-pay',
            'cardNumber' => 'required_if:payment,card|nullable|regex:/^[0-9 ]{12,19}$/',
            'expiry' => ['required_if:payment,card', 'nullable', 'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'],
            'cvv' => 'required_if:payment,card|nullable|digits_between:3,4',
            'cardName' => 'required_if:payment,card|nullable|string|max:100',
        ]);

        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())->get()->toArray();
        } else {
            $cartItems = session('cart', []);
        }

        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.')->withInput();
        }

        // Structural validation: reject carts with missing slot/seat data or non-existent products
        foreach ($cartItems as $item) {
            $opts = is_string($item['options'] ?? null) ? json_decode($item['options'], true) : ($item['options'] ?? []);

            if ($item['type'] === 'ticket') {
                if (empty($opts['schedule_slot_id']) || empty($opts['seat_ids'])) {
                    return back()->with('error', 'Please select a showtime and seats before checking out.')->withInput();
                }
                if (! DB::table('movies')->where('id', $item['reference_id'])->exists()) {
                    return back()->with('error', 'Invalid item in cart.')->withInput();
                }
            } elseif ($item['type'] === 'souvenir') {
                if (! DB::table('souvenirs')->where('id', $item['reference_id'])->exists()) {
                    return back()->with('error', 'Invalid item in cart.')->withInput();
                }
            }
        }

        // Calculate totals server-side
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $opts = is_string($item['options'] ?? null) ? json_decode($item['options'], true) : ($item['options'] ?? []);
            if ($item['type'] === 'souvenir') {
                $subtotal += (float) DB::table('souvenirs')->where('id', $item['reference_id'])->value('price') * ($item['quantity'] ?? 1);
            } elseif ($item['type'] === 'ticket') {
                $price = (float) (DB::table('movies')->where('id', $item['reference_id'])->value('price') ?? 9.99);
                $subtotal += $price * count($opts['seat_ids'] ?? []);
            }
        }

        $shippingCost = match ($request->delivery) {
            'courier' => 4.99,
            'locker' => 2.99,
            default => 0.00,
        };
        $total = round($subtotal + $shippingCost, 2);

        $orderId = null;

        try {
            DB::transaction(function () use ($request, $cartItems, $subtotal, $shippingCost, $total, &$orderId) {
                $orderId = DB::table('orders')->insertGetId([
                    'user_id' => Auth::id(),
                    'guest_email' => Auth::check() ? null : $request->email,
                    'guest_first_name' => Auth::check() ? null : $request->firstName,
                    'guest_last_name' => Auth::check() ? null : $request->lastName,
                    'guest_phone' => $request->phone,
                    'status' => 'paid',
                    'delivery_type' => $request->delivery,
                    'shipping_street' => $request->address1,
                    'shipping_city' => $request->city,
                    'shipping_postal_code' => $request->postal,
                    'shipping_country' => $request->country,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'tax' => 0,
                    'total_amount' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($cartItems as $item) {
                    $opts = is_string($item['options'] ?? null) ? json_decode($item['options'], true) : ($item['options'] ?? []);

                    if ($item['type'] === 'souvenir') {
                        // Lock the row to prevent concurrent overselling
                        $souvenir = DB::table('souvenirs')
                            ->where('id', $item['reference_id'])
                            ->lockForUpdate()
                            ->first();

                        if (! $souvenir || $souvenir->quantity < ($item['quantity'] ?? 1)) {
                            throw new \RuntimeException('out_of_stock:'.($souvenir->name ?? 'item'));
                        }

                        DB::table('order_souvenirs')->insert([
                            'order_id' => $orderId,
                            'souvenir_id' => $souvenir->id,
                            'quantity' => $item['quantity'] ?? 1,
                            'price' => $souvenir->price,
                        ]);
                        DB::table('souvenirs')->where('id', $souvenir->id)->decrement('quantity', $item['quantity'] ?? 1);

                    } elseif ($item['type'] === 'ticket') {
                        $slotId = $opts['schedule_slot_id'];
                        $seatIds = $opts['seat_ids'];
                        $price = (float) (DB::table('movies')->where('id', $item['reference_id'])->value('price') ?? 9.99);

                        // Re-check seat availability inside the transaction to close the race window
                        $taken = DB::table('tickets')
                            ->join('order_tickets', 'tickets.id', '=', 'order_tickets.ticket_id')
                            ->join('orders', 'order_tickets.order_id', '=', 'orders.id')
                            ->where('orders.status', 'paid')
                            ->where('tickets.schedule_slot_id', $slotId)
                            ->whereIn('tickets.seat_id', $seatIds)
                            ->count();

                        if ($taken > 0) {
                            throw new \RuntimeException('seats_taken');
                        }

                        foreach ($seatIds as $seatId) {
                            $ticketId = DB::table('tickets')->insertGetId([
                                'seat_id' => $seatId,
                                'schedule_slot_id' => $slotId,
                                'price' => $price,
                                'created_at' => now(),
                            ]);
                            DB::table('order_tickets')->insert([
                                'order_id' => $orderId,
                                'ticket_id' => $ticketId,
                            ]);
                        }
                    }
                }

                $invoiceNumber = 'INV-MM-'.now()->format('Ymd').'-'.str_pad($orderId, 4, '0', STR_PAD_LEFT);
                DB::table('invoices')->insert([
                    'order_id' => $orderId,
                    'invoice_number' => $invoiceNumber,
                    'issued_at' => now(),
                ]);

                if (Auth::check()) {
                    CartItem::where('user_id', Auth::id())->delete();
                } else {
                    session()->forget('cart');
                }
            });
        } catch (\RuntimeException $e) {
            if (str_starts_with($e->getMessage(), 'out_of_stock:')) {
                $name = substr($e->getMessage(), 13);

                return back()->with('error', "\"$name\" is out of stock.")->withInput();
            }
            if ($e->getMessage() === 'seats_taken') {
                return back()->with('error', 'One or more selected seats are no longer available.')->withInput();
            }
            throw $e;
        }

        $paymentDisplay = match ($request->payment) {
            'card' => 'Credit Card •••• '.substr(str_replace(' ', '', $request->cardNumber ?? '0000'), -4),
            'paypal' => 'PayPal',
            'google-pay' => 'Google Pay',
            'apple-pay' => 'Apple Pay',
            default => ucfirst($request->payment),
        };

        $orderNumber = 'MM-'.now()->format('Ymd').'-'.str_pad($orderId, 4, '0', STR_PAD_LEFT);
        $cartController = new CartController;
        $enrichedItems = $cartController->enrichItems($cartItems)['items'] ?? [];

        session([
            'order_number' => '#'.$orderNumber,
            'order_date' => now()->format('F j, Y'),
            'order_total' => number_format($total, 2).'€',
            'order_email' => Auth::check() ? Auth::user()->email : $request->email,
            'payment_display' => $paymentDisplay,
            'delivery_method' => ucfirst($request->delivery),
            'delivery_address' => $request->delivery !== 'pickup'
                ? implode(', ', array_filter([$request->address1, $request->city, $request->postal, $request->country]))
                : 'In-store pickup',
            'order_items' => $enrichedItems,
            'order_items_json' => json_encode($enrichedItems),
        ]);

        return redirect()->route('confirmation');
    }
}
