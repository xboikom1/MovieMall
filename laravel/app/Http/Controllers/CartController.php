<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(): View
    {
        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->get()->toArray();
        } else {
            $items = $this->getGuestCart();
            foreach ($items as $i => &$item) {
                $item['id'] ??= $i + 1;
            }
            unset($item);
        }

        $enriched = $this->enrichItems($items);
        $tickets = array_values(array_filter($enriched['items'], fn ($i) => $i['type'] === 'ticket'));
        $souvenirs = array_values(array_filter($enriched['items'], fn ($i) => $i['type'] === 'souvenir'));

        return view('cart', [
            'tickets' => $tickets,
            'souvenirs' => $souvenirs,
            'total' => $enriched['total'],
        ]);
    }

    public function removeItem(Request $request): RedirectResponse
    {
        $type = $request->input('type');
        $referenceId = $request->input('reference_id');
        $options = json_decode($request->input('options', '[]'), true) ?? [];

        if (Auth::check()) {
            $query = CartItem::where(['user_id' => Auth::id(), 'type' => $type, 'reference_id' => $referenceId]);
            if (empty($options)) {
                $query->where(fn ($q) => $q->whereNull('options')
                    ->orWhereRaw("options::text = '[]'")->orWhereRaw("options::text = '{}'"));
            } else {
                $query->whereJsonContains('options', $options);
            }
            $query->delete();
        } else {
            $cart = array_values(array_filter(
                $this->getGuestCart(),
                fn ($item) => $item['type'] !== $type
                    || (string) $item['reference_id'] !== (string) $referenceId
                    || json_encode($item['options'] ?? []) !== json_encode($options)
            ));
            $this->saveGuestCart($cart);
        }

        return redirect()->route('cart.index');
    }

    public function updateQuantity(Request $request): RedirectResponse
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $type = $request->input('type');
        $referenceId = $request->input('reference_id');
        $options = json_decode($request->input('options', '[]'), true) ?? [];

        if (Auth::check()) {
            $query = CartItem::where(['user_id' => Auth::id(), 'type' => $type, 'reference_id' => $referenceId]);
            if (empty($options)) {
                $query->where(fn ($q) => $q->whereNull('options')
                    ->orWhereRaw("options::text = '[]'")->orWhereRaw("options::text = '{}'"));
            } else {
                $query->whereJsonContains('options', $options);
            }
            $query->update(['quantity' => $qty]);
        } else {
            $cart = $this->getGuestCart();
            foreach ($cart as &$item) {
                if ($item['type'] === $type
                    && (string) $item['reference_id'] === (string) $referenceId
                    && json_encode($item['options'] ?? []) === json_encode($options)) {
                    $item['quantity'] = $qty;
                    break;
                }
            }
            unset($item);
            $this->saveGuestCart($cart);
        }

        return redirect()->route('cart.index');
    }

    public function addItem(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'souvenir');
        $referenceId = $request->input('reference_id');
        $quantity = max(1, (int) $request->input('quantity', 1));
        $options = json_decode($request->input('options', '[]'), true) ?? [];

        if (Auth::check()) {
            $query = CartItem::where([
                'user_id' => Auth::id(),
                'type' => $type,
                'reference_id' => $referenceId,
            ]);
            if (empty($options)) {
                $query->where(fn ($q) => $q->whereNull('options')
                    ->orWhereRaw("options::text = '[]'")->orWhereRaw("options::text = '{}'"));
            } else {
                $query->whereJsonContains('options', $options);
            }
            $item = $query->first();
            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'reference_id' => $referenceId,
                    'quantity' => $quantity,
                    'options' => empty($options) ? null : $options,
                ]);
            }
        } else {
            $cart = $this->getGuestCart();
            $existingIndex = null;
            foreach ($cart as $i => $cartItem) {
                if ($cartItem['type'] === $type
                    && (string) $cartItem['reference_id'] === (string) $referenceId
                    && json_encode($cartItem['options'] ?? []) === json_encode($options)) {
                    $existingIndex = $i;
                    break;
                }
            }
            if ($existingIndex !== null) {
                $cart[$existingIndex]['quantity'] += $quantity;
            } else {
                $cart[] = [
                    'type' => $type,
                    'reference_id' => $referenceId,
                    'quantity' => $quantity,
                    'options' => empty($options) ? null : $options,
                ];
            }
            $this->saveGuestCart($cart);
        }

        return redirect()->route('cart.index');
    }

    public function editBeginRedirect(Request $request): RedirectResponse
    {
        session(['editing_cart_item' => [
            'type' => $request->input('type'),
            'reference_id' => $request->input('reference_id'),
            'cart_item_id' => $request->input('cart_item_id'),
            'options' => json_decode($request->input('options', '[]'), true) ?? [],
            'quantity' => (int) $request->input('quantity', 1),
        ]]);

        return redirect($request->input('movie_url').'?edit=1');
    }

    private function getGuestCart(): array
    {
        return session('cart', []);
    }

    private function saveGuestCart(array $cart): void
    {
        session(['cart' => array_values($cart)]);
    }

    public function details(): JsonResponse
    {
        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->get()->toArray();
        } else {
            $items = $this->getGuestCart();
            foreach ($items as $i => &$item) {
                $item['id'] ??= $i + 1;
            }
            unset($item);
        }

        return response()->json($this->enrichItems($items));
    }

    public function enrichItems(array $items): array
    {
        $enriched = [];
        $total = 0;

        foreach ($items as $item) {
            $quantity = $item['quantity'];
            $type = $item['type'];

            if ($type === 'souvenir') {
                $souvenir = DB::table('souvenirs')->where('id', $item['reference_id'])->first();
                if (! $souvenir) {
                    continue;
                }

                $category = DB::table('category')->where('id', $souvenir->category_id)->first();
                $imageObj = DB::table('souvenir_images')->where('souvenir_id', $souvenir->id)->where('is_primary', true)->first();

                $enriched[] = [
                    'type' => 'souvenir',
                    'cart_item_id' => $item['id'] ?? null,
                    'reference_id' => $souvenir->id,
                    'options' => $item['options'] ?? null,
                    'name' => $souvenir->name,
                    'description' => $souvenir->name.' · '.($category?->name ?? 'Souvenir'),
                    'image' => $imageObj?->url ?? '',
                    'price' => $souvenir->price,
                    'quantity' => $quantity,
                    'subtotal' => $souvenir->price * $quantity,
                    'url' => route('souvenirs.show', Str::slug($souvenir->name)),
                ];
                $total += $souvenir->price * $quantity;

            } elseif ($type === 'ticket') {
                $opts = is_string($item['options']) ? json_decode($item['options'], true) : ($item['options'] ?? []);
                $movie = DB::table('movies')->where('id', $item['reference_id'])->first();
                $schedule = DB::table('schedule_slots')->where('id', $opts['schedule_slot_id'] ?? null)->first();
                if (! $movie) {
                    continue;
                }

                $hall = $schedule ? DB::table('halls')->where('id', $schedule->hall_id)->first() : null;
                $seatIds = $opts['seat_ids'] ?? [];
                $seats = DB::table('seats')->whereIn('id', $seatIds)->get();

                $seatLabels = $seats->isNotEmpty()
                    ? $seats->map(fn ($s) => 'Row '.$s->row_label.' - '.$s->seat_number)->all()
                    : ($opts['seats'] ?? []);
                $price = $movie->price ?? 9.99;
                $ticketCount = $seats->isNotEmpty() ? $seats->count() : $quantity;

                $imageObj = DB::table('movie_images')->where('movie_id', $movie->id)->where('is_primary', true)->first();

                $genre = DB::table('movie_genres')
                    ->join('genres', 'movie_genres.genre_id', '=', 'genres.id')
                    ->where('movie_genres.movie_id', $movie->id)
                    ->value('genres.name') ?? '';

                $scheduleStr = 'Select Date & Time';
                if (! empty($opts['date']) && ! empty($opts['time'])) {
                    try {
                        $parsed = Carbon::createFromFormat('M j', $opts['date']);
                        $parsed->year = 2026;
                        $scheduleStr = $parsed->format('D, d M Y').' · '.$opts['time'].' · '.($hall?->name ?? 'Hall A');
                    } catch (Exception) {
                        $scheduleStr = $opts['date'].' 2026 · '.$opts['time'].' · '.($hall?->name ?? 'Hall A');
                    }
                } elseif ($schedule) {
                    $scheduleStr = Carbon::parse($schedule->starts_at)->format('D, d M Y · H:i').' · '.($hall?->name ?? '');
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
                    'genre' => $genre,
                    'year' => $movie->release_date ? Carbon::parse($movie->release_date)->format('Y') : '',
                    'rating' => $movie->rating,
                    'image' => $imageObj?->url ?? '',
                    'price' => $price,
                    'quantity' => $ticketCount,
                    'subtotal' => $price * $ticketCount,
                    'url' => route('movies.show', Str::slug($movie->title)),
                ];
                $total += $price * $ticketCount;
            }
        }

        return ['items' => $enriched, 'total' => $total];
    }

    public function add(Request $request): JsonResponse
    {
        $type = $request->type;
        $referenceId = $request->reference_id;
        $options = $request->options ?? [];
        $quantity = $request->quantity ?? 1;

        if (Auth::check()) {
            $query = CartItem::where([
                'user_id' => Auth::id(),
                'type' => $type,
                'reference_id' => $referenceId,
            ]);

            $item = null;

            if ($type === 'ticket') {
                foreach ($query->get() as $existing) {
                    $opts = is_string($existing->options) ? json_decode($existing->options, true) : $existing->options;
                    if (($opts['date'] ?? '') === ($options['date'] ?? '') &&
                        ($opts['time'] ?? '') === ($options['time'] ?? '')) {
                        $item = $existing;
                        break;
                    }
                }
            } else {
                if (empty($options)) {
                    $query->where(fn ($q) => $q->whereNull('options')
                        ->orWhereRaw("options::text = '[]'")->orWhereRaw("options::text = '{}'"));
                } else {
                    $query->whereJsonContains('options', $options);
                }
                $item = $query->first();
            }

            if ($item) {
                if ($type === 'ticket') {
                    $opts = is_string($item->options) ? json_decode($item->options, true) : ($item->options ?? []);
                    $merged = array_values(array_unique(array_merge($opts['seat_ids'] ?? [], $options['seat_ids'] ?? [])));
                    $opts['seat_ids'] = $merged;
                    $item->options = $opts;
                    $item->quantity = count($merged);
                    $item->save();
                } else {
                    $item->increment('quantity', $quantity);
                }
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'reference_id' => $referenceId,
                    'quantity' => $quantity,
                    'options' => empty($options) ? null : $options,
                ]);
            }

            return response()->json(['status' => 'success']);
        }

        // Guest — session cart
        $cart = $this->getGuestCart();
        $existingIndex = null;

        foreach ($cart as $i => $cartItem) {
            if ($cartItem['type'] !== $type || (string) $cartItem['reference_id'] !== (string) $referenceId) {
                continue;
            }
            $itemOpts = $cartItem['options'] ?? [];
            if ($type === 'ticket') {
                if (($itemOpts['date'] ?? '') === ($options['date'] ?? '') &&
                    ($itemOpts['time'] ?? '') === ($options['time'] ?? '')) {
                    $existingIndex = $i;
                    break;
                }
            } elseif (json_encode($itemOpts) === json_encode($options)) {
                $existingIndex = $i;
                break;
            }
        }

        if ($existingIndex !== null) {
            if ($type === 'ticket') {
                $merged = array_values(array_unique(array_merge(
                    $cart[$existingIndex]['options']['seat_ids'] ?? [],
                    $options['seat_ids'] ?? []
                )));
                $cart[$existingIndex]['options']['seat_ids'] = $merged;
                $cart[$existingIndex]['quantity'] = count($merged);
            } else {
                $cart[$existingIndex]['quantity'] += $quantity;
            }
        } else {
            $cart[] = [
                'type' => $type,
                'reference_id' => $referenceId,
                'quantity' => $type === 'ticket' ? count($options['seat_ids'] ?? []) : $quantity,
                'options' => empty($options) ? null : $options,
            ];
        }

        $this->saveGuestCart($cart);

        return response()->json(['status' => 'success']);
    }

    public function remove(Request $request): JsonResponse
    {
        $type = $request->type;
        $referenceId = $request->reference_id;
        $options = $request->options ?? [];

        if (Auth::check()) {
            $query = CartItem::where([
                'user_id' => Auth::id(),
                'type' => $type,
                'reference_id' => $referenceId,
            ]);

            if (empty($options)) {
                $query->where(fn ($q) => $q->whereNull('options')
                    ->orWhereRaw("options::text = '[]'")->orWhereRaw("options::text = '{}'"));
            } else {
                $query->whereJsonContains('options', $options);
            }

            $query->delete();

            return response()->json(['status' => 'success']);
        }

        // Guest — session cart
        $cart = array_values(array_filter(
            $this->getGuestCart(),
            fn ($item) => $item['type'] !== $type
                || (string) $item['reference_id'] !== (string) $referenceId
                || json_encode($item['options'] ?? []) !== json_encode($options)
        ));

        $this->saveGuestCart($cart);

        return response()->json(['status' => 'success']);
    }

    public function sync(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['status' => 'success']);
        }

        $sessionCart = $this->getGuestCart();
        if (empty($sessionCart)) {
            return response()->json(['status' => 'success']);
        }

        foreach ($sessionCart as $req_item) {
            $type = $req_item['type'];
            $referenceId = $req_item['reference_id'];
            $opts = $req_item['options'] ?? [];

            $query = CartItem::where(['user_id' => Auth::id(), 'type' => $type, 'reference_id' => $referenceId]);
            $existing = null;

            if ($type === 'ticket') {
                foreach ($query->get() as $db_item) {
                    $dbOpts = is_string($db_item->options) ? json_decode($db_item->options, true) : $db_item->options;
                    if (($dbOpts['date'] ?? '') === ($opts['date'] ?? '') &&
                        ($dbOpts['time'] ?? '') === ($opts['time'] ?? '')) {
                        $existing = $db_item;
                        break;
                    }
                }
            } else {
                if (empty($opts)) {
                    $query->where(fn ($q) => $q->whereNull('options')
                        ->orWhereRaw("options::text = '[]'")->orWhereRaw("options::text = '{}'"));
                } else {
                    $query->whereJsonContains('options', $opts);
                }
                $existing = $query->first();
            }

            if ($existing) {
                if ($type === 'ticket') {
                    $existingOpts = is_string($existing->options) ? json_decode($existing->options, true) : ($existing->options ?? []);
                    $merged = array_values(array_unique(array_merge($existingOpts['seat_ids'] ?? [], $opts['seat_ids'] ?? [])));
                    $existingOpts['seat_ids'] = $merged;
                    $existing->options = $existingOpts;
                    $existing->quantity = count($merged);
                    $existing->save();
                } else {
                    $existing->increment('quantity', $req_item['quantity'] ?? 1);
                }
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'reference_id' => $referenceId,
                    'quantity' => $req_item['quantity'] ?? 1,
                    'options' => empty($opts) ? null : $opts,
                ]);
            }
        }

        session()->forget('cart');

        return response()->json(['status' => 'success']);
    }

    public function editBegin(Request $request): JsonResponse
    {
        session(['editing_cart_item' => $request->all()]);

        return response()->json(['status' => 'success']);
    }

    public function editEnd(): JsonResponse
    {
        session()->forget('editing_cart_item');

        return response()->json(['status' => 'success']);
    }
}
