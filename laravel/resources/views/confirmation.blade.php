<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmation | MovieMall</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-10">
        <div class="flex flex-col items-center gap-8">
            <div class="w-full max-w-2xl rounded-2xl border border-border bg-dark p-6 tablet:p-8 shadow-[0_14px_36px_rgba(0,0,0,.4)] text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-accent/10 border-2 border-accent">
                    <svg class="h-10 w-10 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold tablet:text-4xl">Order Confirmed!</h1>
                <p class="mt-3 text-placeholder">Thank you for your purchase. Your order has been placed successfully.</p>

                <div class="mt-6 rounded-xl border border-border bg-bg px-6 py-4 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-placeholder">Order number</span>
                        <span class="font-semibold tracking-wide">{{ session('order_number', '#MM-20260420-7070') }}</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between gap-4">
                        <span class="text-placeholder">Date</span>
                        <span class="font-semibold">{{ session('order_date', 'April 20, 2026') }}</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between gap-4">
                        <span class="text-placeholder">Payment</span>
                        <span class="font-semibold">{{ session('payment_display', 'Credit Card •••• 4242') }}</span>
                    </div>
                    <div class="mt-3 border-t border-border pt-4 flex items-center justify-between gap-4">
                        <span class="font-bold">Total Paid</span>
                        <span class="font-bold text-accent">{{ session('order_total', '–') }}</span>
                    </div>
                </div>

                <p class="mt-5 text-xs text-placeholder">A confirmation email has been sent to <span class="text-text font-medium">{{ session('order_email', 'email@example.com') }}</span></p>
            </div>

            <div
                x-data="confirmationInit()"
                x-init="init()"
                class="w-full max-w-2xl rounded-2xl border border-border bg-dark p-4 tablet:p-6 shadow-[0_14px_36px_rgba(0,0,0,.4)]"
            >
                <h2 class="mb-4 text-lg font-bold">Items Ordered</h2>

                <template x-if="items.length === 0">
                    <p class="text-sm text-placeholder">No items found in the order.</p>
                </template>

                <ul class="flex flex-col gap-4">
                    <template x-for="itm in items" :key="itm.reference_id">
                        <li>
                            <div class="flex gap-4 rounded-xl border border-border bg-bg p-4 items-center">
                                <div class="h-28 w-20 shrink-0 overflow-hidden rounded-lg border border-border bg-button flex items-center justify-center">
                                    <img
                                        :src="itm.image || itm.poster || itm.thumb || '/images/placeholder.png'"
                                        alt="Poster"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div class="flex flex-1 flex-col justify-between gap-1 py-1">
                                    <div>
                                        <p class="font-semibold" x-text="itm.name || itm.title || itm.name"></p>
                                        <p
                                            class="text-xs text-placeholder"
                                            x-text="itm.genre ? itm.genre + ' • ' + (itm.year || '') : itm.description || ''"
                                        ></p>
                                        <p class="text-xs text-placeholder" x-text="itm.type === 'ticket' ? 'Tickets: ' + (itm.quantity || 1) : ''"></p>
                                    </div>
                                    <p
                                        class="text-sm font-semibold"
                                        x-text="
                                            formatPrice(itm.subtotal || (itm.price && itm.quantity ? itm.price * itm.quantity : itm.price) || itm.line_total)
                                        "
                                    ></p>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>

                <div class="mt-5 rounded-xl border border-border bg-bg p-4 text-sm">
                    <h3 class="mb-3 font-semibold">Delivery Details</h3>
                    <div class="flex flex-col gap-1 text-placeholder">
                        <p><span class="text-text font-medium">Method:</span> <span x-text="meta.delivery_method"></span></p>
                        <p
                            x-show="(meta.delivery_method || '').toLowerCase() !== 'pickup'"
                        ><span class="text-text font-medium">Address:</span> <span x-text="meta.delivery_address"></span></p>
                        <p class="mt-2 text-xs">Estimated delivery: <span class="text-text font-medium">3–5 business days</span></p>
                    </div>
                </div>
            </div>

            <div class="flex w-full max-w-2xl flex-col gap-3 tablet:flex-row">
                <a
                    href="{{ route('home') }}"
                    class="flex flex-1 items-center justify-center rounded-xl bg-accent px-6 py-3 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.4)] transition hover:brightness-110"
                    >Back to Home</a
                >
            </div>
        </div>
    </main>

    <x-layout.footer />

    <script>
        function confirmationInit() {
            const server = {
                order_number: '{{ session('order_number', '#MM-20260420-7070') }}',
                order_date: '{{ session('order_date', 'April 20, 2026') }}',
                payment_display: '{{ session('payment_display', 'Credit Card •••• 4242') }}',
                order_total: '{{ session('order_total', '–') }}',
                order_email: '{{ session('order_email', 'email@example.com') }}',
                delivery_method: '{{ session('delivery_method', 'Courier') }}',
                delivery_address: '{{ session('delivery_address', '123 Main Street, Bratislava, 81101, Slovakia') }}',
                items: {!! json_encode(session('order_items', [])) !!},
                items_json: {!! session('order_items_json') ? session('order_items_json') : 'null' !!}
            };

            return {
                items: [],
                meta: {
                    delivery_method: server.delivery_method,
                    delivery_address: server.delivery_address
                },

                formatPrice(v) {
                    if (v === undefined || v === null) return '–';
                    const n = parseFloat(v);
                    if (isNaN(n)) return v;
                    return n.toFixed(2) + '€';
                },

                async init() {
                    if (server.items && server.items.length > 0) {
                        this.items = server.items;
                        console.log('confirmation: loaded items from session array', this.items);
                        try {
                            if (window.CartService) localStorage.removeItem(window.CartService.cartKey);
                        } catch (e) {
                            console.error('confirmation: failed to clear client cart', e);
                        }
                        return;
                    }

                    if (server.items_json) {
                        try {
                            this.items = server.items_json || [];
                            console.log('confirmation: loaded items from session JSON backup', this.items);
                            try {
                                if (window.CartService) localStorage.removeItem(window.CartService.cartKey);
                            } catch (e) {
                                console.error('confirmation: failed to clear client cart', e);
                            }
                            return;
                        } catch (e) {
                            console.error('confirmation: failed to use items_json', e);
                        }
                    }

                    try {
                        const raw = window.CartService ? window.CartService.getCart() : [];
                        const response = await axios.post('{{ route('cart.details') }}', { items: raw }, { headers: { 'X-CSRF-TOKEN': window.csrfToken } });
                        this.items = response.data.items || [];
                        console.log('confirmation: loaded items from cart.details', this.items);
                    } catch (e) {
                        console.error('Failed to load order items', e);
                        this.items = [];
                    }

                    try {
                        if (window.CartService) localStorage.removeItem(window.CartService.cartKey);
                    } catch (e) {
                        console.error('Failed to clear cart', e);
                    }
                }
            }
    </script>
</body>
</html>
