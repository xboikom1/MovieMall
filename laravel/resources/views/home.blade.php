<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MovieMall</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main>
        {{-- Banner --}}
        <section
            class="bg-dark px-4 py-14 tablet:px-6 tablet:py-20 desktop:py-28 border-b border-border"
        >
            <div class="flex mx-auto max-w-7xl items-center gap-10">
                <div class="flex flex-col flex-1 gap-6">
                    <h1
                        class="text-3xl tablet:text-4xl desktop:text-5xl font-bold leading-tight"
                    >
                        Your Cinema,
                        <br />Your rules
                    </h1>
                    <p class="max-w-md text-placeholder">Browse thousands of movies, collect souvenirs, and never miss a release — all in one place.</p>

                    <div class="flex gap-3 flex-wrap">
                        <a
                            href="{{ route('movies.index') }}"
                            class="bg-accent rounded-xl px-6 py-3 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.4)] transition hover:brightness-110"
                            >Browse Movies</a
                        >
                        <a
                            href="{{ route('souvenirs.index') }}"
                            class="bg-button rounded-xl px-6 py-3 font-semibold border border-border transition hover:bg-accent"
                            >Browse Souvenirs</a
                        >
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
        <section
            class="flex flex-col mx-auto max-w-7xl gap-12 px-4 py-12 tablet:px-6 tablet:py-16"
        >
            {{-- Top Rated --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl tablet:text-2xl font-bold">Top Rated</h2>
                    <a
                        href="{{ route('movies.index') }}"
                        class="text-accent text-sm font-semibold transition hover:brightness-125"
                        >See More</a
                    >
                </div>

                <ul
                    class="grid grid-cols-2 gap-4 gap-y-7 tablet:grid-cols-3 tablet:gap-5 tablet:gap-y-8 desktop:grid-cols-5"
                >
                    @foreach ($topMovies as $movie)
                        <li
                            class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-[1.03]"
                        >
                            <a
                                href="{{ route('movies.show', $movie['slug']) }}"
                                class="block relative h-full"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                ></div>
                                <div
                                    class="relative bg-button aspect-[2/3] overflow-hidden"
                                >
                                    <img
                                        src="{{ $movie['image'] }}"
                                        alt="{{ $movie['title'] }}"
                                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                    />
                                </div>
                                <div class="flex flex-col gap-1 p-3">
                                    <span
                                        class="min-w-0 truncate font-semibold"
                                        >{{ $movie['title'] }}</span
                                    >
                                    <div
                                        class="flex gap-2 text-placeholder text-xs"
                                    >
                                        <span>{{ $movie['year'] }}</span>
                                        <span
                                            class="truncate max-w-[160px] inline-block"
                                            >{{ $movie['genres'] }}</span
                                        >
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-rating"
                                            >★</span
                                        >
                                        <span
                                            class="text-xs text-rating"
                                            >{{ $movie['rating'] }}</span
                                        >
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
                    <h2 class="text-xl tablet:text-2xl font-bold">
                        New Releases
                    </h2>
                    <a
                        href="{{ route('movies.index') }}"
                        class="text-accent text-sm font-semibold transition hover:brightness-125"
                        >See More</a
                    >
                </div>

                <ul
                    class="grid grid-cols-2 gap-4 gap-y-7 tablet:grid-cols-3 tablet:gap-5 tablet:gap-y-8 desktop:grid-cols-5"
                >
                    @foreach ($newReleases as $movie)
                        <li
                            class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-[1.03]"
                        >
                            <a
                                href="{{ route('movies.show', $movie['slug']) }}"
                                class="block relative h-full"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                ></div>
                                <div
                                    class="relative bg-button aspect-[2/3] overflow-hidden"
                                >
                                    <img
                                        src="{{ $movie['image'] }}"
                                        alt="{{ $movie['title'] }}"
                                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                    />
                                </div>
                                <div class="flex flex-col gap-1 p-3">
                                    <span
                                        class="min-w-0 truncate font-semibold"
                                        >{{ $movie['title'] }}</span
                                    >
                                    <div
                                        class="flex gap-2 text-placeholder text-xs"
                                    >
                                        <span>{{ $movie['year'] }}</span>
                                        <span
                                            class="truncate max-w-[160px] inline-block"
                                            >{{ $movie['genres'] }}</span
                                        >
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-rating"
                                            >★</span
                                        >
                                        <span
                                            class="text-xs text-rating"
                                            >{{ $movie['rating'] }}</span
                                        >
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Newest Arrivals --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl tablet:text-2xl font-bold">
                        Newest Arrivals
                    </h2>
                    <a
                        href="{{ route('souvenirs.index') }}"
                        class="text-accent text-sm font-semibold transition hover:brightness-125"
                        >See More</a
                    >
                </div>

                <ul
                    class="grid grid-cols-2 gap-4 gap-y-7 tablet:grid-cols-3 tablet:gap-5 tablet:gap-y-8 desktop:grid-cols-5"
                >
                    @foreach ($newestSouvenirs as $s)
                        <li
                            class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-[1.03]"
                        >
                            <a
                                href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($s->name)) }}"
                                class="block relative h-full"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                ></div>
                                <div
                                    class="bg-button aspect-square overflow-hidden"
                                >
                                    <img
                                        src="{{ $s->image ?? '/images/SuperGrandpaSouvenir.png' }}"
                                        alt="{{ $s->name }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                    />
                                </div>
                                <div class="flex flex-col gap-1 p-3">
                                    <div
                                        class="flex items-center justify-between gap-2"
                                    >
                                        <span
                                            class="min-w-0 truncate font-semibold"
                                            >{{ $s->name }}</span
                                        >
                                        <span
                                            class="shrink-0 text-sm font-semibold text-accent"
                                            >{{ number_format($s->price, 2) }}€</span
                                        >
                                    </div>
                                    <span
                                        class="truncate text-xs text-placeholder"
                                        >{{ $s->franchise_name ?? '—' }}</span
                                    >
                                    <span
                                        class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder"
                                        >{{ $s->category ?? '—' }}</span
                                    >
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Best Value --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl tablet:text-2xl font-bold">
                        Best Value
                    </h2>
                    <a
                        href="{{ route('souvenirs.index', ['sort' => 'price_asc']) }}"
                        class="text-accent text-sm font-semibold transition hover:brightness-125"
                        >See More</a
                    >
                </div>

                <ul
                    class="grid grid-cols-2 gap-4 gap-y-7 tablet:grid-cols-3 tablet:gap-5 tablet:gap-y-8 desktop:grid-cols-5"
                >
                    @foreach ($bestValueSouvenirs as $s)
                        <li
                            class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-[1.03]"
                        >
                            <a
                                href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($s->name)) }}"
                                class="block relative h-full"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                ></div>
                                <div
                                    class="bg-button aspect-square overflow-hidden"
                                >
                                    <img
                                        src="{{ $s->image ?? '/images/SuperGrandpaSouvenir.png' }}"
                                        alt="{{ $s->name }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                    />
                                </div>
                                <div class="flex flex-col gap-1 p-3">
                                    <div
                                        class="flex items-center justify-between gap-2"
                                    >
                                        <span
                                            class="min-w-0 truncate font-semibold"
                                            >{{ $s->name }}</span
                                        >
                                        <span
                                            class="shrink-0 text-sm font-semibold text-accent"
                                            >{{ number_format($s->price, 2) }}€</span
                                        >
                                    </div>
                                    <span
                                        class="truncate text-xs text-placeholder"
                                        >{{ $s->franchise_name ?? '—' }}</span
                                    >
                                    <span
                                        class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder"
                                        >{{ $s->category ?? '—' }}</span
                                    >
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{--       Browse By Genre --}}
        <section
            class="bg-dark px-4 py-12 tablet:px-6 tablet:py-16 border-t border-border"
        >
            <div class="mx-auto max-w-7xl">
                <h2 class="text-xl tablet:text-2xl font-bold mb-6">
                    Browse By Genre
                </h2>

                <ul
                    class="grid auto-rows-[120px] grid-cols-2 gap-3 tablet:auto-rows-[140px] tablet:grid-cols-4"
                >
                    <li class="tablet:col-span-2 tablet:row-span-2">
                        <a
                            href="{{ route('movies.index', isset($genresByName['action']) ? ['genres[]' => $genresByName['action']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 tablet:p-5 text-lg tablet:text-xl font-bold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/action.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Action</span
                            >
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('movies.index', isset($genresByName['comedy']) ? ['genres[]' => $genresByName['comedy']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/comedy.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Comedy</span
                            >
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('movies.index', isset($genresByName['drama']) ? ['genres[]' => $genresByName['drama']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/drama.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Drama</span
                            >
                        </a>
                    </li>

                    <li class="tablet:col-span-2">
                        <a
                            href="{{ route('movies.index', isset($genresByName['sci-fi']) ? ['genres[]' => $genresByName['sci-fi']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/scifi.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Sci-Fi</span
                            >
                        </a>
                    </li>

                    <li class="tablet:col-span-2">
                        <a
                            href="{{ route('movies.index', isset($genresByName['thriller']) ? ['genres[]' => $genresByName['thriller']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/thrillerr.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Thriller</span
                            >
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('movies.index', isset($genresByName['fantasy']) ? ['genres[]' => $genresByName['fantasy']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/fantasy.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Fantasy</span
                            >
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('movies.index', isset($genresByName['horror']) ? ['genres[]' => $genresByName['horror']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/horror.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Horror</span
                            >
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('movies.index', isset($genresByName['romance']) ? ['genres[]' => $genresByName['romance']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/romance.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Romance</span
                            >
                        </a>
                    </li>

                    <li class="tablet:col-span-2">
                        <a
                            href="{{ route('movies.index', isset($genresByName['documentary']) ? ['genres[]' => $genresByName['documentary']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/documentary.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Documentary</span
                            >
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('movies.index', isset($genresByName['apocalypse']) ? ['genres[]' => $genresByName['apocalypse']->id] : []) }}"
                            class="group relative flex rounded-2xl h-full items-end overflow-hidden p-4 font-semibold border border-border transition-colors duration-300 hover:border-accent"
                        >
                            <img
                                src="/images/genres/apocalypse.jpg"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06]"
                                alt=""
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-accent/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                            <span class="relative z-10 truncate w-full block"
                                >Apocalypse</span
                            >
                        </a>
                    </li>
                </ul>
            </div>
        </section>
    </main>

    <x-layout.footer />
</body>
</html>
