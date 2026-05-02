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

    <main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6">
        <div class="flex items-center gap-2 text-xs text-placeholder mb-6">
            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-accent">Dashboard</a>
            <span>/</span>
            <span class="text-text">Edit {{ $type === 'movie' ? 'Movie' : 'Souvenir' }}</span>
        </div>

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Edit {{ $type === 'movie' ? 'Movie' : 'Souvenir' }}</h1>
            <a
                href="{{ route('admin.dashboard') }}"
                class="rounded-lg border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent"
            >
                ← Back
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

        <div class="grid gap-6 desktop:grid-cols-[1fr_360px]">

            {{-- LEFT COLUMN --}}
            <div class="flex flex-col gap-5">

                {{-- Basic Information --}}
                <div class="rounded-2xl border border-border bg-dark p-5 tablet:p-6 shadow-[0_14px_36px_rgba(0,0,0,.25)]">
                    <h2 class="font-semibold mb-4">Basic Information</h2>
                    <p class="text-xs text-placeholder mb-4">Editing ID #{{ $product->id }}</p>

                    <form id="main-form" method="POST" action="{{ route('admin.product.update', ['type' => $type, 'id' => $product->id]) }}" class="flex flex-col gap-4">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col gap-1.5">
                            <label for="title" class="text-sm font-medium text-placeholder">{{ $type === 'movie' ? 'Title' : 'Product Name' }} <span class="text-accent">*</span></label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                value="{{ old('title', $product->title) }}"
                                required
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label for="price" class="text-sm font-medium text-placeholder">Price <span class="text-accent">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-placeholder text-sm">€</span>
                                    <input
                                        id="price"
                                        name="price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('price', $product->price) }}"
                                        {{ $type === 'souvenir' ? 'required' : '' }}
                                        class="w-full rounded-xl border border-border bg-button pl-8 pr-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                    />
                                </div>
                            </div>

                            @if ($type === 'movie')
                                <div class="flex flex-col gap-1.5">
                                    <label for="rating" class="text-sm font-medium text-placeholder">Rating (0–10)</label>
                                    <input
                                        id="rating"
                                        name="rating"
                                        type="number"
                                        step="0.1"
                                        min="0"
                                        max="10"
                                        value="{{ old('rating', $product->rating) }}"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                    />
                                </div>
                            @else
                                <div class="flex flex-col gap-1.5">
                                    <label for="quantity" class="text-sm font-medium text-placeholder">Quantity <span class="text-accent">*</span></label>
                                    <input
                                        id="quantity"
                                        name="quantity"
                                        type="number"
                                        min="0"
                                        value="{{ old('quantity', $product->quantity) }}"
                                        required
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                    />
                                </div>
                            @endif
                        </div>

                        @if ($type === 'movie')
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label for="release_date" class="text-sm font-medium text-placeholder">Release Date</label>
                                    <input
                                        id="release_date"
                                        name="release_date"
                                        type="date"
                                        value="{{ old('release_date', $product->release_date) }}"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                    />
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="length_minutes" class="text-sm font-medium text-placeholder">Length (minutes)</label>
                                    <input
                                        id="length_minutes"
                                        name="length_minutes"
                                        type="number"
                                        min="1"
                                        value="{{ old('length_minutes', $product->length_minutes) }}"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                    />
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label for="description" class="text-sm font-medium text-placeholder">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    class="w-full resize-none rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                >{{ old('description', $product->description) }}</textarea>
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label for="category_id" class="text-sm font-medium text-placeholder">Category</label>
                                    <select
                                        id="category_id"
                                        name="category_id"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30 appearance-none"
                                    >
                                        <option value="">None</option>
                                        @foreach ($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                {{ (string) old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}
                                            >{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="status_id" class="text-sm font-medium text-placeholder">Status</label>
                                    <select
                                        id="status_id"
                                        name="status_id"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30 appearance-none"
                                    >
                                        <option value="">None</option>
                                        @foreach ($statuses as $status)
                                            <option
                                                value="{{ $status->id }}"
                                                {{ (string) old('status_id', $product->status_id) === (string) $status->id ? 'selected' : '' }}
                                            >{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label for="movie_id" class="text-sm font-medium text-placeholder">Linked Movie</label>
                                <select
                                    id="movie_id"
                                    name="movie_id"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30 appearance-none"
                                >
                                    <option value="">None</option>
                                    @foreach ($movies as $movie)
                                        <option
                                            value="{{ $movie->id }}"
                                            {{ (string) old('movie_id', $product->movie_id) === (string) $movie->id ? 'selected' : '' }}
                                        >{{ $movie->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </form>
                </div>

                {{-- Photos --}}
                <div class="rounded-2xl border border-border bg-dark p-5 tablet:p-6 shadow-[0_14px_36px_rgba(0,0,0,.25)]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-semibold">Photos <span class="text-accent">*</span></h2>
                            <p class="text-xs text-placeholder mt-0.5">First photo will be the main image.</p>
                        </div>
                        <label
                            for="photo-input"
                            class="cursor-pointer flex items-center gap-2 rounded-xl bg-accent px-4 py-2 text-sm font-semibold transition hover:brightness-110"
                        >
                            + Add Photo
                        </label>
                    </div>

                    <form
                        id="add-photo-form"
                        method="POST"
                        action="{{ route('admin.product.image.add', ['type' => $type, 'id' => $product->id]) }}"
                        enctype="multipart/form-data"
                        class="hidden"
                    >
                        @csrf
                        <input
                            id="photo-input"
                            type="file"
                            name="image"
                            accept="image/*"
                            onchange="document.getElementById('add-photo-form').submit()"
                        />
                    </form>

                    @if ($images->isEmpty())
                        <p class="text-sm text-placeholder">No photos yet. Add one above.</p>
                    @else
                        <div class="grid grid-cols-2 tablet:grid-cols-4 gap-3">
                            @foreach ($images as $image)
                                <div class="group relative rounded-xl overflow-hidden border border-border bg-button aspect-square">
                                    <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover" />

                                    @if ($image->is_primary)
                                        <span class="absolute top-1.5 left-1.5 rounded-md bg-accent px-1.5 py-0.5 text-xs font-semibold">Main</span>
                                    @else
                                        <form
                                            method="POST"
                                            action="{{ route('admin.product.image.primary', ['type' => $type, 'imageId' => $image->id]) }}"
                                            class="absolute top-1.5 left-1.5"
                                        >
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                            <button
                                                type="submit"
                                                class="rounded-md bg-black/60 px-1.5 py-0.5 text-xs opacity-0 group-hover:opacity-100 transition hover:bg-black/80"
                                            >Set Main</button>
                                        </form>
                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('admin.product.image.remove', ['type' => $type, 'imageId' => $image->id]) }}"
                                        class="absolute top-1.5 right-1.5"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                        <button
                                            type="submit"
                                            aria-label="Remove photo"
                                            class="leading-none flex items-center justify-center rounded-full bg-black/60 w-6 h-6 opacity-0 group-hover:opacity-100 transition hover:bg-black/80"
                                        >×</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="flex flex-col gap-5">
                <div class="flex flex-col gap-3">
                    <button
                        form="main-form"
                        type="submit"
                        class="w-full rounded-xl bg-accent px-6 py-3.5 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.4)] transition hover:brightness-110"
                    >Save Changes</button>

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="w-full rounded-xl border border-border bg-button px-6 py-3.5 text-center font-semibold transition hover:border-accent hover:text-accent"
                    >Cancel</a>

                    <form
                        method="POST"
                        action="{{ route('admin.product.destroy', ['type' => $type, 'id' => $product->id]) }}"
                        onsubmit="return confirm('Delete this {{ $type }}? This cannot be undone.')"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="w-full rounded-xl border border-red-800/60 bg-red-950/30 px-6 py-3.5 text-sm font-semibold text-red-400 transition hover:border-red-500 hover:text-red-300"
                        >Delete Product</button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
