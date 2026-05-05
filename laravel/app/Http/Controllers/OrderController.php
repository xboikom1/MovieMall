<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = DB::table('orders')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($order) {
                $invoice = DB::table('invoices')->where('order_id', $order->id)->first();

                $order->order_number = $invoice
                    ? '#' . str_replace('INV-', '', $invoice->invoice_number)
                    : '#MM-' . Carbon::parse($order->created_at)->format('Ymd') . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);

                $order->tickets = DB::table('order_tickets')
                    ->join('tickets', 'order_tickets.ticket_id', '=', 'tickets.id')
                    ->join('seats', 'tickets.seat_id', '=', 'seats.id')
                    ->join('schedule_slots', 'tickets.schedule_slot_id', '=', 'schedule_slots.id')
                    ->join('movies', 'schedule_slots.movie_id', '=', 'movies.id')
                    ->leftJoin('movie_images', function ($join) {
                        $join->on('movie_images.movie_id', '=', 'movies.id')
                             ->where('movie_images.is_primary', true);
                    })
                    ->where('order_tickets.order_id', $order->id)
                    ->select('movies.id as movie_id', 'movies.title', 'movie_images.url as image',
                             'seats.row_label', 'seats.seat_number', 'tickets.price', 'schedule_slots.starts_at')
                    ->get();

                $order->souvenirs = DB::table('order_souvenirs')
                    ->join('souvenirs', 'order_souvenirs.souvenir_id', '=', 'souvenirs.id')
                    ->leftJoin('souvenir_images', function ($join) {
                        $join->on('souvenir_images.souvenir_id', '=', 'souvenirs.id')
                             ->where('souvenir_images.is_primary', true);
                    })
                    ->where('order_souvenirs.order_id', $order->id)
                    ->select('souvenirs.name', 'souvenir_images.url as image',
                             'order_souvenirs.quantity', 'order_souvenirs.price')
                    ->get();

                return $order;
            });

        return view('orders', compact('orders'));
    }
}
