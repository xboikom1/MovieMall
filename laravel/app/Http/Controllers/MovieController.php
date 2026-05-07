<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function show(string $slug): View
    {
        $movies = DB::table('movies')
            ->leftJoin('directors', 'movies.director_id', '=', 'directors.id')
            ->leftJoin('studios', 'movies.studio_id', '=', 'studios.id')
            ->leftJoin('languages', 'movies.language_id', '=', 'languages.id')
            ->select(
                'movies.id',
                'movies.title',
                'movies.description as synopsis',
                'movies.rating',
                'movies.length_minutes',
                'movies.release_date',
                'movies.price',
                'directors.name as director',
                'studios.name as studio',
                'languages.name as language'
            )
            ->get();

        $movieRecord = $movies->first(fn ($m) => Str::slug($m->title) === $slug);

        abort_unless($movieRecord, 404);

        $images = DB::table('movie_images')
            ->where('movie_id', $movieRecord->id)
            ->orderByDesc('is_primary')
            ->pluck('url')
            ->toArray();

        $poster = $images[0] ?? null;

        $genres = DB::table('movie_genres')
            ->join('genres', 'movie_genres.genre_id', '=', 'genres.id')
            ->where('movie_id', $movieRecord->id)
            ->pluck('genres.name')
            ->toArray();

        $relatedSouvenirs = DB::table('souvenirs')
            ->join('category', 'souvenirs.category_id', '=', 'category.id')
            ->leftJoin('souvenir_images', function ($join) {
                $join->on('souvenirs.id', '=', 'souvenir_images.souvenir_id')
                     ->where('souvenir_images.is_primary', true);
            })
            ->where('souvenirs.movie_id', $movieRecord->id)
            ->select(
                'souvenirs.name as title',
                'souvenirs.price',
                'category.name as type',
                'souvenir_images.url as image'
            )
            ->limit(4)
            ->get()
            ->map(function ($s) use ($movieRecord) {
                return [
                    'title' => $s->title,
                    'image' => $s->image,
                    'movie' => $movieRecord->title,
                    'type' => $s->type,
                    'price' => number_format($s->price, 2) . '€',
                ];
            })->toArray();

        $allSlots = DB::table('schedule_slots')
            ->where('movie_id', $movieRecord->id)
            ->orderBy('starts_at')
            ->get();

        $firstSlot = $allSlots->first();

        if ($firstSlot) {
            $seats = DB::table('seats')->where('hall_id', $firstSlot->hall_id)->get(['id', 'row_label', 'seat_number']);
        } else {
            $seats = DB::table('seats')->where('hall_id', 1)->get(['id', 'row_label', 'seat_number']);
        }

        $editingItem = session('editing_cart_item');
        if ($editingItem && (
            request()->query('edit') !== '1' ||
            (string) ($editingItem['reference_id'] ?? '') !== (string) $movieRecord->id
        )) {
            $editingItem = null;
        }

        $slotsForView = $allSlots->map(fn($slot) => [
            'id'   => $slot->id,
            'date' => Carbon::parse($slot->starts_at)->format('M j'),
            'time' => Carbon::parse($slot->starts_at)->format('H:i'),
        ])->values()->toArray();

        $uniqueDates  = array_values(array_unique(array_column($slotsForView, 'date')));
        $initialDate  = $editingItem['options']['date'] ?? ($uniqueDates[0] ?? null);
        $initialTimes = array_values(array_column(
            array_filter($slotsForView, fn($s) => $s['date'] === $initialDate),
            'time'
        ));
        $initialTime  = $editingItem['options']['time'] ?? ($initialTimes[0] ?? null);

        // Use editing item's slot if present, otherwise first slot (null if no slots exist)
        $initialSlotId = $firstSlot?->id;
        if ($editingItem && !empty($editingItem['options']['schedule_slot_id'])) {
            $initialSlotId = (int) $editingItem['options']['schedule_slot_id'];
        }

        $bookedSeatIds = $initialSlotId
            ? DB::table('tickets')
                ->join('order_tickets', 'tickets.id', '=', 'order_tickets.ticket_id')
                ->join('orders', 'order_tickets.order_id', '=', 'orders.id')
                ->where('orders.status', 'paid')
                ->where('tickets.schedule_slot_id', $initialSlotId)
                ->pluck('tickets.seat_id')
                ->map(fn($id) => (string) $id)->all()
            : [];

        $movieData = [
            'id'               => $movieRecord->id,
            'schedule_slots'   => $slotsForView,
            'schedule_slot_id' => $initialSlotId,
            'unique_dates'     => $uniqueDates,
            'initial_date'     => $initialDate,
            'initial_times'    => $initialTimes,
            'initial_time'     => $initialTime,
            'slug' => $slug,
            'title' => $movieRecord->title,
            'images' => $images,
            'poster' => $poster,
            'price' => $movieRecord->price,
            'genres' => $genres,
            'synopsis' => $movieRecord->synopsis,
            'rating' => $movieRecord->rating . '/10',
            'duration' => $movieRecord->length_minutes . ' min',
            'release_date' => Carbon::parse($movieRecord->release_date)->format('F j, Y'),
            'director' => $movieRecord->director,
            'language' => $movieRecord->language,
            'studio' => $movieRecord->studio,
            'related_souvenirs' => $relatedSouvenirs,
            'movie_id'          => $movieRecord->id,
            'seats'             => $seats,
            'booked_seat_ids'   => $bookedSeatIds,
            'editing_item'      => $editingItem,
        ];

        return view('movie-details', [
            'movie' => $movieData,
        ]);
    }

    public function bookedSeats(int $id, Request $request)
    {
        $slotId = $request->input('slot_id');
        $ids = DB::table('tickets')
            ->join('order_tickets', 'tickets.id', '=', 'order_tickets.ticket_id')
            ->join('orders', 'order_tickets.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->where('tickets.schedule_slot_id', $slotId)
            ->pluck('tickets.seat_id')
            ->map(fn($id) => (string) $id)
            ->all();
        return response()->json(['booked_seat_ids' => $ids]);
    }

    public function index(Request $request): View
    {
        $sort = $request->input('sort', 'most_popular');
        $sortMap = [
            'most_popular' => ['rating', 'desc'],
            'highest_rated' => ['rating', 'desc'],
            'newest' => ['release_date', 'desc'],
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
        ];
        [$orderBy, $direction] = $sortMap[$sort] ?? $sortMap['most_popular'];

        $query = DB::table('movies')
            ->leftJoin('movie_images', function ($join) {
                $join->on('movies.id', '=', 'movie_images.movie_id')
                    ->where('movie_images.is_primary', true);
            })
            ->select(
                'movies.id',
                'movies.title',
                'movies.rating',
                'movies.release_date',
                'movies.price',
                'movie_images.url as image'
            );

        if ($request->filled('genres')) {
            $query->whereIn('movies.id', function ($sub) use ($request) {
                $sub->select('movie_id')
                    ->from('movie_genres')
                    ->whereIn('genre_id', (array) $request->input('genres'));
            });
        }

        if ($request->filled('year_min')) {
            $query->whereYear('movies.release_date', '>=', (int) $request->input('year_min'));
        }
        if ($request->filled('year_max')) {
            $query->whereYear('movies.release_date', '<=', (int) $request->input('year_max'));
        }
        if ($request->filled('rating_min')) {
            $query->where('movies.rating', '>=', (float) $request->input('rating_min'));
        }
        if ($request->filled('price_min')) {
            $query->where('movies.price', '>=', (float) $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('movies.price', '<=', (float) $request->input('price_max'));
        }

        $query->orderBy('movies.' . $orderBy, $direction);

        $movies = $query->paginate(20)->withQueryString();

        $movieIds = collect($movies->items())->pluck('id')->all();

        $genresRows = DB::table('movie_genres')
            ->join('genres', 'movie_genres.genre_id', '=', 'genres.id')
            ->whereIn('movie_genres.movie_id', $movieIds)
            ->select('movie_genres.movie_id', 'genres.name')
            ->get()
            ->groupBy('movie_id')
            ->map(fn($g) => $g->pluck('name')->implode(', '))
            ->toArray();

        $allGenres = DB::table('genres')->orderBy('name')->get();

        $priceMin = (float) DB::table('movies')->min('price');
        $priceMax = (float) DB::table('movies')->max('price');

        return view('movies', [
            'movies' => $movies,
            'genresByMovie' => $genresRows,
            'allGenres' => $allGenres,
            'sort' => $sort,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
        ]);
    }
}

