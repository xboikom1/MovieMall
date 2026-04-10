<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $topMovies = $this->fetchMovies('rating', 'desc', 5);
        $newReleases = $this->fetchMovies('release_date', 'desc', 5);

        return view('home', compact('topMovies', 'newReleases'));
    }

    private function fetchMovies(string $orderBy, string $direction, int $limit): array
    {
        $movies = DB::table('movies')
            ->leftJoin('movie_images', function ($join) {
                $join->on('movies.id', '=', 'movie_images.movie_id')
                    ->where('movie_images.is_primary', true);
            })
            ->select('movies.id', 'movies.title', 'movies.rating', 'movies.release_date', 'movie_images.url as image')
            ->orderBy('movies.' . $orderBy, $direction)
            ->limit($limit)
            ->get();

        $movieIds = $movies->pluck('id');

        $genresByMovie = DB::table('movie_genres')
            ->join('genres', 'movie_genres.genre_id', '=', 'genres.id')
            ->whereIn('movie_genres.movie_id', $movieIds)
            ->select('movie_genres.movie_id', 'genres.name')
            ->get()
            ->groupBy('movie_id');

        return $movies->map(function ($movie) use ($genresByMovie) {
            $genres = $genresByMovie->has($movie->id)
                ? $genresByMovie->get($movie->id)->pluck('name')->implode(', ')
                : '';

            return [
                'slug'   => Str::slug($movie->title),
                'title'  => $movie->title,
                'image'  => $movie->image,
                'rating' => $movie->rating,
                'year'   => Carbon::parse($movie->release_date)->year,
                'genres' => $genres,
            ];
        })->toArray();
    }
}
