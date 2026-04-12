<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function show(string $slug): View
    {
        $movies = DB::table('movies')
            ->join('directors', 'movies.director_id', '=', 'directors.id')
            ->join('studios', 'movies.studio_id', '=', 'studios.id')
            ->join('languages', 'movies.language_id', '=', 'languages.id')
            ->select(
                'movies.id',
                'movies.title',
                'movies.description as synopsis',
                'movies.rating',
                'movies.length_minutes',
                'movies.release_date',
                'directors.name as director',
                'studios.name as studio',
                'languages.name as language'
            )
            ->get();

        $movieRecord = $movies->first(fn ($m) => Str::slug($m->title) === $slug);

        abort_unless($movieRecord, 404);

        $poster = DB::table('movie_images')
            ->where('movie_id', $movieRecord->id)
            ->where('is_primary', true)
            ->value('url');

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
            ->get()
            ->map(function ($s) use ($movieRecord) {
                return [
                    'title' => $s->title,
                    'image' => $s->image,
                    'movie' => $movieRecord->title,
                    'type'  => $s->type,
                    'price' => number_format($s->price, 2) . '€',
                ];
            })->toArray();

        $scheduleSlot = DB::table('schedule_slots')->where('movie_id', $movieRecord->id)->first();

        $seats = [];
        if ($scheduleSlot) {
            $seats = DB::table('seats')->where('hall_id', $scheduleSlot->hall_id)->get(['id', 'row_label', 'seat_number']);
        } else {
            $seats = DB::table('seats')->where('hall_id', 1)->get(['id', 'row_label', 'seat_number']);
        }

        $movieData = [
            'id' => $movieRecord->id,
            'schedule_slot_id' => $scheduleSlot ? $scheduleSlot->id : 1,
            'slug' => $slug,
            'title' => $movieRecord->title,
            'poster' => $poster,
            'genres' => $genres,
            'synopsis' => $movieRecord->synopsis,
            'rating' => $movieRecord->rating . '/10',
            'duration' => $movieRecord->length_minutes . ' min',
            'release_date' => \Carbon\Carbon::parse($movieRecord->release_date)->format('F j, Y'),
            'director' => $movieRecord->director,
            'language' => $movieRecord->language,
            'studio' => $movieRecord->studio,
            'related_souvenirs' => $relatedSouvenirs,
            'seats' => $seats,
        ];

        return view('movie-details', [
            'movie' => $movieData,
        ]);
    }

    public function index(Request $request): View
    {
        $perPage = 20;

        $sort = $request->get('sort', 'most_popular');
        $sortMap = [
            'most_popular' => ['rating', 'desc'],
            'highest_rated' => ['rating', 'desc'],
            'newest' => ['release_date', 'desc'],
            'price_asc' => ['id', 'asc'],
            'price_desc' => ['id', 'desc'],
        ];

        [$orderBy, $direction] = $sortMap[$sort] ?? $sortMap['most_popular'];

        $query = DB::table('movies')
            ->leftJoin('movie_images', function ($join) {
                $join->on('movies.id', '=', 'movie_images.movie_id')
                    ->where('movie_images.is_primary', true);
            })
            ->select('movies.id', 'movies.title', 'movies.rating', 'movies.release_date', 'movie_images.url as image')
            ->orderBy('movies.' . $orderBy, $direction);

        $movies = $query->paginate($perPage)->withQueryString();

        $movieIds = collect($movies->items())->pluck('id')->all();

        $genresRows = DB::table('movie_genres')
            ->join('genres', 'movie_genres.genre_id', '=', 'genres.id')
            ->whereIn('movie_genres.movie_id', $movieIds)
            ->select('movie_genres.movie_id', 'genres.name')
            ->get()
            ->groupBy('movie_id')
            ->map(function ($group) {
                return $group->pluck('name')->implode(', ');
            })
            ->toArray();

        return view('movies', [
            'movies' => $movies,
            'genresByMovie' => $genresRows,
        ]);
    }
}

