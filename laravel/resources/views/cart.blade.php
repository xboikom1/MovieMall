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

<main x-data="cartInit()" class="mx-auto max-w-7xl px-4 py-8 tablet:px-6 tablet:py-10">
    <div class="flex flex-col gap-8 desktop:flex-row desktop:items-start desktop:gap-8">
        <div class="min-w-0 flex-1 flex flex-col gap-10">
            <template x-if="tickets.length > 0">
                <section>
                    <div class="flex items-center gap-3 mb-5">
                        <h2 class="text-xl font-bold">Tickets</h2>
                        <span class="rounded-full bg-button border border-border px-2.5 py-0.5 text-xs text-placeholder" x-text="tickets.length + ' films'"></span>
                    </div>
                    <div class="flex flex-col gap-4">
                        <template x-for="item in tickets" :key="item.cart_item_id || Math.random()">
                            <div class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden">
                                <div class="flex gap-4 p-4 tablet:p-5">
                                    <a :href="item.url" class="shrink-0 w-16 tablet:w-20 rounded-xl overflow-hidden">
                                        <img :src="item.image" :alt="item.name" class="w-full h-full object-cover aspect-[2/3]" />
                                    </a>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <a :href="item.url" class="block line-clamp-2 font-semibold hover:text-accent transition tablet:text-lg" x-text="item.name"></a>
                                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-placeholder">
                                                    <span x-text="item.genre"></span>
                                                    <span>·</span>
                                                    <span x-text="item.year"></span>
                                                    <span>·</span>
                                                    <span class="text-xs text-rating" x-text="'★ ' + item.rating"></span>
                                                </div>
                                            </div>
                                            <button @click="removeItem(item)" class="text-placeholder hover:text-accent transition leading-none shrink-0 mt-0.5" title="Remove">✕</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-border bg-button/40 px-4 py-4 tablet:px-5 flex flex-col gap-3">
                                    <div class="flex flex-wrap gap-4 text-sm">
                                        <div class="flex items-center gap-2 text-placeholder">
                                            <span x-text="item.schedule"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs text-placeholder font-semibold uppercase tracking-wider">Seats:</span>
                                        <template x-for="seat in item.seats">
                                            <span class="flex items-center gap-1.5 rounded-lg border border-accent/50 bg-accent/10 px-3 py-1 text-xs font-bold text-accent" x-text="seat"></span>
                                        </template>
                                    </div>
                                    <div class="flex items-center justify-between pt-1 border-t border-border">
                                        <span class="text-xs text-placeholder" x-text="item.quantity + ' tickets × ' + item.price + '€'"></span>
                                        <span class="text-sm font-bold text-accent" x-text="(parseFloat(item.subtotal).toFixed(2)) + '€'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </template>

            <template x-if="souvenirs.length > 0">
                <section>
                    <div class="flex items-center gap-3 mb-5">
                        <h2 class="text-xl font-bold">Souvenirs</h2>
                        <span class="rounded-full bg-button border border-border px-2.5 py-0.5 text-xs text-placeholder" x-text="souvenirs.length + ' items'"></span>
                    </div>
                    <div class="flex flex-col gap-4">
                        <template x-for="item in souvenirs" :key="item.cart_item_id || Math.random()">
                            <div class="flex gap-4 rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.35)] tablet:p-5">
                                <a :href="item.url" class="shrink-0 w-20 tablet:w-24 rounded-xl overflow-hidden">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover aspect-square" />
                                </a>
                                <div class="flex flex-1 flex-col gap-3 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <a :href="item.url" class="block line-clamp-2 font-semibold hover:text-accent transition" x-text="item.name"></a>
                                            <p class="truncate text-xs text-placeholder mt-0.5" x-text="item.description"></p>
                                        </div>
                                        <button @click="removeItem(item)" class="text-placeholder hover:text-accent transition leading-none shrink-0 mt-0.5" title="Remove">✕</button>
                                    </div>
                                    <div class="flex items-center justify-between mt-auto">
                                        <div class="flex items-center gap-3">
                                            <button @click="decreaseQty(item)" type="button" :disabled="item.quantity <= 1" :class="{'opacity-50 cursor-not-allowed': item.quantity <= 1}" class="rounded-md border border-border bg-button/30 px-3 py-1 text-sm hover:bg-button">
                                                −
                                            </button>
                                            <div class="min-w-[2rem] text-center font-bold"> <span x-text="item.quantity"></span> </div>
                                            <button @click="increaseQty(item)" type="button" class="rounded-md border border-border bg-button/30 px-3 py-1 text-sm hover:bg-button">
                                                +
                                            </button>
                                        </div>
                                        <span class="text-sm font-bold text-accent" x-text="(parseFloat(item.subtotal).toFixed(2)) + '€'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </template>

            <template x-if="tickets.length === 0 && souvenirs.length === 0">
                <div class="py-12 text-center text-placeholder">
                    Your cart is empty.
                </div>
            </template>

            <div class="flex gap-3">
                <a href="{{ route('movies.index') }}" class="rounded-xl border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent">← Browse Movies</a>
                <a href="{{ route('souvenirs.index') }}" class="rounded-xl border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent">← Browse Souvenirs</a>
            </div>
        </div>

        <template x-if="tickets.length > 0 || souvenirs.length > 0">
            <aside class="w-full desktop:w-80 desktop:shrink-0 desktop:sticky desktop:top-6 h-fit rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden">
                <div class="border-b border-border px-5 py-4">
                    <h2 class="font-bold text-lg">Order Summary</h2>
                </div>
                <div class="px-5 py-4 flex flex-col gap-3">
                    <template x-if="tickets.length > 0">
                        <div>
                            <p class="text-[0.65rem] font-bold uppercase tracking-widest text-placeholder">Tickets</p>
                            <div class="flex flex-col gap-2 text-sm">
                                <template x-for="item in tickets">
                                    <div class="flex justify-between gap-2">
                                        <span class="truncate min-w-0 text-placeholder" x-text="item.name + ' × ' + item.quantity"></span>
                                        <span class="shrink-0" x-text="(parseFloat(item.subtotal).toFixed(2)) + '€'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="tickets.length > 0 && souvenirs.length > 0">
                        <div class="border-t border-border"></div>
                    </template>

                    <template x-if="souvenirs.length > 0">
                        <div>
                            <p class="text-[0.65rem] font-bold uppercase tracking-widest text-placeholder">Souvenirs</p>
                            <div class="flex flex-col gap-2 text-sm">
                                <template x-for="item in souvenirs">
                                    <div class="flex justify-between gap-2">
                                        <span class="truncate min-w-0 text-placeholder" x-text="item.name + ' × ' + item.quantity"></span>
                                        <span class="shrink-0" x-text="(parseFloat(item.subtotal).toFixed(2)) + '€'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div class="border-t border-border"></div>

                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span class="text-accent" x-text="(parseFloat(total).toFixed(2)) + '€'"></span>
                    </div>

                    <a href="{{ route('checkout') }}" class="block w-full rounded-xl bg-accent py-3 text-center text-sm font-semibold transition hover:brightness-110 mt-1">
                        Proceed to Checkout
                    </a>
                </div>
            </aside>
        </template>
    </div>
</main>

<x-layout.footer />

<script>
    function cartInit() {
        return {
            tickets: [],
            souvenirs: [],
            total: 0,
            isOptionsEmpty(opts) {
                return !opts || Object.keys(opts).length === 0;
            },
            async init() {
                if (window.CartService && window.CartService.syncPromise) {
                    await window.CartService.syncPromise;
                }

                let items = window.CartService.getCart();
                try {
                    const response = await axios.post('{{ route('cart.details') }}', { items }, {
                        headers: { 'X-CSRF-TOKEN': window.csrfToken }
                    });

                    const enriched = response.data.items;
                    this.total = response.data.total;
                    this.tickets = enriched.filter(i => i.type === 'ticket');
                    this.souvenirs = enriched.filter(i => i.type === 'souvenir');
                } catch (e) {
                    console.error(e);
                }
            },

                async increaseQty(item) {
                    if (!window.isLoggedIn) {
                        const cart = window.CartService.getCart();
                        const found = cart.find(c =>
                            c.type === 'souvenir' &&
                            String(c.reference_id) === String(item.reference_id) &&
                            (this.isOptionsEmpty(c.options) ? this.isOptionsEmpty(item.options) : JSON.stringify(c.options) === JSON.stringify(item.options))
                        );
                        if (found) {
                            found.quantity = (found.quantity || 0) + 1;
                            localStorage.setItem(window.CartService.cartKey, JSON.stringify(cart));
                        } else {
                            // fallback: add new
                            await window.CartService.add({ type: 'souvenir', reference_id: item.reference_id, quantity: 1, options: item.options });
                        }
                        await this.init();
                        return;
                    }

                    try {
                        await axios.post('{{ route('cart.add') }}', {
                            type: 'souvenir',
                            reference_id: item.reference_id,
                            quantity: 1,
                            options: item.options
                        }, { headers: { 'X-CSRF-TOKEN': window.csrfToken } });
                        await this.init();
                    } catch (e) {
                        console.error(e);
                    }
                },

                async decreaseQty(item) {
                    if (!window.isLoggedIn) {
                        const cart = window.CartService.getCart();
                        const idx = cart.findIndex(c =>
                            c.type === 'souvenir' &&
                            String(c.reference_id) === String(item.reference_id) &&
                            (this.isOptionsEmpty(c.options) ? this.isOptionsEmpty(item.options) : JSON.stringify(c.options) === JSON.stringify(item.options))
                        );
                        if (idx !== -1) {
                            if ((cart[idx].quantity || 1) <= 1) {
                                cart[idx].quantity = 1;
                            } else {
                                cart[idx].quantity = (cart[idx].quantity || 1) - 1;
                            }
                            localStorage.setItem(window.CartService.cartKey, JSON.stringify(cart));
                        }
                        await this.init();
                        return;
                    }

                    try {
                        if (item.quantity > 1) {
                            await axios.post('{{ route('cart.remove') }}', {
                                type: 'souvenir',
                                reference_id: item.reference_id,
                                options: item.options
                            }, {headers: {'X-CSRF-TOKEN': window.csrfToken}});

                            await axios.post('{{ route('cart.add') }}', {
                                type: 'souvenir',
                                reference_id: item.reference_id,
                                quantity: item.quantity - 1,
                                options: item.options
                            }, {headers: {'X-CSRF-TOKEN': window.csrfToken}});
                        }
                        await this.init();
                    } catch (e) {
                        console.error(e);
                    }
                },
            async removeItem(item) {
                await window.CartService.remove(item.type, item.reference_id, item.options);
                if (!window.isLoggedIn) {
                    this.init();
                }
            }
        }
    }
</script>
</body>
</html>
