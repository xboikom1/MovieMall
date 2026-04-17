<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Souvenirs | MovieMall</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-text">
<x-layout.header />

<div class="flex-col px-6 py-8 bg-dark border-b border-border">
    <div class="flex items-center gap-2 text-xs mb-2 max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="text-placeholder hover:text-accent">Home</a>
        <span class="flex items-center gap-2"><span>/</span><span>Souvenirs</span></span>
    </div>
    <h1 class="max-w-7xl mx-auto text-3xl font-bold">Souvenirs Catalog</h1>
    <p class="max-w-7xl mx-auto text-placeholder text-sm">Explore exclusive movie merchandise, collectibles, and
        memorabilia.</p>
</div>

<main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-8">
    <form method="GET" action="{{ route('souvenirs.index') }}" id="filterForm">
        <input type="hidden" name="sort" value="{{ $sort }}" />

        <div class="flex flex-col gap-6 desktop:flex-row desktop:items-start">

            <!-- filters -->
            <aside
                class="desktop:sticky desktop:top-4 desktop:w-56 desktop:shrink-0 bg-dark rounded-2xl border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)]">
                <div class="text-text px-4 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold">Filters</h2>
                        <a href="{{ route('souvenirs.index') }}"
                           class="bg-button border border-border px-2.5 py-1 rounded-lg text-placeholder text-xs hover:text-text transition">Reset</a>
                    </div>

                    <!-- category -->
                    <h3 class="text-placeholder text-xs font-bold mt-4">CATEGORY</h3>
                    <div class="flex flex-col text-sm my-1 gap-1.5">
                        @foreach ($categories as $cat)
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="accent-accent"
                                    {{ in_array($cat->id, (array) request('categories', [])) ? 'checked' : '' }} />
                                <span class="cursor-pointer transition hover:text-accent">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- mvie tie-in -->
                    <h3 class="text-placeholder text-xs font-bold mt-4">MOVIE TIE-IN</h3>
                    <div class="flex flex-col text-sm my-1 gap-1.5">
                        @foreach ($movies as $movie)
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="movies[]" value="{{ $movie->id }}" class="accent-accent"
                                    {{ in_array($movie->id, (array) request('movies', [])) ? 'checked' : '' }} />
                                <span class="cursor-pointer transition hover:text-accent">{{ $movie->title }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- price range -->
                    <h3 class="text-placeholder text-xs font-bold mt-4 mb-2">PRICE RANGE</h3>
                    <div class="flex items-center gap-2 mb-5">
                        <input type="number" name="price_min" min="{{ $priceMin }}" max="{{ $priceMax }}" step="0.01" placeholder="{{ $priceMin }}"
                               value="{{ request('price_min') }}"
                               class="w-full rounded-lg border border-border bg-button px-2 py-1.5 text-sm text-text outline-none focus:border-accent" />
                        <span class="text-placeholder text-xs shrink-0">–</span>
                        <input type="number" name="price_max" min="{{ $priceMin }}" max="{{ $priceMax }}" step="0.01" placeholder="{{ $priceMax }}"
                               value="{{ request('price_max') }}"
                               class="w-full rounded-lg border border-border bg-button px-2 py-1.5 text-sm text-text outline-none focus:border-accent" />
                        <span class="text-placeholder text-xs shrink-0">€</span>
                    </div>

                    <button type="submit"
                            class="bg-accent rounded-xl w-full px-4 py-2.5 mb-1 text-sm font-semibold transition hover:brightness-110">
                        Apply Filters
                    </button>
                </div>
            </aside>

            <!-- souvenirs grid -->
            <section class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <p class="text-sm text-placeholder">
                        Showing <span
                            class="text-text font-bold">{{ $souvenirs->firstItem() ?? 0 }}–{{ $souvenirs->lastItem() ?? 0 }}</span>
                        of <span class="text-text font-bold">{{ $souvenirs->total() }}</span> souvenirs
                    </p>
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-placeholder">Sort:</p>
                        <select name="sort" class="bg-button border border-border rounded-lg px-3 py-1 text-sm text-text focus:border-accent outline-none">
                            <option value="newest"     {{ $sort === 'newest'     ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc"   {{ $sort === 'name_asc'   ? 'selected' : '' }}>Name A–Z</option>
                        </select>
                        <button type="submit" class="rounded-lg border border-border bg-button px-3 py-1 text-sm transition hover:border-accent hover:bg-accent">Go</button>
                    </div>
                </div>

                @if ($souvenirs->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-placeholder">
                        <p class="text-lg font-semibold">No souvenirs found</p>
                        <p class="text-sm mt-1">Try adjusting your filters.</p>
                    </div>
                @else
                    <ul class="grid grid-cols-2 gap-3 tablet:grid-cols-3 desktop:grid-cols-5">
                        @foreach ($souvenirs as $s)
                            <li class="group bg-dark rounded-xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-[1.03]">
                                <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($s->name)) }}"
                                   class="block relative h-full">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                                    <div class="aspect-square overflow-hidden">
                                        <img src="{{ $s->image ?? '/images/grandpa/grandpa_figurine.jpg' }}"
                                             alt="{{ $s->name }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                    </div>
                                    <div class="flex flex-col gap-1 p-2 pb-3">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="truncate text-sm font-semibold">{{ $s->name }}</span>
                                            <span class="shrink-0 text-xs font-bold text-accent">{{ number_format($s->price, 2) }}€</span>
                                        </div>
                                        <span
                                            class="truncate text-xs text-placeholder">{{ $s->movie_title ?? '—' }}</span>
                                        <span
                                            class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">
                          {{ $s->category ?? '—' }}
                        </span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- paging -->
                    <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm text-placeholder">Page <strong
                                class="text-text">{{ $souvenirs->currentPage() }}</strong>
                            of {{ $souvenirs->lastPage() }}</p>
                        <div class="flex flex-wrap items-center gap-1.5 text-sm">
                            @if ($souvenirs->onFirstPage())
                                <span
                                    class="cursor-not-allowed rounded-lg border border-border bg-button px-3 py-1.5 opacity-40">← Prev</span>
                            @else
                                <a href="{{ $souvenirs->previousPageUrl() }}"
                                   class="rounded-lg border border-border bg-button px-3 py-1.5">← Prev</a>
                            @endif

                            @foreach (range(1, $souvenirs->lastPage()) as $p)
                                @if ($p == $souvenirs->currentPage())
                                    <span
                                        class="rounded-lg border border-accent bg-accent px-3 py-1.5 font-bold">{{ $p }}</span>
                                @elseif ($p <= 3 || $p > $souvenirs->lastPage() - 3 || ($p >= $souvenirs->currentPage() - 1 && $p <= $souvenirs->currentPage() + 1))
                                    <a href="{{ $souvenirs->url($p) }}"
                                       class="rounded-lg border border-border bg-button px-3 py-1.5 transition hover:border-accent hover:bg-accent">{{ $p }}</a>
                                @elseif ($p == 4 || $p == $souvenirs->lastPage() - 3)
                                    <span class="px-1 text-placeholder">…</span>
                                @endif
                            @endforeach

                            @if ($souvenirs->hasMorePages())
                                <a href="{{ $souvenirs->nextPageUrl() }}"
                                   class="rounded-lg border border-border bg-button px-3 py-1.5">Next →</a>
                            @else
                                <span
                                    class="cursor-not-allowed rounded-lg border border-border bg-button px-3 py-1.5 opacity-40">Next →</span>
                            @endif
                        </div>
                    </div>
                @endif
            </section>

        </div>
    </form>
</main>

<x-layout.footer />


</body>
</html>
