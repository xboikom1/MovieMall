<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Product - MovieMall Admin</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-text">
    <header class="border-b border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)]">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 tablet:px-6">
            <div class="flex items-center gap-3 tablet:gap-5">
                <a href="{{ route('home') }}" class="flex items-center rounded-xl bg-accent px-4 py-2 font-semibold">MovieMall</a>
                <span class="hidden tablet:block text-xs font-semibold text-placeholder uppercase tracking-widest">Admin Panel</span>
            </div>
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
    </header>

    <main class="mx-auto max-w-4xl px-4 py-8 tablet:px-6">
        <div class="mb-6 flex items-center justify-between gap-3">
            <h1 class="text-2xl font-bold">Edit {{ $type === 'movie' ? 'Movie' : 'Souvenir' }}</h1>
            <a
                href="{{ route('admin.dashboard') }}"
                class="rounded-lg border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent"
            >
                Back to Dashboard
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-green-500/30 bg-green-900/30 px-4 py-3 text-sm text-green-200">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-500/30 bg-red-900/30 px-4 py-3 text-sm text-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] p-5 tablet:p-6">
            <form method="POST" action="{{ route('admin.product.update', ['type' => $type, 'id' => $product->id]) }}" class="space-y-5">
                @csrf
                @method ('PUT')

                <div class="flex items-start gap-4 rounded-xl border border-border bg-button/40 p-3">
                    <img
                        src="{{ $product->image ?: ($type === 'movie' ? '/images/Supergrandpa.png' : '/images/SuperGrandpaSouvenir.png') }}"
                        alt="{{ $product->title }}"
                        class="h-20 w-20 rounded-lg object-cover"
                    />
                    <div>
                        <p class="text-sm text-placeholder">Editing ID #{{ $product->id }}</p>
                        <p class="font-semibold">{{ $product->title }}</p>
                    </div>
                </div>

                <div class="grid gap-4 tablet:grid-cols-2">
                    <div class="tablet:col-span-2">
                        <label for="title" class="mb-1 block text-sm text-placeholder">Name</label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title', $product->title) }}"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            required
                        />
                    </div>

                    <div>
                        <label for="price" class="mb-1 block text-sm text-placeholder">Price</label>
                        <input
                            id="price"
                            name="price"
                            type="number"
                            step="0.01"
                            min="0"
                            value="{{ old('price', $product->price) }}"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            {{ $type === 'souvenir' ? 'required' : '' }}
                        />
                    </div>

                    @if ($type === 'movie')
                        <div>
                            <label for="rating" class="mb-1 block text-sm text-placeholder">Rating (0-10)</label>
                            <input
                                id="rating"
                                name="rating"
                                type="number"
                                step="0.1"
                                min="0"
                                max="10"
                                value="{{ old('rating', $product->rating) }}"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            />
                        </div>
                        <div>
                            <label for="release_date" class="mb-1 block text-sm text-placeholder">Release Date</label>
                            <input
                                id="release_date"
                                name="release_date"
                                type="date"
                                value="{{ old('release_date', $product->release_date) }}"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            />
                        </div>
                        <div>
                            <label for="length_minutes" class="mb-1 block text-sm text-placeholder">Length (minutes)</label>
                            <input
                                id="length_minutes"
                                name="length_minutes"
                                type="number"
                                min="1"
                                value="{{ old('length_minutes', $product->length_minutes) }}"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            />
                        </div>
                        <div class="tablet:col-span-2">
                            <label for="description" class="mb-1 block text-sm text-placeholder">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                                >{{ old('description', $product->description) }}</textarea
                            >
                        </div>
                    @else
                        <div>
                            <label for="quantity" class="mb-1 block text-sm text-placeholder">Quantity</label>
                            <input
                                id="quantity"
                                name="quantity"
                                type="number"
                                min="0"
                                value="{{ old('quantity', $product->quantity) }}"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                                required
                            />
                        </div>
                        <div>
                            <label for="category_id" class="mb-1 block text-sm text-placeholder">Category</label>
                            <select
                                id="category_id"
                                name="category_id"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            >
                                <option value="">None</option>
                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ (string) old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="movie_id" class="mb-1 block text-sm text-placeholder">Linked Movie</label>
                            <select
                                id="movie_id"
                                name="movie_id"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            >
                                <option value="">None</option>
                                @foreach ($movies as $movie)
                                    <option
                                        value="{{ $movie->id }}"
                                        {{ (string) old('movie_id', $product->movie_id) === (string) $movie->id ? 'selected' : '' }}
                                    >
                                        {{ $movie->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status_id" class="mb-1 block text-sm text-placeholder">Status</label>
                            <select
                                id="status_id"
                                name="status_id"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent"
                            >
                                <option value="">None</option>
                                @foreach ($statuses as $status)
                                    <option
                                        value="{{ $status->id }}"
                                        {{ (string) old('status_id', $product->status_id) === (string) $status->id ? 'selected' : '' }}
                                    >
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="rounded-xl bg-accent px-6 py-3 text-sm font-semibold transition hover:brightness-110">Save Changes</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
