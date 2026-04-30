<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Product - MovieMall Admin</title>

    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-bg text-text">
    <!-- header -->
    <header class="border-b border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)]">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 tablet:px-6">
            <div class="flex items-center gap-3 tablet:gap-5">
                <a href="{{ route('home') }}" class="flex items-center rounded-xl bg-accent px-4 py-2 font-semibold">MovieMall</a>
                <span class="hidden tablet:flex text-xs font-semibold text-placeholder uppercase tracking-widest">Admin Panel</span>
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
            <span class="text-text">New Product</span>
        </div>

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Add New Product</h1>
            <a
                href="{{ route('admin.dashboard') }}"
                class="rounded-lg border border-border bg-button px-4 py-2 text-sm transition hover:border-accent hover:text-accent"
                >&larr; Back</a
            >
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-900/30 px-4 py-3 text-sm text-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.product.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="grid gap-6 desktop:grid-cols-[1fr_360px]"
            x-data="productForm()"
        >
            @csrf
            <div class="flex flex-col gap-5">
                <!-- basic information -->
                <div class="rounded-2xl border border-border bg-dark p-5 tablet:p-6 shadow-[0_14px_36px_rgba(0,0,0,.25)]">
                    <h2 class="font-semibold mb-4">Basic Information</h2>

                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-medium text-placeholder" for="name">Product Name <span class="text-accent">*</span></label>
                            <input
                                id="name"
                                name="title"
                                type="text"
                                value="{{ old('title') }}"
                                placeholder="Enter product name"
                                required
                                class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                            />
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-medium text-placeholder" for="description">Description <span class="text-accent">*</span></label>
                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                placeholder="Describe the product..."
                                required
                                class="w-full resize-none rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                >{{ old('description') }}</textarea
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-placeholder" for="price">Price <span class="text-accent">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 text-placeholder text-sm -translate-y-1/2">€</span>
                                    <input
                                        id="price"
                                        name="price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        value="{{ old('price') }}"
                                        placeholder="0.00"
                                        required
                                        class="w-full rounded-xl border border-border bg-button pl-8 pr-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                                    />
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-medium text-placeholder" for="category">Category <span class="text-accent">*</span></label>
                                <select
                                    id="category"
                                    name="type"
                                    required
                                    x-model="productType"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30 appearance-none"
                                >
                                    <option value="" disabled>Select…</option>
                                    <option value="movie" {{ old('type') === 'movie' ? 'selected' : '' }}>Movie</option>
                                    <option value="souvenir" {{ old('type') === 'souvenir' ? 'selected' : '' }}>Souvenir</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                <div class="rounded-2xl border border-border bg-dark p-5 tablet:p-6 shadow-[0_14px_36px_rgba(0,0,0,.25)]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-semibold">Photos <span class="text-accent">*</span></h2>
                            <p class="text-xs text-placeholder mt-0.5">At least 2 photos required. First photo will be the main image.</p>
                        </div>
                        <label for="photo-input" class="cursor-pointer flex items-center gap-2 rounded-xl bg-accent px-4 py-2 text-sm font-semibold">
                            + Add Photo
                        </label>
                        <input id="photo-input" name="images[]" type="file" accept="image/*" multiple required class="hidden" @change="handleFiles" />
                    </div>

                    <!-- upload photo preview -->
                    <label
                        for="photo-input"
                        class="flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-border bg-button/40 p-10 text-center cursor-pointer transition hover:border-accent"
                        x-show="files.length === 0"
                    >
                        <div>
                            <p class="text-sm font-medium">Click to upload or drop the file here</p>
                            <p class="text-xs text-placeholder mt-1">PNG, JPG, WEBP</p>
                        </div>
                    </label>

                    <div x-show="files.length > 0" class="grid grid-cols-2 tablet:grid-cols-4 gap-4 mt-4">
                        <template x-for="(file, index) in files" :key="index">
                            <div class="relative w-full aspect-square bg-button rounded-xl border border-border overflow-hidden">
                                <img :src="file.preview" class="w-full h-full object-cover" />
                                <button
                                    type="button"
                                    @click.prevent="removeFile(index)"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shadow"
                                >
                                    &times;
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-5">
                <!-- status -->
                <div class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.25)]">
                    <h2 class="font-semibold mb-4">Status</h2>
                    <div class="flex flex-col gap-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm">Published</span>
                            <div class="relative">
                                <input type="checkbox" name="published" value="1" class="sr-only peer" checked />
                                <div class="w-10 h-5 bg-button rounded-full border border-border peer-checked:bg-accent transition"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-4 h-4 bg-placeholder rounded-full transition peer-checked:translate-x-5 peer-checked:bg-white"
                                ></div>
                            </div>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm">Featured</span>
                            <div class="relative">
                                <input type="checkbox" name="featured" value="1" class="sr-only peer" />
                                <div class="w-10 h-5 bg-button rounded-full border border-border peer-checked:bg-accent transition"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-4 h-4 bg-placeholder rounded-full transition peer-checked:translate-x-5 peer-checked:bg-white"
                                ></div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.25)]" x-show="productType === 'souvenir'">
                    <h2 class="font-semibold mb-4">Stock</h2>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm text-placeholder" for="stock">Quantity</label>
                        <input
                            id="stock"
                            name="quantity"
                            type="number"
                            min="0"
                            value="{{ old('quantity', 1) }}"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3 outline-none focus:border-accent focus:ring-2 focus:ring-accent/30"
                        />
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full rounded-xl bg-accent px-6 py-3.5 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.35)]">
                        Save Product
                    </button>
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="w-full rounded-xl border border-border bg-button px-6 py-3.5 text-center font-semibold transition hover:border-accent hover:text-accent"
                    >
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productForm', () => ({
                productType: '{{ old("type", "") }}',
                files: [],
                handleFiles(evt) {
                    const selectedFiles = Array.from(evt.target.files);
                    selectedFiles.forEach((file) => {
                        this.files.push({
                            file: file,
                            preview: URL.createObjectURL(file)
                        });
                    });
                    this.updateInput();
                },
                removeFile(index) {
                    this.files.splice(index, 1);
                    this.updateInput();
                },
                updateInput() {
                    const dt = new DataTransfer();
                    this.files.forEach((f) => dt.items.add(f.file));
                    document.getElementById('photo-input').files = dt.files;
                }
            }));
        });
    </script>
</body>
</html>
