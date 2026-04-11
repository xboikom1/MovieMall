<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $souvenir['title'] }} | MovieMall</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-10">
      <a href="{{ route('souvenirs.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm text-placeholder transition hover:text-accent">&larr; Back to Souvenirs</a>

      <section class="grid grid-cols-1 gap-8 tablet:grid-cols-2">
        <div class="rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.5)] tablet:p-6">
          <div class="aspect-square overflow-hidden rounded-2xl bg-button w-full">
            <img src="{{ $souvenir['image'] }}" alt="{{ $souvenir['title'] }} image" class="h-full w-full object-cover" />
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
              <span class="rounded-full border border-border bg-button px-3 py-1 text-xs font-semibold text-placeholder">{{ $souvenir['in_stock'] ? 'In Stock' : 'Out of Stock' }}</span>
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
              <div class="flex flex-col gap-3 tablet:flex-row tablet:items-center tablet:justify-between">
                <div>
                  <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-placeholder">Quantity</p>
                  <div class="inline-flex h-10 items-center overflow-hidden rounded-xl border border-border bg-bg">
                    <button id="qtyMinus" type="button" class="qty-btn h-full px-4 text-sm font-semibold text-placeholder transition hover:text-accent" aria-label="Decrease quantity">−</button>
                    <label for="quantityInput" class="sr-only">Quantity</label>
                    <input id="quantityInput" type="text" value="1" inputmode="numeric" pattern="[0-9]*"
                      class="qty-input h-full w-10 appearance-none border-none bg-transparent p-0 text-center text-sm outline-none text-text focus:ring-0"
                      aria-label="Quantity" style="background-color: transparent"
                    />
                    <button id="qtyPlus" type="button" class="qty-btn h-full px-4 text-sm font-semibold text-placeholder transition hover:text-accent" aria-label="Increase quantity">+</button>
                  </div>
                </div>

                <a href="{{ route('cart.index') }}" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-accent px-6 py-3.5 text-xl font-semibold shadow-[0_14px_36px_rgba(0,0,0,.40)] transition hover:brightness-110 tablet:mt-0 tablet:w-[260px]">Add to Cart</a>
              </div>

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
            <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
              <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($s['title'])) }}" class="block relative h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                <div class="bg-button aspect-square overflow-hidden">
                  <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex flex-col gap-1 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <span class="min-w-0 truncate font-semibold">{{ $s['title'] }}</span>
                    <span class="shrink-0 text-sm font-semibold text-accent">{{ $s['price'] }}</span>
                  </div>
                  <span class="truncate text-xs text-placeholder">{{ $s['movie'] }}</span>
                  <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">{{ $s['type'] }}</span>
                </div>
              </a>
            </li>
          @endforeach
        </ul>
      </section>
    </main>

    <x-layout.footer />

    <script>
      document.getElementById('qtyPlus')?.addEventListener('click', function () {
        const input = document.getElementById('quantityInput');
        input.value = Math.max(1, parseInt(input.value || '1') + 1);
      });
      document.getElementById('qtyMinus')?.addEventListener('click', function () {
        const input = document.getElementById('quantityInput');
        input.value = Math.max(1, parseInt(input.value || '1') - 1);
      });
    </script>
  </body>
</html>

