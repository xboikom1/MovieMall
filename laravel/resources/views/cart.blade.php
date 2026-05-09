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

    <div class="bg-dark border-b border-border px-6 py-8">
        <div class="mx-auto max-w-7xl">
            <div class="flex items-center gap-2 text-xs mb-2 text-placeholder">
                <a
                    href="{{ route('home') }}"
                    class="hover:text-accent transition"
                    >Home</a
                >
                <span>/</span>
                <span class="text-text">Cart</span>
            </div>
            <h1 class="text-3xl font-bold">Your Cart</h1>
            <p class="text-placeholder text-sm mt-1">Review items before proceeding to checkout.</p>
        </div>
    </div>

    <main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6 tablet:py-10">
        <div
            class="flex flex-col gap-8 desktop:flex-row desktop:items-start desktop:gap-8"
        >
            {{-- Items column --}}
            <div class="min-w-0 flex-1 flex flex-col gap-10">
                {{-- Tickets --}}
                @if (count($tickets) > 0)
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <h2 class="text-xl font-bold">Tickets</h2>
                            <span
                                class="rounded-full bg-button border border-border px-2.5 py-0.5 text-xs text-placeholder"
                                >{{ count($tickets) }} films</span
                            >
                        </div>
                        <div class="flex flex-col gap-4">
                            @foreach ($tickets as $item)
                                <div
                                    class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden"
                                >
                                    <div class="flex gap-4 p-4 tablet:p-5">
                                        <a
                                            href="{{ $item['url'] }}"
                                            class="shrink-0 w-16 tablet:w-20 rounded-xl overflow-hidden"
                                        >
                                            <img
                                                src="{{ $item['image'] }}"
                                                alt="{{ $item['name'] }}"
                                                class="w-full h-full object-cover aspect-[2/3]"
                                            />
                                        </a>
                                        <div class="flex-1 min-w-0">
                                            <div
                                                class="flex items-start justify-between gap-2"
                                            >
                                                <div class="min-w-0">
                                                    <a
                                                        href="{{ $item['url'] }}"
                                                        class="block line-clamp-2 font-semibold hover:text-accent transition tablet:text-lg"
                                                        >{{ $item['name'] }}</a
                                                    >
                                                    <div
                                                        class="flex flex-wrap items-center gap-2 mt-1 text-xs text-placeholder"
                                                    >
                                                        <span
                                                            >{{ $item['genre'] }}</span
                                                        >
                                                        <span>·</span>
                                                        <span
                                                            >{{ $item['year'] }}</span
                                                        >
                                                        <span>·</span>
                                                        <span
                                                            class="text-xs text-rating"
                                                            >★ {{ $item['rating'] }}</span
                                                        >
                                                    </div>
                                                </div>
                                                <form
                                                    method="POST"
                                                    action="{{ route('cart.item.remove') }}"
                                                    class="shrink-0"
                                                >
                                                    @csrf
                                                    <input
                                                        type="hidden"
                                                        name="type"
                                                        value="{{ $item['type'] }}"
                                                    />
                                                    <input
                                                        type="hidden"
                                                        name="reference_id"
                                                        value="{{ $item['reference_id'] }}"
                                                    />
                                                    <input
                                                        type="hidden"
                                                        name="options"
                                                        value="{{ json_encode($item['options'] ?? []) }}"
                                                    />
                                                    <button
                                                        type="submit"
                                                        class="text-placeholder hover:text-accent transition leading-none mt-0.5"
                                                        title="Remove"
                                                    >
                                                        ✕
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="border-t border-border bg-button/40 px-4 py-4 tablet:px-5 flex flex-col gap-3"
                                    >
                                        <div class="text-sm text-placeholder">
                                            {{ $item['schedule'] }}
                                        </div>

                                        <div
                                            class="grid grid-cols-[1fr_auto] items-center gap-4"
                                        >
                                            <div
                                                class="flex flex-wrap items-center gap-2 min-w-0"
                                            >
                                                <span
                                                    class="text-xs text-placeholder font-semibold uppercase tracking-wider"
                                                    >Seats:</span
                                                >
                                                @foreach ($item['seats'] as $seat)
                                                    <span
                                                        class="flex items-center gap-1.5 rounded-lg border border-accent/50 bg-accent/10 px-3 py-1 text-xs font-bold text-accent"
                                                        >{{ $seat }}</span
                                                    >
                                                @endforeach
                                            </div>
                                            <form
                                                method="POST"
                                                action="{{ route('cart.item.edit-begin') }}"
                                                class="flex-shrink-0"
                                            >
                                                @csrf
                                                <input
                                                    type="hidden"
                                                    name="type"
                                                    value="{{ $item['type'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="reference_id"
                                                    value="{{ $item['reference_id'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="cart_item_id"
                                                    value="{{ $item['cart_item_id'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="options"
                                                    value="{{ json_encode($item['options'] ?? []) }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="quantity"
                                                    value="{{ $item['quantity'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="movie_url"
                                                    value="{{ $item['url'] }}"
                                                />
                                                <button
                                                    type="submit"
                                                    class="text-sm text-placeholder underline hover:text-accent"
                                                >
                                                    Change seats
                                                </button>
                                            </form>
                                        </div>

                                        <div
                                            class="flex items-center justify-between pt-1 border-t border-border"
                                        >
                                            <span
                                                class="text-xs text-placeholder"
                                                >{{ $item['quantity'] }} tickets
                                                × {{ number_format($item['price'], 2) }}€</span
                                            >
                                            <span
                                                class="text-sm font-bold text-accent"
                                                >{{ number_format($item['subtotal'], 2) }}€</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Souvenirs --}}
                @if (count($souvenirs) > 0)
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <h2 class="text-xl font-bold">Souvenirs</h2>
                            <span
                                class="rounded-full bg-button border border-border px-2.5 py-0.5 text-xs text-placeholder"
                                >{{ count($souvenirs) }} items</span
                            >
                        </div>
                        <div class="flex flex-col gap-4">
                            @foreach ($souvenirs as $item)
                                <div
                                    class="flex gap-4 rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.35)] tablet:p-5"
                                >
                                    <a
                                        href="{{ $item['url'] }}"
                                        class="shrink-0 w-20 tablet:w-24 rounded-xl overflow-hidden"
                                    >
                                        <img
                                            src="{{ $item['image'] }}"
                                            alt="{{ $item['name'] }}"
                                            class="w-full h-full object-cover aspect-square"
                                        />
                                    </a>
                                    <div
                                        class="flex flex-1 flex-col gap-3 min-w-0"
                                    >
                                        <div
                                            class="flex items-start justify-between gap-2"
                                        >
                                            <div class="min-w-0">
                                                <a
                                                    href="{{ $item['url'] }}"
                                                    class="block line-clamp-2 font-semibold hover:text-accent transition"
                                                    >{{ $item['name'] }}</a
                                                >
                                                <p class="truncate text-xs text-placeholder mt-0.5">{{ $item['description'] }}</p>
                                            </div>
                                            <form
                                                method="POST"
                                                action="{{ route('cart.item.remove') }}"
                                                class="shrink-0"
                                            >
                                                @csrf
                                                <input
                                                    type="hidden"
                                                    name="type"
                                                    value="{{ $item['type'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="reference_id"
                                                    value="{{ $item['reference_id'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="options"
                                                    value="{{ json_encode($item['options'] ?? []) }}"
                                                />
                                                <button
                                                    type="submit"
                                                    class="text-placeholder hover:text-accent transition leading-none mt-0.5"
                                                    title="Remove"
                                                >
                                                    ✕
                                                </button>
                                            </form>
                                        </div>

                                        <div
                                            class="flex items-center justify-between mt-auto"
                                        >
                                            <form
                                                method="POST"
                                                action="{{ route('cart.item.quantity') }}"
                                            >
                                                @csrf
                                                <input
                                                    type="hidden"
                                                    name="type"
                                                    value="{{ $item['type'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="reference_id"
                                                    value="{{ $item['reference_id'] }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="options"
                                                    value="{{ json_encode($item['options'] ?? []) }}"
                                                />
                                                <div
                                                    class="inline-flex h-9 items-center overflow-hidden rounded-xl border border-border bg-bg"
                                                >
                                                    <button
                                                        type="submit"
                                                        name="quantity"
                                                        value="{{ $item['quantity'] - 1 }}"
                                                        @disabled ($item['quantity'] <= 1)
                                                        class="h-full px-3 text-sm font-semibold text-placeholder transition hover:text-accent disabled:opacity-50 disabled:cursor-not-allowed"
                                                        >−
                                                    </button>
                                                    <span
                                                        class="h-full w-10 flex items-center justify-center text-sm font-bold"
                                                        >{{ $item['quantity'] }}</span
                                                    >
                                                    <button
                                                        type="submit"
                                                        name="quantity"
                                                        value="{{ $item['quantity'] + 1 }}"
                                                        class="h-full px-3 text-sm font-semibold text-placeholder transition hover:text-accent"
                                                    >
                                                        +
                                                    </button>
                                                </div>
                                            </form>
                                            <span
                                                class="text-sm font-bold text-accent"
                                                >{{ number_format($item['subtotal'], 2) }}€</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if (count($tickets) === 0 && count($souvenirs) === 0)
                    <div class="py-12 text-center text-placeholder">
                        Your cart is empty.
                    </div>
                @endif

                <div class="flex gap-3">
                    <a
                        href="{{ route('movies.index') }}"
                        class="rounded-xl border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent"
                        >← Browse Movies</a
                    >
                    <a
                        href="{{ route('souvenirs.index') }}"
                        class="rounded-xl border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent"
                        >← Browse Souvenirs</a
                    >
                </div>
            </div>

            {{-- Order summary sidebar --}}
            @if (count($tickets) > 0 || count($souvenirs) > 0)
                <aside
                    class="w-full desktop:w-80 desktop:shrink-0 desktop:sticky desktop:top-6 h-fit rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden"
                >
                    <div class="border-b border-border px-5 py-4">
                        <h2 class="font-bold text-lg">Order Summary</h2>
                    </div>
                    <div class="px-5 py-4 flex flex-col gap-3">
                        @if (count($tickets) > 0)
                            <div>
                                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-placeholder">Tickets</p>
                                <div class="flex flex-col gap-2 text-sm">
                                    @foreach ($tickets as $item)
                                        <div class="flex justify-between gap-2">
                                            <span
                                                class="truncate min-w-0 text-placeholder"
                                                >{{ $item['name'] }} × {{ $item['quantity'] }}</span
                                            >
                                            <span class="shrink-0"
                                                >{{ number_format($item['subtotal'], 2) }}€</span
                                            >
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (count($tickets) > 0 && count($souvenirs) > 0)
                            <div class="border-t border-border"></div>
                        @endif

                        @if (count($souvenirs) > 0)
                            <div>
                                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-placeholder">Souvenirs</p>
                                <div class="flex flex-col gap-2 text-sm">
                                    @foreach ($souvenirs as $item)
                                        <div class="flex justify-between gap-2">
                                            <span
                                                class="truncate min-w-0 text-placeholder"
                                                >{{ $item['name'] }} × {{ $item['quantity'] }}</span
                                            >
                                            <span class="shrink-0"
                                                >{{ number_format($item['subtotal'], 2) }}€</span
                                            >
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-border"></div>

                        <div class="flex justify-between font-bold">
                            <span>Total</span>
                            <span class="text-accent"
                                >{{ number_format($total, 2) }}€</span
                            >
                        </div>

                        <a
                            href="{{ route('checkout') }}"
                            class="block w-full rounded-xl bg-accent py-3 text-center text-sm font-semibold transition hover:brightness-110 mt-1"
                        >
                            Proceed to Checkout
                        </a>
                    </div>
                </aside>
            @endif
        </div>
    </main>

    <x-layout.footer />
</body>
</html>
