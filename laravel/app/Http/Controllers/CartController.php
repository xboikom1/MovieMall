<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart');
    }

    public function details(Request $request)
    {
        $items = [];
        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->get()->toArray();
        } else {
            $items = $request->items ?? [];
        }

        $enriched = [];
        $total = 0;

        foreach ($items as $item) {
            $quantity = $item['quantity'];
            $type = $item['type'];

            if ($type === 'souvenir') {
                $souvenir = DB::table('souvenirs')->where('id', $item['reference_id'])->first();
                if ($souvenir) {
                    $category = DB::table('category')->where('id', $souvenir->category_id)->first();
                    $imageObj = DB::table('souvenir_images')->where('souvenir_id', $souvenir->id)->where('is_primary', true)->first();
                    $image = $imageObj ? $imageObj->url : '';
                    $description = $souvenir->name . ' · ' . ($category ? $category->name : 'Souvenir');
                    $enriched[] = [
                        'type' => 'souvenir',
                        'cart_item_id' => $item['id'] ?? null,
                        'reference_id' => $souvenir->id,
                        'options' => $item['options'] ?? null,
                        'name' => $souvenir->name,
                        'description' => $description,
                        'image' => $image,
                        'price' => $souvenir->price,
                        'quantity' => $quantity,
                        'subtotal' => $souvenir->price * $quantity,
                        'url' => route('souvenirs.show', \Illuminate\Support\Str::slug($souvenir->name)),
                    ];
                    $total += $souvenir->price * $quantity;
                }
            } elseif ($type === 'ticket') {
                $opts = $item['options'] ?? [];
                $movie = DB::table('movies')->where('id', $item['reference_id'])->first();
                $schedule = DB::table('schedule_slots')->where('id', $opts['schedule_slot_id'] ?? null)->first();

                if ($movie) {
                    $hall = null;
                    if ($schedule) {
                        $hall = DB::table('halls')->where('id', $schedule->hall_id)->first();
                    }

                    $seatIds = $opts['seat_ids'] ?? [];

                    $seats = DB::table('seats')->whereIn('id', $seatIds)->get();
                    $seatLabels = count($seats) > 0
                        ? $seats->map(function($s) { return 'Row ' . $s->row_label . ' - ' . $s->seat_number; })->toArray()
                        : (isset($opts['seats']) ? $opts['seats'] : []);

                    $price = 9.99;
                    $ticketCount = count($seats) > 0 ? count($seats) : $quantity;
                    $subtotal = $price * $ticketCount;

                    $imageObj = DB::table('movie_images')->where('movie_id', $movie->id)->where('is_primary', true)->first();
                    $image = $imageObj ? $imageObj->url : '';

                    $genreStr = '';
                    $movieGenre = DB::table('movie_genres')->where('movie_id', $movie->id)->first();
                    if($movieGenre) {
                        $genre = DB::table('genres')->where('id', $movieGenre->genre_id)->first();
                        if($genre) {
                            $genreStr = $genre->name;
                        }
                    }

                    $enriched[] = [
                        'type' => 'ticket',
                        'cart_item_id' => $item['id'] ?? null,
                        'reference_id' => $movie->id,
                        'options' => $opts,
                        'name' => $movie->title,
                        'description' => 'Tickets',
                        'schedule' => $schedule ? \Carbon\Carbon::parse($schedule->starts_at)->format('D, d M Y') . ' · ' . \Carbon\Carbon::parse($schedule->starts_at)->format('H:i') . ' · ' . ($hall->name ?? '') : 'Select Date & Time',
                        'seats' => $seatLabels,
                        'genre' => $genreStr,
                        'year' => $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('Y') : '',
                        'rating' => $movie->rating,
                        'image' => $image,
                        'price' => $price,
                        'quantity' => $ticketCount,
                        'subtotal' => $subtotal,
                        'url' => route('movies.show', \Illuminate\Support\Str::slug($movie->title)),
                    ];
                    $total += $subtotal;
                }
            }
        }

        return response()->json([
            'items' => $enriched,
            'total' => $total
        ]);
    }

    public function add(Request $request)
    {
        if (Auth::check()) {
            $query = CartItem::where([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'reference_id' => $request->reference_id,
            ]);

            if (empty($request->options) || (is_array($request->options) && count($request->options) == 0)) {
                $query->where(function($q) {
                    $q->whereNull('options')
                      ->orWhereRaw("options::text = '[]'")
                      ->orWhereRaw("options::text = '{}'");
                });
            } else {
                $query->whereJsonContains('options', $request->options);
            }

            $item = $query->first();

            if ($item) {
                $item->increment('quantity', $request->quantity ?? 1);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'type' => $request->type,
                    'reference_id' => $request->reference_id,
                    'quantity' => $request->quantity ?? 1,
                    'options' => (empty($request->options) || (is_array($request->options) && count($request->options) == 0)) ? null : $request->options,
                ]);
            }

            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'unauthorized'], 401);
    }

    public function remove(Request $request)
    {
        if (Auth::check()) {
            $query = CartItem::where([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'reference_id' => $request->reference_id,
            ]);

            if (empty($request->options) || (is_array($request->options) && count($request->options) == 0)) {
                $query->where(function($q) {
                    $q->whereNull('options')
                      ->orWhereRaw("options::text = '[]'")
                      ->orWhereRaw("options::text = '{}'");
                });
            } else {
                $query->whereJsonContains('options', $request->options);
            }

            $query->delete();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'unauthorized'], 401);
    }

    public function sync(Request $request)
    {
        if (Auth::check() && $request->items) {
            foreach ($request->items as $item) {
                $existing_query = CartItem::where([
                    'user_id' => Auth::id(),
                    'type' => $item['type'],
                    'reference_id' => $item['reference_id'],
                ]);

                if (empty($item['options']) || (is_array($item['options']) && count($item['options']) == 0)) {
                    $existing_query->where(function($q) {
                        $q->whereNull('options')
                          ->orWhereRaw("options::text = '[]'")
                          ->orWhereRaw("options::text = '{}'");
                    });
                } else {
                    $existing_query->whereJsonContains('options', $item['options']);
                }
                $existing = $existing_query->first();

                if ($existing) {
                    $existing->increment('quantity', $item['quantity'] ?? 1);
                } else {
                    CartItem::create([
                        'user_id' => Auth::id(),
                        'type' => $item['type'],
                        'reference_id' => $item['reference_id'],
                        'quantity' => $item['quantity'] ?? 1,
                        'options' => (empty($item['options']) || (is_array($item['options']) && count($item['options']) == 0)) ? null : $item['options'],
                    ]);
                }
            }
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'success']);
    }
}
