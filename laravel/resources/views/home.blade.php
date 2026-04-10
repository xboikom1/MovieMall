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

    <main>
      {{-- Banner --}}
      <section class="bg-dark px-4 py-14 tablet:px-6 tablet:py-20 desktop:py-28 border-b border-border">
        <div class="flex mx-auto max-w-7xl items-center gap-10">
          <div class="flex flex-col flex-1 gap-6">
            <h1 class="text-3xl tablet:text-4xl desktop:text-5xl font-bold leading-tight">
              Your Cinema,
              <br />Your rules
            </h1>
            <p class="max-w-md text-placeholder">Browse thousands of movies, collect souvenirs, and never miss a release — all in one place.</p>

            <div class="flex gap-3 flex-wrap">
              <a href="{{ route('movies.index') }}" class="bg-accent rounded-xl px-6 py-3 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.4)] transition hover:brightness-110">Browse Movies</a>
              <a href="{{ route('souvenirs.index') }}" class="bg-button rounded-xl px-6 py-3 font-semibold border border-border transition hover:bg-accent">Browse Souvenirs</a>
            </div>
          </div>

          <div class="flex-shrink-0 hidden tablet:block">
            <img
              src="/images/moviemall.jpg"
              alt="Movie Poster"
              class="bg-button rounded-2xl w-52 aspect-[2/3] object-cover shadow-[0_24px_48px_rgba(0,0,0,.6)] ring-1 ring-border"
            />
          </div>
        </div>
      </section>

      {{-- Recommendation grid --}}
      <section class="flex flex-col mx-auto max-w-7xl gap-12 px-4 py-12 tablet:px-6 tablet:py-16">

        {{-- Top Movies --}}
        <div>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl tablet:text-2xl font-bold">Top Movies</h2>
            <a href="{{ route('movies.index') }}" class="text-accent text-sm font-semibold transition hover:brightness-125">See More</a>
          </div>

          <ul class="grid grid-cols-2 gap-4 tablet:grid-cols-3 tablet:gap-5 desktop:grid-cols-5">
            @foreach ($topMovies as $movie)
            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="{{ route('movies.show', $movie['slug']) }}" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-[2/3] overflow-hidden">
                  <img src="{{ $movie['image'] }}" alt="{{ $movie['title'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <span class="min-w-0 truncate font-semibold">{{ $movie['title'] }}</span>
                  <div class="flex gap-2 text-placeholder text-xs">
                    <span>{{ $movie['year'] }}</span>
                    <span class="truncate max-w-[160px] inline-block">{{ $movie['genres'] }}</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <span class="text-xs text-rating">★</span>
                    <span class="text-xs text-rating">{{ $movie['rating'] }}</span>
                  </div>
                </div>
              </a>
            </li>
            @endforeach
          </ul>
        </div>

        {{-- New Releases --}}
        <div>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl tablet:text-2xl font-bold">New Releases</h2>
            <a href="{{ route('movies.index') }}" class="text-accent text-sm font-semibold transition hover:brightness-125">See More</a>
          </div>

          <ul class="grid grid-cols-2 gap-4 tablet:grid-cols-3 tablet:gap-5 desktop:grid-cols-5">
            @foreach ($newReleases as $movie)
            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="{{ route('movies.show', $movie['slug']) }}" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-[2/3] overflow-hidden">
                  <img src="{{ $movie['image'] }}" alt="{{ $movie['title'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <span class="min-w-0 truncate font-semibold">{{ $movie['title'] }}</span>
                  <div class="flex gap-2 text-placeholder text-xs">
                    <span>{{ $movie['year'] }}</span>
                    <span class="truncate max-w-[160px] inline-block">{{ $movie['genres'] }}</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <span class="text-xs text-rating">★</span>
                    <span class="text-xs text-rating">{{ $movie['rating'] }}</span>
                  </div>
                </div>
              </a>
            </li>
            @endforeach
          </ul>
        </div>

        {{-- Best Rated Souvenirs --}}
        <div>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl tablet:text-2xl font-bold">Best Rated Souvenirs</h2>
            <a href="{{ route('souvenirs.index') }}" class="text-accent text-sm font-semibold transition hover:brightness-125">See More</a>
          </div>

          <ul class="grid grid-cols-2 gap-4 tablet:grid-cols-3 tablet:gap-5 desktop:grid-cols-5">
            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="#" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-square overflow-hidden">
                  <img src="/images/plushtoynemo.png" alt="Souvenir image" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <span class="min-w-0 truncate font-semibold">Hiding Nemo Plush Toy</span>
                    <span class="shrink-0 text-sm font-semibold text-accent">9.99€</span>
                  </div>
                  <span class="truncate text-xs text-placeholder">Hiding Nemo</span>
                  <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">Plush Toy</span>
                </div>
              </a>
            </li>

            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="#" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-square overflow-hidden">
                  <img src="/images/bluebulkpropreplica.png" alt="Souvenir image" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <span class="min-w-0 truncate font-semibold">Blue Bulk Prop Replica</span>
                    <span class="shrink-0 text-sm font-semibold text-accent">9.99€</span>
                  </div>
                  <span class="truncate text-xs text-placeholder">The Ordinary Blue Bulk</span>
                  <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">Prop Replica</span>
                </div>
              </a>
            </li>

            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="#" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-square overflow-hidden">
                  <img src="/images/gollumring.png" alt="Souvenir image" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <span class="min-w-0 truncate font-semibold">Gollum's Ring</span>
                    <span class="shrink-0 text-sm font-semibold text-accent">9.99€</span>
                  </div>
                  <span class="truncate text-xs text-placeholder">Gollum: Steal The Ring</span>
                  <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">Accessory</span>
                </div>
              </a>
            </li>

            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="#" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-square overflow-hidden">
                  <img src="/images/missionpossibleprint.png" alt="Souvenir image" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <span class="min-w-0 truncate font-semibold">Mission: Possible Print</span>
                    <span class="shrink-0 text-sm font-semibold text-accent">9.99€</span>
                  </div>
                  <span class="truncate text-xs text-placeholder">Mission: Possible</span>
                  <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">Print</span>
                </div>
              </a>
            </li>

            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="#" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-square overflow-hidden">
                  <img src="/images/SuperGrandpaSouvenir.png" alt="Souvenir image" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <span class="min-w-0 truncate font-semibold">SuperGrandpa Figurine</span>
                    <span class="shrink-0 text-sm font-semibold text-accent">9.99€</span>
                  </div>
                  <span class="truncate text-xs text-placeholder">SuperGrandpa</span>
                  <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">Figurine</span>
                </div>
              </a>
            </li>
          </ul>
        </div>

      </section>

      {{-- Browse By Genre --}}
      <section class="bg-dark px-4 py-12 tablet:px-6 tablet:py-16 border-t border-border">
        <div class="mx-auto max-w-7xl">
          <h2 class="text-xl tablet:text-2xl font-bold mb-6">Browse By Genre</h2>

          <ul class="grid auto-rows-[120px] grid-cols-2 gap-3 tablet:auto-rows-[140px] tablet:grid-cols-4">
            <li class="tablet:col-span-2 tablet:row-span-2">
              <a href="{{ route('movies.index') }}" class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 tablet:p-5 text-lg tablet:text-xl font-bold border border-border transition-colors duration-300 hover:border-accent">
                <img src="/images/action.jpg" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                <span class="relative z-10 truncate w-full block">Action</span>
              </a>
            </li>

            <li>
              <a href="{{ route('movies.index') }}" class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent">
                <img src="/images/comedy.jpg" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                <span class="relative z-10 truncate w-full block">Comedy</span>
              </a>
            </li>

            <li>
              <a href="{{ route('movies.index') }}" class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent">
                <img src="/images/drama.jpg" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                <span class="relative z-10 truncate w-full block">Drama</span>
              </a>
            </li>

            <li class="tablet:col-span-2">
              <a href="{{ route('movies.index') }}" class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent">
                <img src="/images/adventure.jpg" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                <span class="relative z-10 truncate w-full block">Adventure</span>
              </a>
            </li>
          </ul>
        </div>
      </section>
    </main>

    <x-layout.footer />
  </body>
</html>
