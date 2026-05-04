<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('checkout');
    }

    public function submit(Request $request): JsonResponse
    {
        $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName'  => 'required|string|max:100',
            'email'     => 'required|email',
            'phone'     => 'nullable|string|max:30',

            'delivery' => 'required|in:courier,locker,pickup',
            'address1' => 'required_if:delivery,courier,locker|nullable|string|max:255',
            'city'     => 'required_if:delivery,courier,locker|nullable|string|max:100',
            'postal'   => 'required_if:delivery,courier,locker|nullable|string|max:20',
            'country'  => 'required_if:delivery,courier,locker|nullable|string|max:100',

            'payment'    => 'required|in:card,paypal,google-pay,apple-pay',
            'cardNumber' => 'required_if:payment,card|nullable|regex:/^[0-9 ]{12,19}$/',
            'expiry'     => 'required_if:payment,card|nullable|regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/',
            'cvv'        => 'required_if:payment,card|nullable|digits_between:3,4',
            'cardName'   => 'required_if:payment,card|nullable|string|max:100',

            'items' => 'nullable|array',
        ]);

        // Load cart items
        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())->get()->toArray();
        } else {
            $cartItems = array_map(function ($item) {
                if (isset($item['options']) && is_string($item['options'])) {
                    $item['options'] = json_decode($item['options'], true);
                }
                return $item;
            }, $request->input('items', []));
        }

        if (empty($cartItems)) {
            return response()->json(['status' => 'error', 'message' => 'Your cart is empty.'], 422);
        }

        // Validate availability before touching the DB
        foreach ($cartItems as $item) {
            $opts = is_string($item['options'] ?? null) ? json_decode($item['options'], true) : ($item['options'] ?? []);

            if ($item['type'] === 'souvenir') {
                $souvenir = DB::table('souvenirs')->where('id', $item['reference_id'])->first();
                if (!$souvenir || $souvenir->quantity < ($item['quantity'] ?? 1)) {
                    $name = $souvenir->name ?? 'A souvenir';
                    return response()->json(['status' => 'error', 'message' => "\"$name\" is out of stock."], 422);
                }
            } elseif ($item['type'] === 'ticket') {
                $slotId  = $opts['schedule_slot_id'] ?? null;
                $seatIds = $opts['seat_ids'] ?? [];
                if ($slotId && !empty($seatIds)) {
                    $taken = DB::table('tickets')
                        ->join('order_tickets', 'tickets.id', '=', 'order_tickets.ticket_id')
                        ->join('orders', 'order_tickets.order_id', '=', 'orders.id')
                        ->where('orders.status', 'paid')
                        ->where('tickets.schedule_slot_id', $slotId)
                        ->whereIn('tickets.seat_id', $seatIds)
                        ->count();
                    if ($taken > 0) {
                        return response()->json(['status' => 'error', 'message' => 'One or more selected seats are no longer available.'], 422);
                    }
                }
            }
        }

        // Calculate totals server-side
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $opts = is_string($item['options'] ?? null) ? json_decode($item['options'], true) : ($item['options'] ?? []);
            if ($item['type'] === 'souvenir') {
                $price     = (float) DB::table('souvenirs')->where('id', $item['reference_id'])->value('price');
                $subtotal += $price * ($item['quantity'] ?? 1);
            } elseif ($item['type'] === 'ticket') {
                $price     = (float) (DB::table('movies')->where('id', $item['reference_id'])->value('price') ?? 9.99);
                $count     = count($opts['seat_ids'] ?? []) ?: ($item['quantity'] ?? 1);
                $subtotal += $price * $count;
            }
        }

        $shippingCost = match ($request->delivery) {
            'courier' => 4.99,
            'locker'  => 2.99,
            default   => 0.00,
        };
        $total = round($subtotal + $shippingCost, 2);

        $orderId = null;
        DB::transaction(function () use ($request, $cartItems, $subtotal, $shippingCost, $total, &$orderId) {
            $orderId = DB::table('orders')->insertGetId([
                'user_id'              => Auth::id(),
                'guest_email'          => Auth::check() ? null : $request->email,
                'guest_first_name'     => Auth::check() ? null : $request->firstName,
                'guest_last_name'      => Auth::check() ? null : $request->lastName,
                'guest_phone'          => $request->phone,
                'status'               => 'paid',
                'delivery_type'        => $request->delivery,
                'shipping_street'      => $request->address1,
                'shipping_city'        => $request->city,
                'shipping_postal_code' => $request->postal,
                'shipping_country'     => $request->country,
                'subtotal'             => $subtotal,
                'shipping_cost'        => $shippingCost,
                'tax'                  => 0,
                'total_amount'         => $total,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);

            foreach ($cartItems as $item) {
                $opts = is_string($item['options'] ?? null) ? json_decode($item['options'], true) : ($item['options'] ?? []);

                if ($item['type'] === 'souvenir') {
                    $souvenir = DB::table('souvenirs')->where('id', $item['reference_id'])->first();
                    DB::table('order_souvenirs')->insert([
                        'order_id'    => $orderId,
                        'souvenir_id' => $souvenir->id,
                        'quantity'    => $item['quantity'] ?? 1,
                        'price'       => $souvenir->price,
                    ]);
                    DB::table('souvenirs')->where('id', $souvenir->id)->decrement('quantity', $item['quantity'] ?? 1);
                } elseif ($item['type'] === 'ticket') {
                    $slotId  = $opts['schedule_slot_id'] ?? null;
                    $seatIds = $opts['seat_ids'] ?? [];
                    $price   = (float) (DB::table('movies')->where('id', $item['reference_id'])->value('price') ?? 9.99);

                    foreach ($seatIds as $seatId) {
                        $ticketId = DB::table('tickets')->insertGetId([
                            'seat_id'          => $seatId,
                            'schedule_slot_id' => $slotId,
                            'price'            => $price,
                            'created_at'       => now(),
                        ]);
                        DB::table('order_tickets')->insert([
                            'order_id'  => $orderId,
                            'ticket_id' => $ticketId,
                        ]);
                    }
                }
            }

            $invoiceNumber = 'INV-MM-' . now()->format('Ymd') . '-' . str_pad($orderId, 4, '0', STR_PAD_LEFT);
            DB::table('invoices')->insert([
                'order_id'       => $orderId,
                'invoice_number' => $invoiceNumber,
                'issued_at'      => now(),
            ]);

            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())->delete();
            }
        });

        $paymentDisplay = match ($request->payment) {
            'card'       => 'Credit Card •••• ' . substr(str_replace(' ', '', $request->cardNumber ?? '0000'), -4),
            'paypal'     => 'PayPal',
            'google-pay' => 'Google Pay',
            'apple-pay'  => 'Apple Pay',
            default      => ucfirst($request->payment),
        };

        $orderNumber = 'MM-' . now()->format('Ymd') . '-' . str_pad($orderId, 4, '0', STR_PAD_LEFT);

        $cartController = new CartController();
        $enrichedItems  = $cartController->details(new Request(['items' => $cartItems]))->getData(true)['items'] ?? [];

        session([
            'order_number'    => '#' . $orderNumber,
            'order_date'      => now()->format('F j, Y'),
            'order_total'     => number_format($total, 2) . '€',
            'order_email'     => Auth::check() ? Auth::user()->email : $request->email,
            'payment_display' => $paymentDisplay,
            'delivery_method' => ucfirst($request->delivery),
            'delivery_address' => $request->delivery !== 'pickup'
                ? implode(', ', array_filter([$request->address1, $request->city, $request->postal, $request->country]))
                : 'In-store pickup',
            'order_items'     => $enrichedItems,
            'order_items_json' => json_encode($enrichedItems),
        ]);

        return response()->json([
            'status'   => 'success',
            'redirect' => route('confirmation'),
        ]);
    }
}
