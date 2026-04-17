<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SouvenirController extends Controller
{
    public function index(Request $request): View
    {
        $query = DB::table('souvenirs')
            ->leftJoin('category', 'souvenirs.category_id', '=', 'category.id')
            ->leftJoin('movies', 'souvenirs.movie_id', '=', 'movies.id')
            ->leftJoin('souvenir_images', function ($join) {
                $join->on('souvenirs.id', '=', 'souvenir_images.souvenir_id')
                    ->where('souvenir_images.is_primary', true);
            })
            ->select(
                'souvenirs.id',
                'souvenirs.name',
                'souvenirs.price',
                'category.name as category',
                'movies.title as movie_title',
                'souvenir_images.url as image'
            );

        if ($request->filled('categories')) {
            $query->whereIn('souvenirs.category_id', (array) $request->input('categories'));
        }

        if ($request->filled('movies')) {
            $query->whereIn('souvenirs.movie_id', (array) $request->input('movies'));
        }

        if ($request->filled('price_min')) {
            $query->where('souvenirs.price', '>=', (float) $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('souvenirs.price', '<=', (float) $request->input('price_max'));
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('souvenirs.price', 'asc'),
            'price_desc' => $query->orderBy('souvenirs.price', 'desc'),
            'name_asc'   => $query->orderBy('souvenirs.name', 'asc'),
            default      => $query->orderBy('souvenirs.id', 'desc'),
        };

        $souvenirs  = $query->paginate(20)->withQueryString();
        $categories = DB::table('category')->orderBy('name')->get();
        $movies     = DB::table('movies')->orderBy('title')->get();

        $priceMin = (float) DB::table('souvenirs')->min('price');
        $priceMax = (float) DB::table('souvenirs')->max('price');

        return view('souvenirs', compact('souvenirs', 'categories', 'movies', 'sort', 'priceMin', 'priceMax'));
    }

    public function show(string $slug): View
    {
        $rows = DB::table('souvenirs')
            ->leftJoin('category', 'souvenirs.category_id', '=', 'category.id')
            ->leftJoin('souvenir_images', function ($join) {
                $join->on('souvenirs.id', '=', 'souvenir_images.souvenir_id')
                    ->where('souvenir_images.is_primary', true);
            })
            ->select(
                'souvenirs.id',
                'souvenirs.name',
                'souvenirs.price',
                'souvenirs.movie_id',
                'category.name as category',
                'souvenir_images.url as image'
            )
            ->get();

        $souvenirRecord = $rows->first(fn($s) => Str::slug($s->name) === $slug);

        abort_unless($souvenirRecord !== null, 404);

        $movieTitle = DB::table('movies')->where('id', $souvenirRecord->movie_id)->value('title');

        $related = DB::table('souvenirs')
            ->leftJoin('category', 'souvenirs.category_id', '=', 'category.id')
            ->leftJoin('souvenir_images', function ($join) {
                $join->on('souvenirs.id', '=', 'souvenir_images.souvenir_id')
                    ->where('souvenir_images.is_primary', true);
            })
            ->where('souvenirs.id', '!=', $souvenirRecord->id)
            ->select('souvenirs.name as title', 'souvenirs.price', 'category.name as type', 'souvenir_images.url as image')
            ->inRandomOrder()
            ->limit(8)
            ->get()
            ->map(function ($s) use ($movieTitle) {
                return [
                    'title' => $s->title,
                    'image' => $s->image ?? '/images/SuperGrandpaSouvenir.png',
                    'movie' => $movieTitle,
                    'type' => $s->type,
                    'price' => number_format($s->price, 2) . '€',
                ];
            })->toArray();

        $souvenir = [
            'id' => $souvenirRecord->id,
            'slug' => $slug,
            'title' => $souvenirRecord->name,
            'image' => $souvenirRecord->image ?? '/images/SuperGrandpaSouvenir.png',
            'price' => number_format($souvenirRecord->price, 2) . '€',
            'in_stock' => true,
            'category' => $souvenirRecord->category,
            'movie' => $movieTitle,
            'description' => '',
            'related_souvenirs' => $related,
        ];

        return view('souvenir-details', ['souvenir' => $souvenir]);
    }
}
