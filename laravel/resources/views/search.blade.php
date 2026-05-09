<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search results for "{{ $q }}" | MovieMall</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <div class="flex-col px-6 py-8 bg-dark border-b border-border">
        <div class="flex items-center gap-2 text-xs mb-2 max-w-7xl mx-auto">
            <a
                href="{{ route('home') }}"
                class="text-placeholder hover:text-accent"
                >Home</a
            >
            <span class="flex items-center gap-2"
                ><span>/</span><span>Search</span></span
            >
        </div>
        <h1 class="max-w-7xl mx-auto text-3xl font-bold">
            @if ($q !== '')
                Results for &ldquo;{{ $q }}&rdquo;
            @else
                Search
            @endif
        </h1>
        @if ($q !== '')
            <p class="max-w-7xl mx-auto text-placeholder text-sm">Found {{ $movies->count() + $souvenirs->count() }} result(s) across movies and souvenirs.</p>
        @endif
    </div>

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-8">
        <!-- search bar -->
        <form method="GET" action="{{ route('search') }}" class="mb-8">
            <div class="flex gap-2 max-w-xl">
                <input
                    type="search"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Search movies, souvenirs..."
                    autofocus
                    class="flex-1 rounded-lg border border-border bg-dark px-4 py-2.5 text-sm text-text placeholder:text-placeholder outline-none focus:border-accent focus:ring-1 focus:ring-accent/30"
                />
                <button
                    type="submit"
                    class="rounded-lg bg-accent px-5 py-2.5 text-sm font-semibold transition hover:brightness-110"
                >
                    Search
                </button>
            </div>
        </form>

        @if ($q === '')
            <p class="text-placeholder">Enter a search term above to find movies and souvenirs.</p>
        @elseif ($movies->isEmpty() && $souvenirs->isEmpty())
            <div
                class="flex flex-col items-center justify-center py-20 text-placeholder"
            >
                <p class="text-lg font-semibold">No results found</p>
                <p class="text-sm mt-1">Try a different search term.</p>
            </div>
        @else
            <!-- movies section -->
            @if ($movies->isNotEmpty())
                <section class="mb-10">
                    <h2 class="text-xl font-bold mb-4">
                        Movies
                        <span class="text-sm font-normal text-placeholder ml-2"
                            >{{ $movies->count() }} result(s)</span
                        >
                    </h2>
                    <ul
                        class="grid grid-cols-2 gap-3 tablet:grid-cols-3 desktop:grid-cols-5"
                    >
                        @foreach ($movies as $movie)
                            <li
                                class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105"
                            >
                                <a
                                    href="{{ route('movies.show', \Illuminate\Support\Str::slug($movie->title)) }}"
                                    class="block relative h-full"
                                >
                                    <div
                                        class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                    ></div>
                                    <div
                                        class="bg-button aspect-[2/3] overflow-hidden"
                                    >
                                        <img
                                            src="{{ $movie->image ?? '/images/moviemall.jpg' }}"
                                            alt="{{ $movie->title }}"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-1 p-3">
                                        <div
                                            class="flex items-center justify-between gap-3"
                                        >
                                            <span
                                                class="min-w-0 truncate font-semibold"
                                                >{{ $movie->title }}</span
                                            >
                                            <span
                                                class="shrink-0 text-xs font-bold text-accent"
                                                >{{ number_format($movie->price, 2) }}€</span
                                            >
                                        </div>
                                        <div
                                            class="flex gap-2 text-placeholder text-xs"
                                        >
                                            <span
                                                >{{ \Carbon\Carbon::parse($movie->release_date)->year }}</span
                                            >
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-rating"
                                                >★</span
                                            >
                                            <span
                                                class="text-xs text-rating"
                                                >{{ number_format($movie->rating, 1) }}</span
                                            >
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
            <!-- souvenirs section -->
            @if ($souvenirs->isNotEmpty())
                <section>
                    <h2 class="text-xl font-bold mb-4">
                        Souvenirs
                        <span class="text-sm font-normal text-placeholder ml-2"
                            >{{ $souvenirs->count() }} result(s)</span
                        >
                    </h2>
                    <ul
                        class="grid grid-cols-2 gap-3 tablet:grid-cols-3 desktop:grid-cols-5"
                    >
                        @foreach ($souvenirs as $souvenir)
                            <li
                                class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105"
                            >
                                <a
                                    href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($souvenir->name)) }}"
                                    class="block relative h-full"
                                >
                                    <div
                                        class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                    ></div>
                                    <div
                                        class="bg-button aspect-square overflow-hidden"
                                    >
                                        <img
                                            src="{{ $souvenir->image ?? '/images/SuperGrandpaSouvenir.png' }}"
                                            alt="{{ $souvenir->name }}"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-1 p-3">
                                        <div
                                            class="flex items-center justify-between gap-3"
                                        >
                                            <span
                                                class="min-w-0 truncate font-semibold"
                                                >{{ $souvenir->name }}</span
                                            >
                                            <span
                                                class="shrink-0 text-xs font-bold text-accent"
                                                >{{ number_format($souvenir->price, 2) }}€</span
                                            >
                                        </div>
                                        <div class="text-placeholder text-xs">
                                            {{ $souvenir->category }}
                                        </div>
                                        @if ($souvenir->movie_title)
                                            <div
                                                class="text-placeholder text-xs truncate"
                                            >
                                                {{ $souvenir->movie_title }}
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

        @endif
    </main>

    <x-layout.footer />
</body>
</html>
