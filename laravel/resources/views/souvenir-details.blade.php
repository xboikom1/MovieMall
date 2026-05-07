<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $souvenir['title'] }} | MovieMall</title>

    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-10">
        <a href="{{ route('souvenirs.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm text-placeholder transition hover:text-accent"
            >&larr; Back to Souvenirs</a
        >

        <section class="grid grid-cols-1 gap-8 tablet:grid-cols-2">
            <div
                class="rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.5)] tablet:p-6"
                x-data="{ activeImage: 0, images: {{ json_encode($souvenir['images']) }} }"
            >
                <div class="aspect-square overflow-hidden rounded-2xl bg-button w-full relative">
                    <template x-for="(image, index) in images" :key="index">
                        <img
                            x-show="activeImage === index"
                            :src="image"
                            alt="{{ $souvenir['title'] }} image"
                            class="absolute inset-0 h-full w-full object-cover"
                        />
                    </template>

                    <button
                        x-show="images.length > 1"
                        @click="activeImage = activeImage === 0 ? images.length - 1 : activeImage - 1"
                        class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/75"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    </button>
                    <button
                        x-show="images.length > 1"
                        @click="activeImage = activeImage === images.length - 1 ? 0 : activeImage + 1"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/75"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <nav class="text-xs text-placeholder">
                    <a href="{{ route('home') }}" class="transition hover:text-accent">Home</a>
                    <span class="px-1.5">/</span>
                    <a href="{{ route('souvenirs.index') }}" class="transition hover:text-accent">Souvenirs</a>
                    <span class="px-1.5">/</span>
                    <span class="text-text">{{ $souvenir['title'] }}</span>
                </nav>

                <div>
                    <h1 class="text-3xl font-bold tablet:text-4xl break-words">{{ $souvenir['title'] }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        <p class="text-2xl font-bold">{{ $souvenir['price'] }}</p>
                        <span
                            class="rounded-full border border-border bg-button px-3 py-1 text-xs font-semibold text-placeholder"
                            >{{ $souvenir['in_stock'] ? 'In Stock' : 'Out of Stock' }}</span
                        >
                    </div>
                </div>

                <div class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.40)] tablet:p-6">
                    <h2 class="text-xl font-bold tablet:text-2xl">Description</h2>

                    <div class="mt-5 grid grid-cols-1 gap-6 tablet:grid-cols-2">
                        <div>
                            <p class="mb-1 text-xs uppercase tracking-widest text-placeholder">Category</p>
                            <p class="text-sm font-semibold break-words">{{ $souvenir['category'] }}</p>
                        </div>
                        <div>
                            <p class="mb-1 text-xs uppercase tracking-widest text-placeholder">Movie Tie-in</p>
                            <p class="text-sm font-semibold line-clamp-2 break-words">{{ $souvenir['movie'] }}</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-border pt-6">
                        <form method="POST" action="{{ route('cart.item.add') }}">
                        @csrf
                        <input type="hidden" name="type" value="souvenir">
                        <input type="hidden" name="reference_id" value="{{ $souvenir['id'] }}">
                        <input type="hidden" name="options" value="[]">
                        <div class="flex flex-col gap-3 tablet:flex-row tablet:items-center tablet:justify-between">
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-placeholder">Quantity</p>
                                <div class="inline-flex h-10 items-center overflow-hidden rounded-xl border border-border bg-bg">
                                    <label for="quantityInput" class="sr-only">Quantity</label>
                                    <input
                                        id="quantityInput"
                                        name="quantity"
                                        type="number"
                                        min="1"
                                        value="1"
                                        class="h-full w-20 appearance-none border-none bg-transparent p-0 px-3 text-center text-sm outline-none text-text focus:ring-0"
                                        aria-label="Quantity"
                                    />
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-accent px-6 py-3.5 text-xl font-semibold shadow-[0_14px_36px_rgba(0,0,0,.40)] transition hover:brightness-110 tablet:mt-0 tablet:w-[260px]"
                            >
                                Add to Cart
                            </button>
                        </div>
                        </form>

                        <div class="mt-5 rounded-xl border border-border bg-bg p-4">
                            <div class="text-sm">
                                <p class="font-semibold">Standard Delivery</p>
                                <p class="mt-1 text-xs text-placeholder">Estimated delivery 3–5 business days. Free shipping over 50€.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm text-placeholder">{{ $souvenir['description'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-12">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold tablet:text-xl">Other Souvenirs</h2>
                <a href="{{ route('souvenirs.index') }}" class="text-sm text-placeholder transition hover:text-accent">See More</a>
            </div>

            <ul class="grid grid-cols-2 gap-4 tablet:grid-cols-3 tablet:gap-5 desktop:grid-cols-5">
                @foreach (array_slice($souvenir['related_souvenirs'], 0, 5) as $s)
                    <li
                        class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105"
                    >
                        <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($s['title'])) }}" class="block relative h-full">
                            <div
                                class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                            ></div>
                            <div class="bg-button aspect-square overflow-hidden">
                                <img
                                    src="{{ $s['image'] }}"
                                    alt="{{ $s['title'] }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                />
                            </div>
                            <div class="flex flex-col gap-1 p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="min-w-0 truncate font-semibold">{{ $s['title'] }}</span>
                                    <span class="shrink-0 text-sm font-semibold text-accent">{{ $s['price'] }}</span>
                                </div>
                                <span class="truncate text-xs text-placeholder">{{ $s['movie'] }}</span>
                                <span
                                    class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder"
                                    >{{ $s['type'] }}</span
                                >
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    </main>

    <x-layout.footer />

</body>
</html>
