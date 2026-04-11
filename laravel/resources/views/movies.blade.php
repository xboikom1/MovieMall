<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MovieMall</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <!--  poster-->
    <div class="flex-col px-6 py-8 bg-dark border-b border-border">
      <div class="flex items-center gap-2 text-xs mb-2 max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="text-placeholder hover:text-accent">Home</a>
        <p class="flex items-center gap-2">
          <span>/</span>
          <span>Movies</span>
        </p>
      </div>

      <h1 class="max-w-7xl mx-auto text-3xl font-bold">Movies Catalog</h1>
      <p class="max-w-7xl mx-auto text-placeholder text-sm">Browse our full collection of tickets available for purchase.</p>
    </div>

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-8">
      <div class="flex flex-col gap-6 desktop:flex-row desktop:items-start movies-layout">
        <!--  filters-->
        <aside
          class="desktop:flex desktop:sticky desktop:top-4 desktop:w-56 desktop:shrink-0 bg-dark rounded-2xl border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)]"
        >
          <div class="text-text px-4 py-2">
            <div class="flex items-center justify-between mt-4 mb-4">
              <h2 class="text-md font-semibold">Filters</h2>
              <button class="bg-button border border-border px-2 py-1 rounded-lg text-placeholder text-xs hover:text-text">Reset</button>
            </div>

            <h3 class="text-placeholder text-xs font-bold">GENRE</h3>
            <div class="flex flex-col text-sm my-1 gap-1.5">
              @foreach (['Action','Comedy','Drama','Horror','Sci-Fi','Thriller','Fantasy','Romance','Documentary','Apocalypse','Sports','Western'] as $g)
              <label class="flex items-center gap-1">
                <input type="checkbox" class="accent-accent" />
                <span class="cursor-pointer transition hover:text-accent">{{ $g }}</span>
              </label>
              @endforeach
            </div>

            <h3 class="text-placeholder text-xs font-bold mt-4 mb-1">RELEASE YEAR</h3>
            <input type="range" min="1900" max="2026" class="accent-accent w-full" />
            <div class="flex item-center justify-between text-xs mt-1">
              <span class="text-text font-bold">1900</span>
              <span class="text-placeholder"> Year: <strong class="text-text font-bold">2000</strong>+</span>
              <span class="text-text font-bold">2026</span>
            </div>

            <h3 class="text-placeholder text-xs font-bold mt-4 mb-1">RATING</h3>
            <input type="range" min="0" max="10" class="accent-accent w-full" />
            <div class="flex item-center justify-between text-xs mt-1">
              <span class="text-text font-bold">0</span>
              <span class="text-placeholder"> Rating: <strong class="text-text font-bold">5</strong>+</span>
              <span class="text-text font-bold">10</span>
            </div>

            <h3 class="text-placeholder text-xs font-bold mt-4 mb-1">MAX PRICE</h3>
            <input type="range" min="0" max="100" class="accent-accent w-full" />
            <div class="flex item-center justify-between text-xs mt-1">
              <span class="text-text font-bold">0</span>
              <span class="text-placeholder"> Up to: <strong class="text-text font-bold">50</strong>€</span>
              <span class="text-text font-bold">100</span>
            </div>

            <button class="bg-accent rounded-xl w-full px-4 py-1.5 mt-6 mb-1 text-bold">Apply Filters</button>
          </div>
        </aside>

       <section class="min-w-0 flex-1">
          <div class="flex items-center justify-between gap-3">
            <p class="text-sm text-placeholder">Showing <span class="text-text font-bold">{{ $movies->firstItem() ?? 0 }}–{{ $movies->lastItem() ?? 0 }}</span> of <span class="text-text font-bold">{{ $movies->total() }}</span> movies</p>

            <div class="flex items-center gap-2">
              <p class="text-sm text-placeholder">Sort:</p>
              <form method="get" id="sortForm">
                <select name="sort" onchange="document.getElementById('sortForm').submit()" class="bg-button border border-border rounded-lg px-3 py-1 text-sm text-text focus:border-accent outline-none">
                  <option value="most_popular" {{ request('sort')=='most_popular' ? 'selected' : '' }}>Most Popular</option>
                  <option value="highest_rated" {{ request('sort')=='highest_rated' ? 'selected' : '' }}>Highest Rated</option>
                  <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Newest First</option>
                  <option value="price_asc" {{ request('sort')=='price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                  <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
              </form>
            </div>
          </div>

          <ul class="grid grid-cols-2 gap-3 mt-8 tablet:grid-cols-3 desktop:grid-cols-5 movies-grid">
            @foreach ($movies as $movie)
            <li class="group bg-dark rounded-xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-[1.03]">
              <a href="{{ route('movies.show',
                  \Illuminate\Support\Str::slug($movie->title)) }}" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="overflow-hidden" style="aspect-ratio: 2 / 3;">
                  <img src="{{ $movie->image ?? '/images/moviemall.jpg' }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" style="width:100%;height:100%;object-fit:cover;" />
                </div>

                <div class="flex flex-col gap-1 mt-2 p-2">
                  <div class="flex items-center justify-between px-2 gap-3">
                    <span class="truncate text-sm font-semibold">{{ $movie->title }}</span>
                    {{-- price not available on movies table; show rating instead --}}
                    <span class="shrink-0 text-xs font-bold text-accent">{{ number_format($movie->rating, 1) }}★</span>
                  </div>
                  <div class="flex items-center px-2 gap-2">
                    <span class="text-xs text-placeholder">{{ \Carbon\Carbon::parse($movie->release_date)->year }}</span>
                    <span class="truncate text-xs text-placeholder max-w-[140px] inline-block">{{ $genresByMovie[$movie->id] ?? '' }}</span>
                  </div>
                  <div class="flex items-center px-2 gap-1">
                    <span class="text-xs text-rating">★</span>
                    <span class="text-xs text-rating">{{ number_format($movie->rating, 1) }}</span>
                  </div>
                </div>
              </a>
            </li>
            @endforeach
          </ul>

          <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-placeholder">Page <strong class="text-text">{{ $movies->currentPage() }}</strong> of {{ $movies->lastPage() }}</p>
            <div class="flex flex-wrap items-center gap-1.5 text-sm">
              @if ($movies->onFirstPage())
                <span class="cursor-not-allowed rounded-lg border border-border bg-button px-3 py-1.5 opacity-40">← Prev</span>
              @else
                <a href="{{ $movies->previousPageUrl() }}" class="rounded-lg border border-border bg-button px-3 py-1.5">← Prev</a>
              @endif

              @foreach (range(1, $movies->lastPage()) as $p)
                @if ($p == $movies->currentPage())
                  <span class="rounded-lg border border-accent bg-accent px-3 py-1.5 font-bold">{{ $p }}</span>
                @elseif ($p <= 3 || $p > $movies->lastPage() - 3 || ($p >= $movies->currentPage() - 1 && $p <= $movies->currentPage() + 1))
                  <a href="{{ $movies->url($p) }}" class="rounded-lg border border-border bg-button px-3 py-1.5 transition hover:border-accent hover:bg-accent">{{ $p }}</a>
                @elseif ($p == 4 || $p == $movies->lastPage() - 3)
                  <span class="px-1 text-placeholder">…</span>
                @endif
              @endforeach

              @if ($movies->hasMorePages())
                <a href="{{ $movies->nextPageUrl() }}" class="rounded-lg border border-border bg-button px-3 py-1.5">Next →</a>
              @else
                <span class="cursor-not-allowed rounded-lg border border-border bg-button px-3 py-1.5 opacity-40">Next →</span>
              @endif
            </div>
          </div>
        </section>
      </div>
    </main>

    <x-layout.footer />
  </body>
</html>

