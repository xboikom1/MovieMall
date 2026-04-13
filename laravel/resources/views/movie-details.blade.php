<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $movie['title'] }} | MovieMall</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-10">
      <a href="{{ route('movies.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm text-placeholder transition hover:text-accent">&larr; Back to Movies</a>

      <section class="grid grid-cols-1 gap-8 tablet:grid-cols-2">
        <div class="rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.5)] tablet:p-6">
          <div class="mx-auto overflow-hidden rounded-2xl bg-button aspect-square">
            <img src="{{ $movie['poster'] }}" alt="{{ $movie['title'] }} Poster" class="h-full w-full object-cover" />
          </div>
        </div>

        <div class="flex flex-col gap-6">
          <nav class="flex flex-wrap items-center gap-x-0 text-xs text-placeholder">
            <a href="{{ route('home') }}" class="shrink-0 transition hover:text-accent">Home</a>
            <span class="px-1.5 shrink-0">/</span>
            <a href="{{ route('movies.index') }}" class="shrink-0 transition hover:text-accent">Movies</a>
            <span class="px-1.5 shrink-0">/</span>
            <span class="line-clamp-1 min-w-0 text-text">{{ $movie['title'] }}</span>
          </nav>

          <div class="flex flex-col gap-3">
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
              @foreach ($movie['genres'] as $genre)
                <span class="rounded-full border border-border bg-button px-3 py-1">{{ $genre }}</span>
              @endforeach
            </div>

            <h1 class="text-3xl font-bold tablet:text-4xl break-words">{{ $movie['title'] }}</h1>
            <p class="text-sm italic text-placeholder">{{ $movie['synopsis'] }}</p>

            <div class="flex flex-wrap gap-2 text-sm text-placeholder">
              <span class="flex items-center gap-1.5"><span class="text-yellow-400">★</span>{{ $movie['rating'] }}</span>
              <span>|</span>
              <span class="flex items-center gap-1.5">{{ $movie['duration'] }}</span>
              <span>|</span>
              <span class="flex items-center gap-1.5">{{ $movie['release_date'] }}</span>
            </div>
          </div>
        </div>
      </section>

      <div class="mt-8 grid grid-cols-1 gap-6 tablet:gap-8 desktop:grid-cols-[1fr_380px]">
        <section>
          <div class="order-2 flex flex-col gap-6 desktop:order-1">
            <div class="flex border-b border-border">
              <button class="border-b-2 border-accent px-5 py-3 text-sm font-semibold">Overview</button>
            </div>

            <div class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-6">
              <h2 class="mb-3 text-lg font-bold">Synopsis</h2>
              <p class="text-sm">{{ $movie['synopsis'] }}</p>

              <div class="mt-6 grid grid-cols-2 gap-4 border-t border-border pt-5 text-sm">
                <div>
                  <p class="mb-1 text-placeholder">Director</p>
                  <p class="font-semibold break-words">{{ $movie['director'] }}</p>
                </div>
                <div>
                  <p class="mb-1 text-placeholder">Release Date</p>
                  <p class="font-semibold">{{ $movie['release_date'] }}</p>
                </div>
                <div>
                  <p class="mb-1 text-placeholder">Language</p>
                  <p class="font-semibold break-words">{{ $movie['language'] }}</p>
                </div>
                <div>
                  <p class="mb-1 text-placeholder">Studio</p>
                  <p class="font-semibold break-words">{{ $movie['studio'] }}</p>
                </div>
              </div>
            </div>

            <div>
              <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold tablet:text-xl">Related Souvenirs</h2>
                <a href="{{ route('souvenirs.index') }}" class="text-sm text-placeholder transition hover:text-accent">See More</a>
              </div>
              <ul class="grid grid-cols-2 gap-4 tablet:grid-cols-4">
                @forelse ($movie['related_souvenirs'] as $souvenir)
                  <li class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105">
                    <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($souvenir['title'])) }}" class="block relative h-full">
                      <div class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                      <div class="bg-button aspect-square overflow-hidden">
                        <img src="{{ $souvenir['image'] }}" alt="{{ $souvenir['title'] }} souvenir" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                      </div>
                      <div class="flex flex-col gap-1 p-3">
                        <div class="flex items-center justify-between gap-2">
                          <span class="min-w-0 truncate font-semibold">{{ $souvenir['title'] }}</span>
                          <span class="shrink-0 text-sm font-semibold text-accent">{{ $souvenir['price'] }}</span>
                        </div>
                        <span class="truncate text-xs text-placeholder">{{ $souvenir['movie'] }}</span>
                        <span class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder">{{ $souvenir['type'] }}</span>
                      </div>
                    </a>
                  </li>
                @empty
                  <li class="col-span-2 tablet:col-span-4 rounded-2xl border border-border bg-dark p-4 text-sm text-placeholder">
                    No related souvenirs yet.
                  </li>
                @endforelse
              </ul>
            </div>
          </div>
        </section>

        <aside class="order-1 flex flex-col gap-5 desktop:order-2">
          <div class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-6" x-data="movieBooking()">
            <h2 class="mb-5 text-lg font-bold">Book Tickets</h2>

            <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-placeholder">1. Select Date</p>
            <div class="mb-5 flex gap-2 text-sm font-semibold">
              <template x-for="date in dates" :key="date">
                <button
                  @click="setDate(date)"
                  :class="selectedDate === date ? 'border-accent bg-accent/10 text-accent' : 'border-border bg-button hover:border-accent hover:text-accent'"
                  class="flex-1 rounded-xl border py-2.5 transition"
                  x-text="date"
                ></button>
              </template>
            </div>

            <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-placeholder">2. Select Time</p>
            <div class="mb-5 grid grid-cols-2 gap-2 text-sm font-semibold">
              <template x-for="time in times" :key="time">
                <button
                  @click="setTime(time)"
                  :class="selectedTime === time ? 'border-accent bg-accent/10 text-accent' : 'border-border bg-button hover:border-accent hover:text-accent'"
                  class="rounded-xl border py-2.5 transition"
                  x-text="time"
                ></button>
              </template>
            </div>

            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-placeholder">3. Select Seats</p>
            <div class="mb-5 rounded-xl border border-border bg-bg p-3">
              <div class="mb-4 rounded-lg bg-button py-1.5 text-center text-xs font-semibold uppercase text-placeholder">Screen</div>

              <div class="flex flex-col items-center gap-2">
                <template x-for="row in layout" :key="row.label">
                  <div class="flex items-center gap-1">
                    <span class="mr-1 w-3 text-right text-[10px] text-placeholder" x-text="row.label"></span>
                    <template x-for="seat in row.seats" :key="seat.id">
                      <div
                        @click="toggleSeat(seat)"
                        :class="{
                          'bg-green-500 cursor-pointer': seat.status === 'available',
                          'bg-accent cursor-pointer': seat.status === 'selected',
                          'bg-border cursor-not-allowed': seat.status === 'occupied'
                        }"
                        class="h-4 w-5 rounded-sm transition-colors"
                      ></div>
                    </template>
                  </div>
                </template>

                <div class="mb-4 mt-3 flex justify-center gap-4 text-xs text-placeholder">
                  <span class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-green-500"></span>Available</span>
                  <span class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-accent"></span>Selected</span>
                  <span class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-border"></span>Occupied</span>
                </div>
              </div>

              <div class="flex flex-col gap-2 border-t border-border pt-4 text-sm">
                <div class="flex justify-between">
                  <span class="text-placeholder" x-text="`Seats ${selectedSeatsCount}`">Seats (0)</span>
                  <span class="font-medium" x-text="selectedSeatLabels"></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-placeholder">Price per ticket</span>
                  <span class="font-medium" x-text="`${pricePerTicket.toFixed(2)}EUR`"></span>
                </div>
                <div class="mt-1 flex justify-between font-bold">
                  <span>Total</span>
                  <span class="text-accent" x-text="`${totalPrice}EUR`"></span>
                </div>
              </div>

              <button type="button" @click="addToCart()" :disabled="selectedSeats.length === 0" :class="{'opacity-50 cursor-not-allowed': selectedSeats.length === 0}" class="mt-5 flex w-full justify-center rounded-xl bg-accent px-6 py-3.5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:text-lg">
                Add to cart
              </button>
            </div>
          </div>
        </aside>
      </div>
    </main>

    <x-layout.footer />
    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.data('movieBooking', () => ({
          dates: ['Mar 6', 'Mar 7', 'Mar 8'],
          times: ['14:00', '16:30', '19:30', '22:00'],
          selectedDate: 'Mar 6',
          selectedTime: '19:30',
          pricePerTicket: {{ $movie['price'] ?? 9.99 }},

          allSeats: @json($movie['seats']),
          layout: [],

          init() {
            this.generateLayout();
          },

          generateLayout() {
            const seed = this.selectedDate + this.selectedTime;

            const cartItems = window.CartService.getCart();
            const seatsInCart = [];
            cartItems.forEach(item => {
              if (item.type === 'ticket' && String(item.reference_id) === String({{ $movie['id'] ?? 1 }})) {
                if (item.options &&
                    item.options.schedule_slot_id == {{ $movie['schedule_slot_id'] ?? 1 }} &&
                    item.options.date === this.selectedDate &&
                    item.options.time === this.selectedTime) {
                  if (item.options.seat_ids) {
                    seatsInCart.push(...item.options.seat_ids);
                  }
                }
              }
            });

            const grouped = {};
            this.allSeats.forEach(s => {
                if(!grouped[s.row_label]) grouped[s.row_label] = [];
                grouped[s.row_label].push(s);
            });

            this.layout = Object.keys(grouped).sort().map(r => {
              const seats = grouped[r].sort((a,b) => a.seat_number - b.seat_number).map(s => {
                const id = s.id;
                const label = s.row_label + s.seat_number;
                let occ = (s.row_label.charCodeAt(0) + s.seat_number + seed.length + (seed.charCodeAt(0)||0)) % 5 === 0;
                if (seatsInCart.includes(id)) {
                  occ = true;
                }
                return {
                  id: id,
                  label: label,
                  status: occ ? 'occupied' : 'available'
                };
              });
              return { label: r, seats: seats };
            });
          },

          setDate(date) {
            this.selectedDate = date;
            this.generateLayout();
          },

          setTime(time) {
            this.selectedTime = time;
            this.generateLayout();
          },

          toggleSeat(seat) {
            if (seat.status === 'occupied') return;
            if (seat.status === 'available') {
              seat.status = 'selected';
            } else {
              seat.status = 'available';
            }
          },

          get selectedSeats() {
            return this.layout.flatMap(r => r.seats.filter(s => s.status === 'selected'));
          },

          get selectedSeatsCount() {
            let count = this.selectedSeats.length;
            return count > 0 ? `(${count})` : '';
          },

          get selectedSeatLabels() {
            return this.selectedSeats.map(s => s.label).join(', ');
          },

          get totalPrice() {
            return (this.selectedSeats.length * this.pricePerTicket).toFixed(2);
          },

          async addToCart() {
            if (this.selectedSeats.length === 0) return;

            await window.CartService.add({
              type: 'ticket',
              reference_id: {{ $movie['id'] ?? 1 }},
              quantity: this.selectedSeats.length,
              options: {
                schedule_slot_id: {{ $movie['schedule_slot_id'] ?? 1 }},
                date: this.selectedDate,
                time: this.selectedTime,
                seat_ids: this.selectedSeats.map(s => s.id)
              }
            });
            window.location.href = "{{ route('cart.index') }}";
          }
        }))
      })
    </script>
  </body>
</html>

