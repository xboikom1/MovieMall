<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
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
                        'url' => route('souvenirs.show', Str::slug($souvenir->name)),
                    ];
                    $total += $souvenir->price * $quantity;
                }
            } elseif ($type === 'ticket') {
                $opts = is_string($item['options']) ? json_decode($item['options'], true) : ($item['options'] ?? []);
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
                        : ($opts['seats'] ?? []);

                    $price = $movie->price ?? 9.99;
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

                    $scheduleStr = 'Select Date & Time';
                    if (!empty($opts['date']) && !empty($opts['time'])) {
                        try {
                            $parsedDate = Carbon::createFromFormat('M j', $opts['date']);
                            $parsedDate->year = 2026;
                            $scheduleStr = $parsedDate->format('D, d M Y') . ' · ' . $opts['time'] . ' · ' . ($hall->name ?? 'Hall A');
                        } catch (Exception) {
                            $scheduleStr = $opts['date'] . ' 2026 · ' . $opts['time'] . ' · ' . ($hall->name ?? 'Hall A');
                        }
                    } elseif ($schedule) {
                        $scheduleStr = Carbon::parse($schedule->starts_at)->format('D, d M Y') . ' · ' . Carbon::parse($schedule->starts_at)->format('H:i') . ' · ' . ($hall->name ?? '');
                    }

                    $enriched[] = [
                        'type' => 'ticket',
                        'cart_item_id' => $item['id'] ?? null,
                        'reference_id' => $movie->id,
                        'options' => $opts,
                        'name' => $movie->title,
                        'description' => 'Tickets',
                        'schedule' => $scheduleStr,
                        'seats' => $seatLabels,
                        'genre' => $genreStr,
                        'year' => $movie->release_date ? Carbon::parse($movie->release_date)->format('Y') : '',
                        'rating' => $movie->rating,
                        'image' => $image,
                        'price' => $price,
                        'quantity' => $ticketCount,
                        'subtotal' => $subtotal,
                        'url' => route('movies.show', Str::slug($movie->title)),
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

            $item = null;

            if ($request->type === 'ticket') {
                $items = $query->get();
                foreach($items as $existing_item) {
                    $opts = is_string($existing_item->options) ? json_decode($existing_item->options, true) : $existing_item->options;
                    if (isset($opts['date']) && isset($request->options['date']) && $opts['date'] === $request->options['date'] &&
                        isset($opts['time']) && isset($request->options['time']) && $opts['time'] === $request->options['time']) {
                        $item = $existing_item;
                        break;
                    }
                }
            } else {
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
            }

            if ($item) {
                if ($request->type === 'ticket') {
                    $opts = is_string($item->options) ? json_decode($item->options, true) : ($item->options ?? []);
                    $existingSeats = $opts['seat_ids'] ?? [];
                    $newSeats = $request->options['seat_ids'] ?? [];
                    $mergedSeats = array_values(array_unique(array_merge($existingSeats, $newSeats)));

                    $opts['seat_ids'] = $mergedSeats;
                    $item->options = $opts;
                    $item->quantity = count($mergedSeats);
                    $item->save();
                } else {
                    $item->increment('quantity', $request->quantity ?? 1);
                }
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
            foreach ($request->items as $req_item) {
                $existing_query = CartItem::where([
                    'user_id' => Auth::id(),
                    'type' => $req_item['type'],
                    'reference_id' => $req_item['reference_id'],
                ]);

                $existing = null;
                if ($req_item['type'] === 'ticket') {
                    $db_items = $existing_query->get();
                    foreach($db_items as $db_item) {
                        $opts = is_string($db_item->options) ? json_decode($db_item->options, true) : $db_item->options;
                        if (isset($opts['date']) && isset($req_item['options']['date']) && $opts['date'] === $req_item['options']['date'] &&
                            isset($opts['time']) && isset($req_item['options']['time']) && $opts['time'] === $req_item['options']['time']) {
                            $existing = $db_item;
                            break;
                        }
                    }
                } else {
                    if (empty($req_item['options']) || (is_array($req_item['options']) && count($req_item['options']) == 0)) {
                        $existing_query->where(function($q) {
                            $q->whereNull('options')
                              ->orWhereRaw("options::text = '[]'")
                              ->orWhereRaw("options::text = '{}'");
                        });
                    } else {
                        $existing_query->whereJsonContains('options', $req_item['options']);
                    }
                    $existing = $existing_query->first();
                }

                if ($existing) {
                    if ($req_item['type'] === 'ticket') {
                        $opts = is_string($existing->options) ? json_decode($existing->options, true) : ($existing->options ?? []);
                        $existingSeats = $opts['seat_ids'] ?? [];
                        $newSeats = $req_item['options']['seat_ids'] ?? [];
                        $mergedSeats = array_values(array_unique(array_merge($existingSeats, $newSeats)));

                        $opts['seat_ids'] = $mergedSeats;
                        $existing->options = $opts;
                        $existing->quantity = count($mergedSeats);
                        $existing->save();
                    } else {
                        $existing->increment('quantity', $req_item['quantity'] ?? 1);
                    }
                } else {
                    CartItem::create([
                        'user_id' => Auth::id(),
                        'type' => $req_item['type'],
                        'reference_id' => $req_item['reference_id'],
                        'quantity' => $req_item['quantity'] ?? 1,
                        'options' => (empty($req_item['options']) || (is_array($req_item['options']) && count($req_item['options']) == 0)) ? null : $req_item['options'],
                    ]);
                }
            }
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'success']);
    }
}
