<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Orders | MovieMall</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6 tablet:py-12">
        <header class="mb-6 flex flex-col gap-2">
            <h1 class="text-2xl font-bold tablet:text-3xl">My Orders</h1>
            <p class="text-sm text-placeholder">Your purchase history.</p>
        </header>

        @if ($orders->isEmpty())
            <div
                class="rounded-2xl border border-border bg-dark p-10 text-center shadow-[0_14px_36px_rgba(0,0,0,.4)]"
            >
                <p class="text-placeholder">You haven't placed any orders yet.</p>
                <a
                    href="{{ route('home') }}"
                    class="mt-4 inline-block rounded-xl bg-accent px-6 py-2.5 text-sm font-semibold transition hover:brightness-110"
                    >Browse Movies & Souvenirs</a
                >
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach ($orders as $order)
                    <div
                        class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.4)] overflow-hidden"
                    >
                        {{-- Order header --}}
                        <div
                            class="flex flex-wrap items-center justify-between gap-3 border-b border-border bg-button/30 px-5 py-4"
                        >
                            <div
                                class="flex flex-wrap items-center gap-4 text-sm"
                            >
                                <span
                                    class="font-semibold tracking-wide"
                                    >{{ $order->order_number }}</span
                                >
                                <span
                                    class="text-placeholder"
                                    >{{ \Carbon\Carbon::parse($order->created_at)->format('M j, Y') }}</span
                                >
                                <span
                                    class="rounded-full bg-green-900/40 px-2.5 py-0.5 text-xs font-semibold text-green-400 border border-green-700/50 capitalize"
                                >
                                    {{ $order->status }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm">
                                <span
                                    class="text-placeholder"
                                    >{{ ucfirst($order->delivery_type ?? 'pickup') }}</span
                                >
                                <span class="font-bold text-accent"
                                    >{{ number_format((float) $order->total_amount, 2) }}€</span
                                >
                            </div>
                        </div>

                        <div class="divide-y divide-border">
                            {{-- Tickets --}}
                            @if ($order->tickets->isNotEmpty())
                                @foreach ($order->tickets->groupBy('title') as $movieTitle => $seats)
                                    <div class="flex gap-4 px-5 py-4">
                                        <div
                                            class="h-20 w-14 shrink-0 overflow-hidden rounded-lg border border-border bg-button"
                                        >
                                            @if ($seats->first()->image)
                                                <img
                                                    src="{{ $seats->first()->image }}"
                                                    alt="{{ $movieTitle }}"
                                                    class="h-full w-full object-cover"
                                                />
                                            @endif
                                        </div>
                                        <div
                                            class="flex flex-1 flex-col justify-center gap-1"
                                        >
                                            <p class="font-semibold">{{ $movieTitle }}</p>
                                            <p class="text-xs text-placeholder">
                                                {{ \Carbon\Carbon::parse($seats->first()->starts_at)->format('D, d M Y · H:i') }}
                                            </p>
                                            <p class="text-xs text-placeholder">
                                                Seats: {{ $seats->map(fn($s) => 'Row '.$s->row_label.'-'.$s->seat_number)->implode(', ') }}
                                            </p>
                                            <p class="text-sm font-semibold">{{ number_format($seats->first()->price * $seats->count(), 2) }}€</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Souvenirs --}}
                            @foreach ($order->souvenirs as $souvenir)
                                <div class="flex gap-4 px-5 py-4">
                                    <div
                                        class="h-20 w-14 shrink-0 overflow-hidden rounded-lg border border-border bg-button"
                                    >
                                        @if ($souvenir->image)
                                            <img
                                                src="{{ $souvenir->image }}"
                                                alt="{{ $souvenir->name }}"
                                                class="h-full w-full object-cover"
                                            />
                                        @endif
                                    </div>
                                    <div
                                        class="flex flex-1 flex-col justify-center gap-1"
                                    >
                                        <p class="font-semibold">{{ $souvenir->name }}</p>
                                        <p class="text-xs text-placeholder">Qty: {{ $souvenir->quantity }}</p>
                                        <p class="text-sm font-semibold">{{ number_format((float) $souvenir->price * $souvenir->quantity, 2) }}€</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <x-layout.footer />
</body>
</html>
