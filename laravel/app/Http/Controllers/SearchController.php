<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim($request->input('q', ''));

        $movies = collect();
        $souvenirs = collect();

        if ($q !== '') {
            $like = '%' . $q . '%';

            $movies = DB::table('movies')
                ->leftJoin('movie_images', function ($join) {
                    $join->on('movies.id', '=', 'movie_images.movie_id')
                        ->where('movie_images.is_primary', true);
                })
                ->leftJoin('directors', 'movies.director_id', '=', 'directors.id')
                ->where(function ($sub) use ($like) {
                    $sub->where('movies.title', 'like', $like)
                        ->orWhere('movies.description', 'like', $like)
                        ->orWhere('directors.name', 'like', $like);
                })
                ->select(
                    'movies.id',
                    'movies.title',
                    'movies.rating',
                    'movies.release_date',
                    'movies.price',
                    'movie_images.url as image'
                )
                ->orderBy('movies.rating', 'desc')
                ->get();

            $souvenirs = DB::table('souvenirs')
                ->leftJoin('category', 'souvenirs.category_id', '=', 'category.id')
                ->leftJoin('movies', 'souvenirs.movie_id', '=', 'movies.id')
                ->leftJoin('souvenir_images', function ($join) {
                    $join->on('souvenirs.id', '=', 'souvenir_images.souvenir_id')
                        ->where('souvenir_images.is_primary', true);
                })
                ->where(function ($sub) use ($like) {
                    $sub->where('souvenirs.name', 'like', $like)
                        ->orWhere('category.name', 'like', $like)
                        ->orWhere('movies.title', 'like', $like);
                })
                ->select(
                    'souvenirs.id',
                    'souvenirs.name',
                    'souvenirs.price',
                    'category.name as category',
                    'movies.title as movie_title',
                    'souvenir_images.url as image'
                )
                ->orderBy('souvenirs.name', 'asc')
                ->get();
        }

        return view('search', compact('q', 'movies', 'souvenirs'));
    }
}
