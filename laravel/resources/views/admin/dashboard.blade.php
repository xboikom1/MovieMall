<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard — MovieMall</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-bg text-text">
    <header
        class="border-b border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)]"
    >
        <div
            class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 tablet:px-6"
        >
            <div class="flex items-center gap-3 tablet:gap-5">
                <a
                    href="{{ route('home') }}"
                    class="flex items-center rounded-xl bg-accent px-4 py-2 font-semibold"
                    >MovieMall</a
                >
                <span
                    class="hidden tablet:block text-xs font-semibold text-placeholder uppercase tracking-widest"
                    >Admin Panel</span
                >
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.schedule') }}"
                    class="rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent"
                    >Schedule</a
                >
                <a
                    href="{{ route('admin.orders') }}"
                    class="rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent"
                    >Orders</a
                >
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center gap-2 rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent"
                    >
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6">
        @if (session('status'))
            <div
                class="mb-4 rounded-xl border border-green-500/30 bg-green-900/30 px-4 py-3 text-sm text-green-200"
            >
                {{ session('status') }}
            </div>
        @endif

        <div
            class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden"
        >
            <div
                class="flex items-center justify-between px-5 py-4 border-b border-border"
            >
                <h2 class="font-bold text-lg">Products</h2>
                <a
                    href="{{ route('admin.product.new') }}"
                    class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2 text-sm font-semibold transition hover:brightness-110"
                >
                    + Add Product
                </a>
            </div>

            <div
                class="flex items-center justify-between gap-4 px-5 py-3 border-b border-border text-sm"
            >
                <div class="flex gap-2">
                    <a
                        href="{{ route('admin.dashboard', array_filter(['filter' => 'all', 'search' => $search])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs transition {{ $filter === 'all' ? 'bg-accent font-semibold' : 'border border-border bg-button hover:border-accent' }}"
                    >
                        All
                    </a>
                    <a
                        href="{{ route('admin.dashboard', array_filter(['filter' => 'movies', 'search' => $search])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs transition {{ $filter === 'movies' ? 'bg-accent font-semibold' : 'border border-border bg-button hover:border-accent' }}"
                    >
                        Movies
                    </a>
                    <a
                        href="{{ route('admin.dashboard', array_filter(['filter' => 'souvenirs', 'search' => $search])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs transition {{ $filter === 'souvenirs' ? 'bg-accent font-semibold' : 'border border-border bg-button hover:border-accent' }}"
                    >
                        Souvenirs
                    </a>
                </div>
                <form
                    method="GET"
                    action="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2"
                >
                    <input type="hidden" name="filter" value="{{ $filter }}" />
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search products..."
                        class="rounded-lg border border-border bg-button px-3 py-1.5 text-xs placeholder:text-placeholder focus:border-accent focus:outline-none w-48"
                    />
                    @if ($search)
                        <a
                            href="{{ route('admin.dashboard', ['filter' => $filter]) }}"
                            class="text-xs text-placeholder hover:text-accent"
                            >Clear</a
                        >
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="border-b border-border text-left text-xs text-placeholder"
                        >
                            <th
                                class="px-5 py-3 font-semibold sticky top-0 bg-dark z-20"
                            >
                                Product
                            </th>
                            <th
                                class="px-5 py-3 font-semibold sticky top-0 bg-dark z-20"
                            >
                                Category
                            </th>
                            <th
                                class="px-5 py-3 font-semibold sticky top-0 bg-dark z-20"
                            >
                                Price
                            </th>
                            <th
                                class="px-5 py-3 font-semibold sticky top-0 bg-dark z-20"
                            >
                                Description
                            </th>
                            <th
                                class="px-5 py-3 font-semibold text-right sticky top-0 bg-dark z-20 w-36"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr
                                class="border-b border-border transition hover:bg-button/40"
                            >
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-12 w-10 shrink-0 overflow-hidden rounded-lg bg-button"
                                        >
                                            <img
                                                src="{{ $product->image ?: ($product->type === 'movie' ? '/images/Supergrandpa.png' : '/images/SuperGrandpaSouvenir.png') }}"
                                                alt="{{ $product->title }}"
                                                class="h-full w-full object-cover"
                                            />
                                        </div>
                                        <span
                                            class="font-medium"
                                            >{{ $product->title }}</span
                                        >
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($product->type === 'movie')
                                        <span
                                            class="rounded-full bg-accent/10 px-2 py-0.5 text-xs text-accent"
                                            >Movie</span
                                        >
                                    @else
                                        <span
                                            class="rounded-full bg-button px-2 py-0.5 text-xs text-placeholder border border-border"
                                            >Souvenir</span
                                        >
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-semibold text-accent">
                                    {{ number_format((float) ($product->price ?? 0), 2) }}€
                                </td>
                                <td
                                    class="px-5 py-3 max-w-[60ch] text-placeholder"
                                    style="
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2;
                                        -webkit-box-orient: vertical;
                                        overflow: hidden;
                                    "
                                >
                                    {{ $product->description ?: 'No description' }}
                                </td>
                                <td
                                    class="px-5 py-3 text-right w-36 shrink-0 whitespace-nowrap"
                                >
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <a
                                            href="{{ route('admin.product.edit', ['type' => $product->type, 'id' => $product->id]) }}"
                                            class="rounded-lg border border-border bg-button px-3 py-1.5 text-xs transition hover:border-accent hover:text-accent"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.product.destroy', ['type' => $product->type, 'id' => $product->id]) }}"
                                            onsubmit="
                                                return confirm(
                                                    'Delete this product permanently?',
                                                );
                                            "
                                        >
                                            @csrf
                                            @method ('DELETE')
                                            <input
                                                type="hidden"
                                                name="filter"
                                                value="{{ $filter }}"
                                            />
                                            <input
                                                type="hidden"
                                                name="page"
                                                value="{{ $products->currentPage() }}"
                                            />
                                            <input
                                                type="hidden"
                                                name="search"
                                                value="{{ $search }}"
                                            />
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-border bg-button px-3 py-1.5 text-xs transition hover:border-red-500 hover:text-red-500"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
                                    class="px-5 py-8 text-center text-placeholder"
                                >
                                    No products found for the selected filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="flex flex-col gap-3 tablet:flex-row tablet:items-center tablet:justify-between px-5 py-3 border-t border-border text-xs text-placeholder"
            >
                <span>
                    @if ($products->total() > 0)
                        Showing {{ (($products->currentPage() - 1) * $products->perPage()) + 1 }}-{{ min($products->currentPage() * $products->perPage(), $products->total()) }} of {{ $products->total() }} products
                    @else
                        Showing 0 products
                    @endif
                </span>
                <div
                    class="text-sm [&_a]:text-placeholder [&_a:hover]:text-accent [&_span]:text-accent"
                >
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </main>
</body>
</html>
