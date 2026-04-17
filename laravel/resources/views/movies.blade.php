<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Movies | MovieMall</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media (max-width: 1023px) {
            .filter-body { display: none; }
            #filter-toggle:checked ~ .filter-body { display: block; }
            #filter-toggle:checked ~ .filter-label .filter-chevron { transform: rotate(180deg); }
        }
    </style>
</head>
<body class="min-h-screen bg-bg text-text">
<x-layout.header />

<div class="flex-col px-6 py-8 bg-dark border-b border-border">
    <div class="flex items-center gap-2 text-xs mb-2 max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="text-placeholder hover:text-accent">Home</a>
        <span class="flex items-center gap-2"><span>/</span><span>Movies</span></span>
    </div>
    <h1 class="max-w-7xl mx-auto text-3xl font-bold">Movies Catalog</h1>
    <p class="max-w-7xl mx-auto text-placeholder text-sm">Browse our full collection of tickets available for
        purchase.</p>
</div>

<main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-8">
    <form method="GET" action="{{ route('movies.index') }}" id="filterForm">

        <div class="flex flex-col gap-6 desktop:flex-row desktop:items-start">

            <!-- filters -->
            <aside class="desktop:sticky desktop:top-4 desktop:w-56 desktop:shrink-0 bg-dark rounded-2xl border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)]">

                <!-- hidden filter mobile-only -->
                <input type="checkbox" id="filter-toggle" class="sr-only">
                <label for="filter-toggle"
                       class="filter-label desktop:hidden flex items-center justify-between px-4 py-3 text-sm font-semibold cursor-pointer select-none">
                    <span>Filters</span>
                    <svg class="filter-chevron w-4 h-4 text-placeholder transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </label>

                <div class="filter-body text-text px-4 pb-4 pt-0 desktop:pt-4">
                    <!-- desktop header -->
                    <div class="hidden desktop:flex items-center justify-between mb-4">
                        <h2 class="font-semibold">Filters</h2>
                        <a href="{{ route('movies.index') }}"
                           class="bg-button border border-border px-2.5 py-1 rounded-lg text-placeholder text-xs hover:text-text transition">Reset</a>
                    </div>

                    <!-- mobile reset -->
                    <div class="flex desktop:hidden items-center justify-end mb-3">
                        <a href="{{ route('movies.index') }}"
                           class="bg-button border border-border px-2.5 py-1 rounded-lg text-placeholder text-xs hover:text-text transition">Reset</a>
                    </div>

                    <!-- genre -->
                    <h3 class="text-placeholder text-xs font-bold mt-2">GENRE</h3>
                    <div class="flex flex-col text-sm my-1 gap-1.5">
                        @foreach ($allGenres as $g)
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="genres[]" value="{{ $g->id }}" class="accent-accent"
                                    {{ in_array($g->id, (array) request('genres', [])) ? 'checked' : '' }} />
                                <span class="cursor-pointer transition hover:text-accent">{{ $g->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- release year -->
                    <h3 class="text-placeholder text-xs font-bold mt-4 mb-2">RELEASE YEAR</h3>
                    <div class="flex items-center gap-2 mb-2">
                        <input type="number" name="year_min" min="1900" max="2030" placeholder="From"
                               value="{{ request('year_min') }}"
                               class="w-full rounded-lg border border-border bg-button px-2 py-1.5 text-sm text-text outline-none focus:border-accent" />
                        <span class="text-placeholder text-xs shrink-0">–</span>
                        <input type="number" name="year_max" min="1900" max="2030" placeholder="To"
                               value="{{ request('year_max') }}"
                               class="w-full rounded-lg border border-border bg-button px-2 py-1.5 text-sm text-text outline-none focus:border-accent" />
                    </div>

                    <!-- rating -->
                    <h3 class="text-placeholder text-xs font-bold mt-4 mb-2">MIN RATING</h3>
                    <div class="flex items-center gap-2 mb-2">
                        <input type="number" name="rating_min" min="0" max="10" step="0.1" placeholder="0"
                               value="{{ request('rating_min') }}"
                               class="w-full rounded-lg border border-border bg-button px-2 py-1.5 text-sm text-text outline-none focus:border-accent" />
                        <span class="text-placeholder text-xs shrink-0">/ 10</span>
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

            <!-- movies grid -->
            <section class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-3 mb-4 flex-wrap">
                    <p class="text-sm text-placeholder">
                        Showing <span
                            class="text-text font-bold">{{ $movies->firstItem() ?? 0 }}–{{ $movies->lastItem() ?? 0 }}</span>
                        of <span class="text-text font-bold">{{ $movies->total() }}</span> movies
                    </p>
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-placeholder">Sort:</p>
                        <select name="sort"
                                class="bg-button border border-border rounded-lg px-3 py-1 text-sm text-text focus:border-accent outline-none">
                            <option value="most_popular" {{$sort === 'most_popular' ? 'selected' : ''}}>
                                Most Popular
                            </option>
                            <option value="highest_rated" {{$sort === 'highest_rated' ? 'selected' : ''}}>
                                Highest Rated
                            </option>
                            <option value="newest" {{$sort === 'newest' ? 'selected' : ''}}>
                                Newest First
                            </option>
                            <option value="price_asc" {{$sort === 'price_asc' ? 'selected' : ''}}>
                                Price: Low to High
                            </option>
                            <option value="price_desc" {{$sort === 'price_desc' ? 'selected' : ''}}>
                                Price: High to Low
                            </option>
                        </select>
                        <button type="submit"
                                class="rounded-lg border border-border bg-button px-3 py-1 text-sm transition hover:border-accent hover:bg-accent">
                            Go
                        </button>
                    </div>
                </div>

                @if ($movies->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-placeholder">
                        <p class="text-lg font-semibold">No movies found</p>
                        <p class="text-sm mt-1">Try adjusting your filters.</p>
                    </div>
                @else
                    <ul class="grid grid-cols-2 gap-3 tablet:grid-cols-3 desktop:grid-cols-5">
                        @foreach ($movies as $movie)
                            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
                                <a href="{{ route('movies.show', \Illuminate\Support\Str::slug($movie->title)) }}"
                                   class="block relative h-full">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                                    <div class="bg-button aspect-[2/3] overflow-hidden">
                                        <img src="{{ $movie->image ?? '/images/moviemall.jpg' }}"
                                             alt="{{ $movie->title }}"
                                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                    </div>
                                    <div class="flex flex-col gap-1 p-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="min-w-0 truncate font-semibold">{{ $movie->title }}</span>
                                            <span class="shrink-0 text-xs font-bold text-accent">{{ number_format($movie->price, 2) }}€</span>
                                        </div>
                                        <div class="flex gap-2 text-placeholder text-xs">
                                            <span>{{ \Carbon\Carbon::parse($movie->release_date)->year }}</span>
                                            <span
                                                class="truncate max-w-[140px] inline-block">{{ $genresByMovie[$movie->id] ?? '' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-rating">★</span>
                                            <span
                                                class="text-xs text-rating">{{ number_format($movie->rating, 1) }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- paging -->
                    <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm text-placeholder">Page <strong
                                class="text-text">{{ $movies->currentPage() }}</strong>
                            of {{ $movies->lastPage() }}</p>
                        <div class="flex flex-wrap items-center gap-1.5 text-sm">
                            @if ($movies->onFirstPage())
                                <span
                                    class="cursor-not-allowed rounded-lg border border-border bg-button px-3 py-1.5 opacity-40">← Prev</span>
                            @else
                                <a href="{{ $movies->previousPageUrl() }}"
                                   class="rounded-lg border border-border bg-button px-3 py-1.5">← Prev</a>
                            @endif

                            @foreach (range(1, $movies->lastPage()) as $p)
                                @if ($p == $movies->currentPage())
                                    <span
                                        class="rounded-lg border border-accent bg-accent px-3 py-1.5 font-bold">{{ $p }}</span>
                                @elseif ($p <= 3 || $p > $movies->lastPage() - 3 || ($p >= $movies->currentPage() - 1 && $p <= $movies->currentPage() + 1))
                                    <a href="{{ $movies->url($p) }}"
                                       class="rounded-lg border border-border bg-button px-3 py-1.5 transition hover:border-accent hover:bg-accent">{{ $p }}</a>
                                @elseif ($p == 4 || $p == $movies->lastPage() - 3)
                                    <span class="px-1 text-placeholder">…</span>
                                @endif
                            @endforeach

                            @if ($movies->hasMorePages())
                                <a href="{{ $movies->nextPageUrl() }}"
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
