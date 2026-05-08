<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders — Admin | MovieMall</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-text">
    <header class="border-b border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)]">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 tablet:px-6">
            <div class="flex items-center gap-3 tablet:gap-5">
                <a href="{{ route('home') }}" class="flex items-center rounded-xl bg-accent px-4 py-2 font-semibold">MovieMall</a>
                <span class="hidden tablet:block text-xs font-semibold text-placeholder uppercase tracking-widest">Admin Panel</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent">Products</a>
                <a href="{{ route('admin.schedule') }}" class="rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent">Schedule</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6">
        <div class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                <h2 class="font-bold text-lg">Orders</h2>
            </div>

            <div class="flex items-center justify-between gap-4 px-5 py-3 border-b border-border text-sm">
                <div class="flex gap-2">
                    <a href="{{ route('admin.orders', array_filter(['status' => 'all', 'search' => $search])) }}"
                       class="rounded-lg px-3 py-1.5 text-xs transition {{ $status === 'all' ? 'bg-accent font-semibold' : 'border border-border bg-button hover:border-accent' }}">All</a>
                    <a href="{{ route('admin.orders', array_filter(['status' => 'paid', 'search' => $search])) }}"
                       class="rounded-lg px-3 py-1.5 text-xs transition {{ $status === 'paid' ? 'bg-accent font-semibold' : 'border border-border bg-button hover:border-accent' }}">Paid</a>
                    <a href="{{ route('admin.orders', array_filter(['status' => 'draft', 'search' => $search])) }}"
                       class="rounded-lg px-3 py-1.5 text-xs transition {{ $status === 'draft' ? 'bg-accent font-semibold' : 'border border-border bg-button hover:border-accent' }}">Draft</a>
                </div>
                <form method="GET" action="{{ route('admin.orders') }}" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $status }}" />
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search orders..."
                           class="rounded-lg border border-border bg-button px-3 py-1.5 text-xs placeholder:text-placeholder focus:border-accent focus:outline-none w-48" />
                    @if ($search)
                        <a href="{{ route('admin.orders', ['status' => $status]) }}" class="text-xs text-placeholder hover:text-accent">Clear</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border text-left text-xs text-placeholder">
                            <th class="px-5 py-3 font-semibold">Invoice</th>
                            <th class="px-5 py-3 font-semibold">Customer</th>
                            <th class="px-5 py-3 font-semibold">Date</th>
                            <th class="px-5 py-3 font-semibold">Delivery</th>
                            <th class="px-5 py-3 font-semibold">Total</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="border-b border-border transition hover:bg-button/40">
                                <td class="px-5 py-3 font-mono text-xs text-placeholder">
                                    {{ $order->invoice_number ?? '—' }}
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-medium">{{ $order->customer_name }}</p>
                                    <p class="text-xs text-placeholder">{{ $order->customer_email }}</p>
                                </td>
                                <td class="px-5 py-3 text-placeholder text-xs whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M j, Y · H:i') }}
                                </td>
                                <td class="px-5 py-3 capitalize text-placeholder text-xs">{{ $order->delivery_type ?? '—' }}</td>
                                <td class="px-5 py-3 font-semibold text-accent">{{ number_format((float) $order->total_amount, 2) }}€</td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                        {{ $order->status === 'paid' ? 'bg-green-900/40 text-green-400 border border-green-700/50' : 'bg-button text-placeholder border border-border' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-placeholder">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 tablet:flex-row tablet:items-center tablet:justify-between px-5 py-3 border-t border-border text-xs text-placeholder">
                <span>
                    @if ($orders->total() > 0)
                        Showing {{ (($orders->currentPage() - 1) * $orders->perPage()) + 1 }}–{{ min($orders->currentPage() * $orders->perPage(), $orders->total()) }} of {{ $orders->total() }} orders
                    @else
                        Showing 0 orders
                    @endif
                </span>
                <div class="text-sm [&_a]:text-placeholder [&_a:hover]:text-accent [&_span]:text-accent">{{ $orders->links() }}</div>
            </div>
        </div>
    </main>
</body>
</html>
