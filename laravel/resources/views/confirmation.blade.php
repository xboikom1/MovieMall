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
            <div
                class="w-full max-w-2xl rounded-2xl border border-border bg-dark p-6 tablet:p-8 shadow-[0_14px_36px_rgba(0,0,0,.4)] text-center"
            >
                <div
                    class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-accent/10 border-2 border-accent"
                >
                    <svg class="h-10 w-10 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold tablet:text-4xl">
                    Order Confirmed!
                </h1>
                <p class="mt-3 text-placeholder">Thank you for your purchase. Your order has been placed successfully.</p>

                <div
                    class="mt-6 rounded-xl border border-border bg-bg px-6 py-4 text-sm"
                >
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-placeholder">Order number</span>
                        <span
                            class="font-semibold tracking-wide"
                            >{{ session('order_number') }}</span
                        >
                    </div>
                    <div class="mt-2 flex items-center justify-between gap-4">
                        <span class="text-placeholder">Date</span>
                        <span
                            class="font-semibold"
                            >{{ session('order_date') }}</span
                        >
                    </div>
                    <div class="mt-2 flex items-center justify-between gap-4">
                        <span class="text-placeholder">Payment</span>
                        <span
                            class="font-semibold"
                            >{{ session('payment_display') }}</span
                        >
                    </div>
                    <div
                        class="mt-3 border-t border-border pt-4 flex items-center justify-between gap-4"
                    >
                        <span class="font-bold">Total Paid</span>
                        <span
                            class="font-bold text-accent"
                            >{{ session('order_total') }}</span
                        >
                    </div>
                </div>

                <p class="mt-5 text-xs text-placeholder">Order placed for <span class="text-text font-medium">{{ session('order_email') }}</span></p>
            </div>

            <div
                class="w-full max-w-2xl rounded-2xl border border-border bg-dark p-4 tablet:p-6 shadow-[0_14px_36px_rgba(0,0,0,.4)]"
            >
                <h2 class="mb-4 text-lg font-bold">Items Ordered</h2>

                @php $orderItems = session('order_items', []); @endphp

                @if (count($orderItems) === 0)
                    <p class="text-sm text-placeholder">No items found in the order.</p>
                @else
                    <ul class="flex flex-col gap-4">
                        @foreach ($orderItems as $itm)
                            <li>
                                <div
                                    class="flex gap-4 rounded-xl border border-border bg-bg p-4 items-center"
                                >
                                    <div
                                        class="h-28 w-20 shrink-0 overflow-hidden rounded-lg border border-border bg-button flex items-center justify-center"
                                    >
                                        <img
                                            src="{{ $itm['image'] ?? '/images/placeholder.png' }}"
                                            alt="{{ $itm['name'] ?? '' }}"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div
                                        class="flex flex-1 flex-col justify-between gap-1 py-1"
                                    >
                                        <div>
                                            <p class="font-semibold">{{ $itm['name'] ?? '' }}</p>
                                            <p class="text-xs text-placeholder">
                                                @if (!empty($itm['genre']))
                                                    {{ $itm['genre'] }}{{ !empty($itm['year']) ? ' · ' . $itm['year'] : '' }}
                                                @else
                                                    {{ $itm['description'] ?? '' }}
                                                @endif
                                            </p>
                                            @if (($itm['type'] ?? '') === 'ticket')
                                                <p class="text-xs text-placeholder">Tickets: {{ $itm['quantity'] ?? 1 }}</p>
                                            @endif
                                        </div>
                                        <p class="text-sm font-semibold">
                                            @php
                                            $subtotal = $itm['subtotal'] ?? ($itm['price'] ?? 0) * ($itm['quantity'] ?? 1);
                                        @endphp
                                            {{ number_format($subtotal, 2) }}€
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div
                    class="mt-5 rounded-xl border border-border bg-bg p-4 text-sm"
                >
                    <h3 class="mb-3 font-semibold">Delivery Details</h3>
                    <div class="flex flex-col gap-1 text-placeholder">
                        <p><span class="text-text font-medium">Method:</span> {{ session('delivery_method') }}</p>
                        @if (strtolower(session('delivery_method', '')) !== 'pickup')
                            <p><span class="text-text font-medium">Address:</span> {{ session('delivery_address') }}</p>
                        @endif
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
</body>
</html>
