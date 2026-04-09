<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $movie['title'] }} | MovieMall</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-10">
      <a href="{{ route('movies.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm text-placeholder transition hover:text-accent">&larr; Back to Movies</a>

      <section class="grid grid-cols-1 gap-8 tablet:grid-cols-2">
        <div class="rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.5)] tablet:p-6">
          <div class="mx-auto overflow-hidden rounded-2xl bg-button aspect-square">
            <img src="{{ $movie['poster'] }}" alt="{{ $movie['title'] }} Poster" class="h-full w-full object-cover" />
          </div>
        </div>

        <div class="flex flex-col gap-6">
          <nav class="flex flex-wrap items-center gap-x-0 text-xs text-placeholder">
            <a href="{{ route('home') }}" class="shrink-0 transition hover:text-accent">Home</a>
            <span class="px-1.5 shrink-0">/</span>
            <a href="{{ route('movies.index') }}" class="shrink-0 transition hover:text-accent">Movies</a>
            <span class="px-1.5 shrink-0">/</span>
            <span class="line-clamp-1 min-w-0 text-text">{{ $movie['title'] }}</span>
          </nav>

          <div class="flex flex-col gap-3">
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
              @foreach ($movie['genres'] as $genre)
                <span class="rounded-full border border-border bg-button px-3 py-1">{{ $genre }}</span>
              @endforeach
            </div>

            <h1 class="text-3xl font-bold tablet:text-4xl break-words">{{ $movie['title'] }}</h1>
            <p class="text-sm italic text-placeholder">{{ $movie['synopsis'] }}</p>

            <div class="flex flex-wrap gap-2 text-sm text-placeholder">
              <span class="flex items-center gap-1.5"><span class="text-yellow-400">*</span>{{ $movie['rating'] }}</span>
              <span>|</span>
              <span class="flex items-center gap-1.5">{{ $movie['duration'] }}</span>
              <span>|</span>
              <span class="flex items-center gap-1.5">{{ $movie['release_date'] }}</span>
            </div>
          </div>
        </div>
      </section>

      <div class="mt-8 grid grid-cols-1 gap-6 tablet:gap-8 desktop:grid-cols-[1fr_380px]">
        <section>
          <div class="order-2 flex flex-col gap-6 desktop:order-1">
            <div class="flex border-b border-border">
              <button class="border-b-2 border-accent px-5 py-3 text-sm font-semibold">Overview</button>
            </div>

            <div class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-6">
              <h2 class="mb-3 text-lg font-bold">Synopsis</h2>
              <p class="text-sm">{{ $movie['synopsis'] }}</p>

              <div class="mt-6 grid grid-cols-2 gap-4 border-t border-border pt-5 text-sm">
                <div>
                  <p class="mb-1 text-placeholder">Director</p>
                  <p class="font-semibold break-words">{{ $movie['director'] }}</p>
                </div>
                <div>
                  <p class="mb-1 text-placeholder">Release Date</p>
                  <p class="font-semibold">{{ $movie['release_date'] }}</p>
                </div>
                <div>
                  <p class="mb-1 text-placeholder">Language</p>
                  <p class="font-semibold break-words">{{ $movie['language'] }}</p>
                </div>
                <div>
                  <p class="mb-1 text-placeholder">Studio</p>
                  <p class="font-semibold break-words">{{ $movie['studio'] }}</p>
                </div>
              </div>
            </div>

            <div>
              <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold tablet:text-xl">Related Souvenirs</h2>
                <a href="{{ route('souvenirs.index') }}" class="text-sm text-placeholder transition hover:text-accent">See More</a>
              </div>
              <ul class="grid grid-cols-2 gap-4 tablet:grid-cols-4">
                @forelse ($movie['related_souvenirs'] as $souvenir)
                  <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
                    <a href="#" class="block relative h-full">
                      <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                      <div class="bg-button aspect-square overflow-hidden">
                        <img src="{{ $souvenir['image'] }}" alt="{{ $souvenir['title'] }} souvenir" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                      </div>
                      <div class="flex flex-col gap-1 p-3">
                        <div class="flex items-center justify-between gap-2">
                          <span class="min-w-0 truncate font-semibold">{{ $souvenir['title'] }}</span>
                          <span class="shrink-0 text-sm font-semibold text-accent">{{ $souvenir['price'] }}</span>
                        </div>
                        <span class="truncate text-xs text-placeholder">{{ $souvenir['movie'] }}</span>
                        <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">{{ $souvenir['type'] }}</span>
                      </div>
                    </a>
                  </li>
                @empty
                  <li class="col-span-2 tablet:col-span-4 rounded-2xl border border-border bg-dark p-4 text-sm text-placeholder">
                    No related souvenirs yet.
                  </li>
                @endforelse
              </ul>
            </div>
          </div>
        </section>

        <aside class="order-1 flex flex-col gap-5 desktop:order-2">
          <div class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-6">
            <h2 class="mb-5 text-lg font-bold">Book Tickets</h2>

            <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-placeholder">1. Select Date</p>
            <div class="mb-5 flex gap-2 text-sm font-semibold">
              <button class="flex-1 rounded-xl border border-accent bg-accent/10 py-2.5 text-accent transition">Mar 6</button>
              <button class="flex-1 rounded-xl border border-border bg-button py-2.5 transition hover:border-accent hover:text-accent">Mar 7</button>
              <button class="flex-1 rounded-xl border border-border bg-button py-2.5 transition hover:border-accent hover:text-accent">Mar 8</button>
            </div>

            <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-placeholder">2. Select Time</p>
            <div class="mb-5 grid grid-cols-2 gap-2 text-sm font-semibold">
              <button class="rounded-xl border border-border bg-button py-2.5 transition hover:border-accent hover:text-accent">14:00</button>
              <button class="rounded-xl border border-border bg-button py-2.5 transition hover:border-accent hover:text-accent">16:30</button>
              <button class="rounded-xl border border-accent bg-accent/10 py-2.5 text-accent transition">19:30</button>
              <button class="rounded-xl border border-border bg-button py-2.5 transition hover:border-accent hover:text-accent">22:00</button>
            </div>

            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-placeholder">3. Select Seats</p>
            <div class="mb-5 rounded-xl border border-border bg-bg p-3">
              <div class="mb-4 rounded-lg bg-button py-1.5 text-center text-xs font-semibold uppercase text-placeholder">Screen</div>

              <div class="flex flex-col items-center gap-2">
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">A</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">B</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">C</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-accent"></div>
                  <div class="h-4 w-5 rounded-sm bg-accent"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">D</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">E</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">F</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">G</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>
                <div class="flex items-center gap-1">
                  <span class="mr-1 w-3 text-right text-[10px] text-placeholder">H</span>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-border"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                  <div class="h-4 w-5 rounded-sm bg-green-500"></div>
                </div>

                <div class="mb-4 mt-3 flex justify-center gap-4 text-xs text-placeholder">
                  <span class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-green-500"></span>Available</span>
                  <span class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-accent"></span>Selected</span>
                  <span class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-border"></span>Occupied</span>
                </div>
              </div>

              <div class="flex flex-col gap-2 border-t border-border pt-4 text-sm">
                <div class="flex justify-between">
                  <span class="text-placeholder">Seats (2)</span>
                  <span class="font-medium">C7, C8</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-placeholder">Price per ticket</span>
                  <span class="font-medium">9.99EUR</span>
                </div>
                <div class="mt-1 flex justify-between font-bold">
                  <span>Total</span>
                  <span class="text-accent">19.98EUR</span>
                </div>
              </div>

              <a href="#" class="mt-5 flex w-full justify-center rounded-xl bg-accent px-6 py-3.5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:text-lg">
                Add to cart
              </a>
            </div>
          </div>
        </aside>
      </div>
    </main>

    <x-layout.footer />
  </body>
</html>

