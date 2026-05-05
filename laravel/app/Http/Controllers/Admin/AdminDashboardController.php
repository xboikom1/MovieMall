<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function create(): View
    {
        return view('admin.product.new');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:movie,souvenir'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'images' => ['required', 'array', 'min:2'],
            'images.*' => ['image', 'max:5120'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            if ($validated['type'] === 'movie') {
                $id = DB::table('movies')->insertGetId([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'price' => $validated['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($validated['images'] as $index => $image) {
                    $path = $image->store('images', 'public');
                    $url = '/storage/' . $path;
                    
                    DB::table('movie_images')->insert([
                        'movie_id' => $id,
                        'url' => $url,
                        'is_primary' => $index === 0,
                        'created_at' => now(),
                    ]);
                }
            } else {
                $id = DB::table('souvenirs')->insertGetId([
                    'name' => $validated['title'],
                    'price' => $validated['price'],
                    'quantity' => $validated['quantity'] ?? 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($validated['images'] as $index => $image) {
                    $path = $image->store('images', 'public');
                    $url = '/storage/' . $path;
                    
                    DB::table('souvenir_images')->insert([
                        'souvenir_id' => $id,
                        'url' => $url,
                        'is_primary' => $index === 0,
                        'created_at' => now(),
                    ]);
                }
            }
        });

        return redirect()->route('admin.dashboard')->with('status', 'Product created successfully!');
    }

    public function index(Request $request): View
    {
        $filter = $this->normalizeFilter($request->query('filter', 'all'));
        $search = trim($request->query('search', ''));
        $perPage = 10;

        if ($filter === 'movies') {
            $query = $this->movieBaseQuery();
            if ($search !== '') {
                $query->where('movies.title', 'like', '%' . $search . '%');
            }
            $products = $query
                ->orderByDesc('movies.updated_at')
                ->paginate($perPage)
                ->withQueryString()
                ->through(fn (object $row) => $this->mapMovieRow($row));
        } elseif ($filter === 'souvenirs') {
            $query = $this->souvenirBaseQuery();
            if ($search !== '') {
                $query->where('souvenirs.name', 'like', '%' . $search . '%');
            }
            $products = $query
                ->orderByDesc('souvenirs.updated_at')
                ->paginate($perPage)
                ->withQueryString()
                ->through(fn (object $row) => $this->mapSouvenirRow($row));
        } else {
            $movieQuery = $this->movieBaseQuery();
            $souvenirQuery = $this->souvenirBaseQuery();
            if ($search !== '') {
                $movieQuery->where('movies.title', 'like', '%' . $search . '%');
                $souvenirQuery->where('souvenirs.name', 'like', '%' . $search . '%');
            }
            $movies = $movieQuery->get()->map(fn (object $row) => $this->mapMovieRow($row));
            $souvenirs = $souvenirQuery->get()->map(fn (object $row) => $this->mapSouvenirRow($row));
            $merged = $movies->concat($souvenirs)
                ->sortByDesc(fn (object $product) => $product->updated_at)
                ->values();

            $currentPage = (int) max(1, LengthAwarePaginator::resolveCurrentPage());
            $total = $merged->count();
            $offset = ($currentPage - 1) * $perPage;
            $items = $merged->slice($offset, $perPage)->values();

            $products = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ]
            );
        }

        return view('admin.dashboard', [
            'products' => $products,
            'filter' => $filter,
            'search' => $search,
        ]);
    }

    public function edit(string $type, int $id): View
    {
        $productType = $this->normalizeType($type);
        abort_unless($productType !== null, 404);

        if ($productType === 'movie') {
            $product = DB::table('movies')
                ->leftJoin('movie_images', function ($join) {
                    $join->on('movies.id', '=', 'movie_images.movie_id')
                        ->where('movie_images.is_primary', true);
                })
                ->select(
                    'movies.id',
                    'movies.title',
                    'movies.description',
                    'movies.price',
                    'movies.rating',
                    'movies.release_date',
                    'movies.length_minutes',
                    'movie_images.url as image'
                )
                ->where('movies.id', $id)
                ->first();

            abort_unless($product !== null, 404);

            $images = DB::table('movie_images')
                ->where('movie_id', $id)
                ->orderByDesc('is_primary')
                ->get(['id', 'url', 'is_primary']);

            return view('admin.product.edit', [
                'type' => 'movie',
                'product' => $product,
                'images' => $images,
            ]);
        }

        $product = DB::table('souvenirs')
            ->leftJoin('souvenir_images', function ($join) {
                $join->on('souvenirs.id', '=', 'souvenir_images.souvenir_id')
                    ->where('souvenir_images.is_primary', true);
            })
            ->select(
                'souvenirs.id',
                'souvenirs.name as title',
                'souvenirs.price',
                'souvenirs.quantity',
                'souvenirs.category_id',
                'souvenirs.movie_id',
                'souvenirs.status_id',
                'souvenir_images.url as image'
            )
            ->where('souvenirs.id', $id)
            ->first();

        abort_unless($product !== null, 404);

        $images = DB::table('souvenir_images')
            ->where('souvenir_id', $id)
            ->orderByDesc('is_primary')
            ->get(['id', 'url', 'is_primary']);

        return view('admin.product.edit', [
            'type' => 'souvenir',
            'product' => $product,
            'images' => $images,
            'categories' => DB::table('category')->orderBy('name')->get(['id', 'name']),
            'movies' => DB::table('movies')->orderBy('title')->get(['id', 'title']),
            'statuses' => DB::table('souvenir_status')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function addImage(Request $request, string $type, int $id): RedirectResponse
    {
        $productType = $this->normalizeType($type);
        abort_unless($productType !== null, 404);

        $request->validate(['image' => ['required', 'image', 'max:5120']]);

        $path = $request->file('image')->store('images', 'public');
        $url = '/storage/' . $path;

        if ($productType === 'movie') {
            abort_unless(DB::table('movies')->where('id', $id)->exists(), 404);
            DB::table('movie_images')->insert([
                'movie_id' => $id,
                'url' => $url,
                'is_primary' => false,
                'created_at' => now(),
            ]);
        } else {
            abort_unless(DB::table('souvenirs')->where('id', $id)->exists(), 404);
            DB::table('souvenir_images')->insert([
                'souvenir_id' => $id,
                'url' => $url,
                'is_primary' => false,
                'created_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.product.edit', ['type' => $productType, 'id' => $id])
            ->with('status', 'Image added successfully.');
    }

    public function removeImage(Request $request, string $type, int $imageId): RedirectResponse
    {
        $productType = $this->normalizeType($type);
        abort_unless($productType !== null, 404);

        $productId = (int) $request->input('product_id');

        if ($productType === 'movie') {
            DB::table('movie_images')->where('id', $imageId)->delete();
        } else {
            DB::table('souvenir_images')->where('id', $imageId)->delete();
        }

        return redirect()
            ->route('admin.product.edit', ['type' => $productType, 'id' => $productId])
            ->with('status', 'Image removed.');
    }

    public function setPrimaryImage(Request $request, string $type, int $imageId): RedirectResponse
    {
        $productType = $this->normalizeType($type);
        abort_unless($productType !== null, 404);

        $productId = (int) $request->input('product_id');

        if ($productType === 'movie') {
            $image = DB::table('movie_images')->where('id', $imageId)->first();
            abort_unless($image !== null, 404);
            DB::table('movie_images')->where('movie_id', $image->movie_id)->update(['is_primary' => false]);
            DB::table('movie_images')->where('id', $imageId)->update(['is_primary' => true]);
        } else {
            $image = DB::table('souvenir_images')->where('id', $imageId)->first();
            abort_unless($image !== null, 404);
            DB::table('souvenir_images')->where('souvenir_id', $image->souvenir_id)->update(['is_primary' => false]);
            DB::table('souvenir_images')->where('id', $imageId)->update(['is_primary' => true]);
        }

        return redirect()
            ->route('admin.product.edit', ['type' => $productType, 'id' => $productId])
            ->with('status', 'Main image updated.');
    }

    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        $productType = $this->normalizeType($type);
        abort_unless($productType !== null, 404);

        if ($productType === 'movie') {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:200'],
                'description' => ['nullable', 'string'],
                'price' => ['nullable', 'numeric', 'min:0'],
                'rating' => ['nullable', 'numeric', 'between:0,10'],
                'release_date' => ['nullable', 'date'],
                'length_minutes' => ['nullable', 'integer', 'min:1'],
            ]);

            $updated = DB::table('movies')
                ->where('id', $id)
                ->update([
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'] ?? null,
                    'rating' => $validated['rating'] ?? null,
                    'release_date' => $validated['release_date'] ?? null,
                    'length_minutes' => $validated['length_minutes'] ?? null,
                    'updated_at' => now(),
                ]);

            abort_unless($updated > 0 || DB::table('movies')->where('id', $id)->exists(), 404);

            return redirect()
                ->route('admin.product.edit', ['type' => 'movie', 'id' => $id])
                ->with('status', 'Movie updated successfully.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'integer', 'exists:category,id'],
            'movie_id' => ['nullable', 'integer', 'exists:movies,id'],
            'status_id' => ['nullable', 'integer', 'exists:souvenir_status,id'],
        ]);

        $updated = DB::table('souvenirs')
            ->where('id', $id)
            ->update([
                'name' => $validated['title'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'category_id' => $validated['category_id'] ?? null,
                'movie_id' => $validated['movie_id'] ?? null,
                'status_id' => $validated['status_id'] ?? null,
                'updated_at' => now(),
            ]);

        abort_unless($updated > 0 || DB::table('souvenirs')->where('id', $id)->exists(), 404);

        return redirect()
            ->route('admin.product.edit', ['type' => 'souvenir', 'id' => $id])
            ->with('status', 'Souvenir updated successfully.');
    }

    public function destroy(Request $request, string $type, int $id): RedirectResponse
    {
        $productType = $this->normalizeType($type);
        abort_unless($productType !== null, 404);

        $deleted = $productType === 'movie'
            ? DB::table('movies')->where('id', $id)->delete()
            : DB::table('souvenirs')->where('id', $id)->delete();

        abort_unless($deleted > 0, 404);

        $redirectParams = array_filter([
            'filter' => $this->normalizeFilter($request->input('filter', 'all')),
            'page' => $request->input('page'),
            'search' => $request->input('search', ''),
        ], fn ($value) => $value !== null && $value !== '');

        return redirect()
            ->route('admin.dashboard', $redirectParams)
            ->with('status', ucfirst($productType) . ' deleted successfully.');
    }

    public function orders(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $status = $request->query('status', 'all');

        $query = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('invoices', 'invoices.order_id', '=', 'orders.id')
            ->select(
                'orders.id',
                'orders.status',
                'orders.delivery_type',
                'orders.total_amount',
                'orders.created_at',
                DB::raw("COALESCE(users.first_name || ' ' || users.last_name, orders.guest_first_name || ' ' || orders.guest_last_name) as customer_name"),
                DB::raw("COALESCE(users.email, orders.guest_email) as customer_email"),
                'invoices.invoice_number'
            )
            ->orderByDesc('orders.created_at');

        if ($search !== '') {
            $like = '%' . strtolower($search) . '%';
            $query->where(function ($q) use ($like) {
                $q->whereRaw("LOWER(COALESCE(users.first_name || ' ' || users.last_name, orders.guest_first_name || ' ' || orders.guest_last_name)) LIKE ?", [$like])
                  ->orWhereRaw("LOWER(COALESCE(users.email, orders.guest_email)) LIKE ?", [$like])
                  ->orWhereRaw("LOWER(invoices.invoice_number) LIKE ?", [$like]);
            });
        }

        if ($status !== 'all') {
            $query->where('orders.status', $status);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders', compact('orders', 'search', 'status'));
    }

    private function normalizeFilter(string $filter): string
    {
        return in_array($filter, ['all', 'movies', 'souvenirs'], true) ? $filter : 'all';
    }

    private function normalizeType(string $type): ?string
    {
        return match ($type) {
            'movie', 'movies' => 'movie',
            'souvenir', 'souvenirs' => 'souvenir',
            default => null,
        };
    }

    private function movieBaseQuery(): Builder
    {
        return DB::table('movies')
            ->leftJoin('movie_images', function ($join) {
                $join->on('movies.id', '=', 'movie_images.movie_id')
                    ->where('movie_images.is_primary', true);
            })
            ->select(
                'movies.id',
                'movies.title',
                'movies.description',
                'movies.price',
                'movies.updated_at',
                'movie_images.url as image'
            );
    }

    private function souvenirBaseQuery(): Builder
    {
        return DB::table('souvenirs')
            ->leftJoin('category', 'souvenirs.category_id', '=', 'category.id')
            ->leftJoin('movies', 'souvenirs.movie_id', '=', 'movies.id')
            ->leftJoin('souvenir_images', function ($join) {
                $join->on('souvenirs.id', '=', 'souvenir_images.souvenir_id')
                    ->where('souvenir_images.is_primary', true);
            })
            ->select(
                'souvenirs.id',
                'souvenirs.name as title',
                'souvenirs.price',
                'souvenirs.updated_at',
                'souvenir_images.url as image',
                'category.name as category_name',
                'movies.title as movie_title'
            );
    }

    private function mapMovieRow(object $row): object
    {
        return (object) [
            'id' => $row->id,
            'type' => 'movie',
            'title' => $row->title,
            'description' => $row->description,
            'price' => $row->price,
            'image' => $row->image,
            'updated_at' => $row->updated_at,
        ];
    }

    private function mapSouvenirRow(object $row): object
    {
        $parts = array_filter([$row->category_name ?? null, $row->movie_title ?? null]);

        return (object) [
            'id' => $row->id,
            'type' => 'souvenir',
            'title' => $row->title,
            'description' => count($parts) > 0 ? implode(' | ', $parts) : null,
            'price' => $row->price,
            'image' => $row->image,
            'updated_at' => $row->updated_at,
        ];
    }
}
