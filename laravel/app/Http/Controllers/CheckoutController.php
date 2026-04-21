<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('checkout');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',

            'delivery' => 'required|in:courier,locker,pickup',
            'address1' => 'required_if:delivery,courier,locker|string|max:255',
            'city' => 'required_if:delivery,courier,locker|string|max:100',
            'postal' => 'required_if:delivery,courier,locker|string|max:20',
            'country' => 'required_if:delivery,courier,locker|string|max:100',

            'payment' => 'required|in:card,paypal,google-pay,apple-pay',
            'cardNumber' => 'required_if:payment,card|nullable|regex:/^[0-9 ]{12,19}$/',
            'expiry' => 'required_if:payment,card|nullable|regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/',
            'cvv' => 'required_if:payment,card|nullable|digits_between:3,4',
            'cardName' => 'required_if:payment,card|nullable|string|max:100',

            'items' => 'nullable|array',
            'items.*.type' => 'required_with:items|string|in:ticket,souvenir',
            'order_total' => 'nullable|numeric',
        ]);

        $orderNumber = $request->input('order_number', '#MM-' . now()->format('Ymd') . '-' . rand(1000, 9999));
        $orderTotalRaw = $request->input('order_total', null);
        $orderEmail = $request->input('order_email', $request->input('email'));
        $orderDateRaw = $request->input('order_date', null);

        $orderDate = null;
        if ($orderDateRaw) {
            if (is_numeric($orderDateRaw)) {
                $ts = (int) $orderDateRaw;
                
                $orderDate = 
                    \Carbon\Carbon::createFromTimestamp($ts)->format('F j, Y');
            } else {
                $orderDate = $orderDateRaw;
            }
        } else {
            $orderDate = now()->format('F j, Y');
        }

        $rawItems = $request->input('items', []);
        $enrichedItems = [];
        $detailsTotal = null;

        if (!empty($rawItems)) {
            $cartReq = new Request(['items' => $rawItems]);
            $cartController = new CartController();
            $resp = $cartController->details($cartReq);
            $data = $resp->getData(true);
            $enrichedItems = $data['items'] ?? [];
            $detailsTotal = $data['total'] ?? null;
        }

        if ($orderTotalRaw === null && $detailsTotal !== null) {
            $orderTotalRaw = $detailsTotal;
        }

        $orderTotal = $orderTotalRaw !== null ? number_format((float) $orderTotalRaw, 2) . '€' : null;

        session([
            'order_number' => $orderNumber,
            'order_date' => $orderDate,
            'order_total' => $orderTotal,
            'order_email' => $orderEmail,
            'delivery_method' => $request->input('delivery', session('delivery_method', 'Courier')),
            'delivery_address' => $request->input('address1', session('delivery_address', '123 Main Street, Bratislava, 81101, Slovakia')),
            'order_items' => $enrichedItems,
       
            'order_items_json' => !empty($enrichedItems) ? json_encode($enrichedItems) : null,
        ]);

        if (Auth::check()) {
            try {
                CartItem::where('user_id', Auth::id())->delete();
            } catch (\Exception $e) {
                    \Log::error('Failed to clear cart for user ' . Auth::id() . ': ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => 'success',
            'redirect' => route('confirmation')
        ]);
    }
}

