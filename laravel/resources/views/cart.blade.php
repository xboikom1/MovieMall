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

<div class="bg-dark border-b border-border px-6 py-8">
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center gap-2 text-xs mb-2 text-placeholder">
            <a href="{{ route('home') }}" class="hover:text-accent transition">Home</a>
            <span>/</span>
            <span class="text-text">Cart</span>
        </div>
        <h1 class="text-3xl font-bold">Your Cart</h1>
        <p class="text-placeholder text-sm mt-1">Review items before proceeding to checkout.</p>
    </div>
</div>

<main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6 tablet:py-10">
    <div class="flex flex-col gap-8 desktop:flex-row desktop:items-start desktop:gap-8">
        <div class="min-w-0 flex-1 flex flex-col gap-10">

            {{-- Tickets section --}}
            <section>
                <div class="flex items-center gap-3 mb-5">
                    <h2 class="text-xl font-bold">Tickets</h2>
                    <span class="rounded-full bg-button border border-border px-2.5 py-0.5 text-xs text-placeholder">2 films</span>
                </div>

                <div class="flex flex-col gap-4">
                    {{-- Ticket 1 --}}
                    <div class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden">
                        <div class="flex gap-4 p-4 tablet:p-5">
                            <a href="{{ route('movies.show', 'supergrandpa') }}" class="shrink-0 w-16 tablet:w-20 rounded-xl overflow-hidden">
                                <img src="/images/Supergrandpa.png" alt="Supergrandpa" class="w-full h-full object-cover aspect-[2/3]" />
                            </a>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('movies.show', 'supergrandpa') }}" class="block line-clamp-2 font-semibold hover:text-accent transition tablet:text-lg">SuperGrandpa</a>
                                        <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-placeholder">
                                            <span>Action</span>
                                            <span>·</span>
                                            <span>2026</span>
                                            <span>·</span>
                                            <span class="text-xs text-rating">★ 7.5</span>
                                        </div>
                                    </div>
                                    <button class="text-placeholder hover:text-accent transition leading-none shrink-0 mt-0.5" title="Remove">✕</button>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-border bg-button/40 px-4 py-4 tablet:px-5 flex flex-col gap-3">
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center gap-2 text-placeholder">
                                    <span>Sat, 22 Mar 2026</span>
                                    <span>·</span>
                                    <span>18:30</span>
                                    <span>·</span>
                                    <span>Hall A</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-placeholder font-semibold uppercase tracking-wider">Seats:</span>
                                <span class="flex items-center gap-1.5 rounded-lg border border-accent/50 bg-accent/10 px-3 py-1 text-xs font-bold text-accent">
                      Row C · 4
                    </span>
                                <span class="flex items-center gap-1.5 rounded-lg border border-accent/50 bg-accent/10 px-3 py-1 text-xs font-bold text-accent">
                      Row C · 5
                    </span>
                                <a href="{{ route('movies.show', 'supergrandpa') }}" class="ml-auto text-xs text-placeholder hover:text-accent transition underline underline-offset-2">
                                    Change seats
                                </a>
                            </div>

                            <div class="flex items-center justify-between pt-1 border-t border-border">
                                <span class="text-xs text-placeholder">2 tickets × 9.99€</span>
                                <span class="text-sm font-bold text-accent">19.98€</span>
                            </div>
                        </div>
                    </div>

                    {{-- Ticket 2 --}}
                    <div class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden">
                        <div class="flex gap-4 p-4 tablet:p-5">
                            <a href="{{ route('movies.show', 'the-squirrels-revenge') }}" class="shrink-0 w-16 tablet:w-20 rounded-xl overflow-hidden">
                                <img src="/images/Squirrel.png" alt="Squirrel" class="w-full h-full object-cover aspect-[2/3]" />
                            </a>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('movies.show', 'the-squirrels-revenge') }}" class="block line-clamp-2 font-semibold hover:text-accent transition tablet:text-lg">The Squirrel's Revenge</a>
                                        <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-placeholder">
                                            <span>Comedy</span>
                                            <span>·</span>
                                            <span>2020</span>
                                            <span>·</span>
                                            <span class="text-xs text-rating">★ 6.9</span>
                                        </div>
                                    </div>
                                    <button class="text-placeholder hover:text-accent transition leading-none shrink-0 mt-0.5" title="Remove">✕</button>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-border bg-button/40 px-4 py-4 tablet:px-5 flex flex-col gap-3">
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center gap-2 text-placeholder">
                                    <span>Sat, 28 Feb 2026</span>
                                    <span>·</span>
                                    <span>16:30</span>
                                    <span>·</span>
                                    <span>Hall A</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-placeholder font-semibold uppercase tracking-wider">Seats:</span>
                                <span class="flex items-center gap-1.5 rounded-lg border border-accent/50 bg-accent/10 px-3 py-1 text-xs font-bold text-accent">
                      Row A · 6
                    </span>
                                <a href="{{ route('movies.show', 'the-squirrels-revenge') }}" class="ml-auto text-xs text-placeholder hover:text-accent transition underline underline-offset-2">
                                    Change seats
                                </a>
                            </div>

                            <div class="flex items-center justify-between pt-1 border-t border-border">
                                <span class="text-xs text-placeholder">1 ticket × 9.99€</span>
                                <span class="text-sm font-bold text-accent">9.99€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Souvenirs section --}}
            <section>
                <div class="flex items-center gap-3 mb-5">
                    <h2 class="text-xl font-bold">Souvenirs</h2>
                    <span class="rounded-full bg-button border border-border px-2.5 py-0.5 text-xs text-placeholder">2 items</span>
                </div>

                <div class="flex flex-col gap-4">
                    {{-- Souvenir 1 --}}
                    <div class="flex gap-4 rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.35)] tablet:p-5">
                            <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug('Mad Squirrel Figurine')) }}" class="shrink-0 w-20 tablet:w-24 rounded-xl overflow-hidden">
                                    <img src="/images/SuperGrandpaSouvenir.png" alt="Supergrandpa Figure" class="w-full h-full object-cover aspect-square" />
                                </a>
                        <div class="flex flex-1 flex-col gap-3 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug('Mad Squirrel Figurine')) }}" class="block line-clamp-2 font-semibold hover:text-accent transition">Mad Squirrel Figurine</a>
                                    <p class="truncate text-xs text-placeholder mt-0.5">The Squirrel's Revenge · Figurine</p>
                                </div>
                                <button class="text-placeholder hover:text-accent transition leading-none shrink-0 mt-0.5" title="Remove">✕</button>
                            </div>
                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2">
                                    <button class="w-8 h-8 rounded-lg border border-border bg-button text-sm font-bold hover:border-accent transition">−</button>
                                    <span class="text-sm font-semibold w-5 text-center">1</span>
                                    <button class="w-8 h-8 rounded-lg border border-border bg-button text-sm font-bold hover:border-accent transition">+</button>
                                </div>
                                <span class="text-sm font-bold text-accent">9.99€</span>
                            </div>
                        </div>
                    </div>

                    {{-- Souvenir 2 --}}
                    <div class="flex gap-4 rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.35)] tablet:p-5">
                        <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug('Mad Squirrel Sticker Pack')) }}" class="shrink-0 w-20 tablet:w-24 rounded-xl overflow-hidden">
                            <img src="/images/SquirrelSouvenir.png" alt="Squirrel Sticker Pack" class="w-full h-full object-cover aspect-square" />
                        </a>
                        <div class="flex flex-1 flex-col gap-3 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug('Mad Squirrel Sticker Pack')) }}" class="block line-clamp-2 font-semibold hover:text-accent transition">Mad Squirrel Sticker Pack</a>
                                    <p class="truncate text-xs text-placeholder mt-0.5">The Squirrel's Revenge · Sticker Pack</p>
                                </div>
                                <button class="text-placeholder hover:text-accent transition leading-none shrink-0 mt-0.5" title="Remove">✕</button>
                            </div>
                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2">
                                    <button class="w-8 h-8 rounded-lg border border-border bg-button text-sm font-bold hover:border-accent transition">−</button>
                                    <span class="text-sm font-semibold w-5 text-center">2</span>
                                    <button class="w-8 h-8 rounded-lg border border-border bg-button text-sm font-bold hover:border-accent transition">+</button>
                                </div>
                                <span class="text-sm font-bold text-accent">9.99€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Back browsing links --}}
            <div class="flex gap-3">
                <a href="{{ route('movies.index') }}" class="rounded-xl border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent">← Browse Movies</a>
                <a href="{{ route('souvenirs.index') }}" class="rounded-xl border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent">← Browse Souvenirs</a>
            </div>
        </div>

        {{-- Order Summary sidebar --}}
        <aside class="w-full desktop:w-80 desktop:shrink-0 desktop:sticky desktop:top-6 h-fit rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden">
            <div class="border-b border-border px-5 py-4">
                <h2 class="font-bold text-lg">Order Summary</h2>
            </div>

            <div class="px-5 py-4 flex flex-col gap-3">
                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-placeholder">Tickets</p>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="flex justify-between gap-2">
                        <span class="truncate min-w-0 text-placeholder">SuperGrandpa × 2</span>
                        <span class="shrink-0">19.98€</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="truncate min-w-0 text-placeholder">The Squirrel's Revenge × 1</span>
                        <span class="shrink-0">9.99€</span>
                    </div>
                </div>

                <div class="border-t border-border"></div>

                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-placeholder">Souvenirs</p>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="flex justify-between gap-2">
                        <span class="truncate min-w-0 text-placeholder">Mad Squirrel Figurine × 1</span>
                        <span class="shrink-0">9.99€</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="truncate min-w-0 text-placeholder">Mad Squirrel Sticker Pack × 2</span>
                        <span class="shrink-0">9.99€</span>
                    </div>
                </div>

                <div class="border-t border-border"></div>

                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span class="text-accent">49.95€</span>
                </div>

                <a href="{{ route('checkout') }}" class="block w-full rounded-xl bg-accent py-3 text-center text-sm font-semibold transition hover:brightness-110 mt-1">
                    Proceed to Checkout
                </a>
            </div>
        </aside>
    </div>
</main>

<x-layout.footer />
</body>
</html>
