<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('checkout');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
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
            'order_items' => $enrichedItems ?: session('order_items', []),
        ]);

        return response()->json([
            'status' => 'success',
            'redirect' => route('confirmation')
        ]);
    }
}

