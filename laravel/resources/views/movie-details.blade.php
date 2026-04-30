<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $movie['title'] }} | MovieMall</title>

    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-10">
        <a href="{{ route('movies.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm text-placeholder transition hover:text-accent"
            >&larr; Back to Movies</a
        >

        <section class="grid grid-cols-1 gap-8 tablet:grid-cols-2">
            <div
                class="rounded-2xl border border-border bg-dark p-4 shadow-[0_14px_36px_rgba(0,0,0,.5)] tablet:p-6"
                x-data="{ activeImage: 0, images: {{ json_encode($movie['images']) }} }"
            >
                <div class="mx-auto overflow-hidden rounded-2xl bg-button aspect-square relative w-full h-full">
                    <template x-for="(image, index) in images" :key="index">
                        <img
                            x-show="activeImage === index"
                            :src="image"
                            alt="{{ $movie['title'] }} Poster"
                            class="absolute inset-0 h-full w-full object-cover"
                        />
                    </template>

                    <button
                        x-show="images.length > 1"
                        @click="activeImage = activeImage === 0 ? images.length - 1 : activeImage - 1"
                        class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/75"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    </button>
                    <button
                        x-show="images.length > 1"
                        @click="activeImage = activeImage === images.length - 1 ? 0 : activeImage + 1"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/75"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </button>
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
                            <a
                                href="{{ route('souvenirs.index') }}?movies[]={{ $movie['movie_id'] }}"
                                class="text-sm text-placeholder transition hover:text-accent"
                                >See More</a
                            >
                        </div>
                        <ul class="grid grid-cols-2 gap-4 tablet:grid-cols-4">
                            @forelse ($movie['related_souvenirs'] as $souvenir)
                                <li
                                    class="group bg-dark rounded-2xl overflow-hidden border border-border shadow-[0_14px_36px_rgba(0,0,0,.35)] transition-all duration-300 hover:bg-button hover:border-accent hover:scale-105"
                                >
                                    <a href="{{ route('souvenirs.show', \Illuminate\Support\Str::slug($souvenir['title'])) }}" class="block relative h-full">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-b from-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                        ></div>
                                        <div class="bg-button aspect-square overflow-hidden">
                                            <img
                                                src="{{ $souvenir['image'] }}"
                                                alt="{{ $souvenir['title'] }} souvenir"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            />
                                        </div>
                                        <div class="flex flex-col gap-1 p-3">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="min-w-0 truncate font-semibold">{{ $souvenir['title'] }}</span>
                                                <span class="shrink-0 text-sm font-semibold text-accent">{{ $souvenir['price'] }}</span>
                                            </div>
                                            <span class="truncate text-xs text-placeholder">{{ $souvenir['movie'] }}</span>
                                            <span
                                                class="inline-block self-start rounded-full border border-border bg-button px-2 py-0.5 text-[0.6rem] font-semibold text-placeholder"
                                                >{{ $souvenir['type'] }}</span
                                            >
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
                                :class="selectedDate === date
                                    ? 'border-accent bg-accent/10 text-accent'
                                    : 'border-border bg-button hover:border-accent hover:text-accent'"
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
                                :class="selectedTime === time
                                    ? 'border-accent bg-accent/10 text-accent'
                                    : 'border-border bg-button hover:border-accent hover:text-accent'"
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
                            <div class="flex items-center gap-3">
                                <span class="text-placeholder flex-shrink-0 whitespace-nowrap" x-text="`Seats ${selectedSeatsCount}`">Seats (0)</span>

                                <span class="font-medium break-words min-w-0" x-text="selectedSeatLabels"></span>
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

                        <button
                            type="button"
                            @click="addToCart()"
                            :disabled="selectedSeats.length === 0"
                            :class="{ 'opacity-50 cursor-not-allowed': selectedSeats.length === 0 }"
                            class="mt-5 flex w-full justify-center rounded-xl bg-accent px-6 py-3.5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:text-lg"
                        >
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

                allSeats: @json ($movie['seats']),
                layout: [],

                async init() {
                    try {
                        const editRaw = localStorage.getItem('moviemall_edit_item');
                        const urlParams = new URLSearchParams(window.location.search);
                        const isEditParam = urlParams.get('edit') === '1';

                        if (editRaw && isEditParam) {
                            const edit = JSON.parse(editRaw);

                            const ts = parseInt(edit && typeof edit.ts !== 'undefined' ? edit.ts : NaN, 10);
                            if (Number.isFinite(ts) && Date.now() - ts > 30 * 60 * 1000) {
                                try {
                                    localStorage.removeItem('moviemall_edit_item');
                                } catch (e) {}
                            } else {
                                const currentMovieId = String({{ $movie['id'] ?? $movie['movie_id'] ?? 1 }});
                                if (String(edit.reference_id) === currentMovieId) {
                                    if (edit.options && edit.options.date) this.selectedDate = edit.options.date;
                                    if (edit.options && edit.options.time) this.selectedTime = edit.options.time;
                                    window.__editingCartItem = edit;
                                }
                            }

                            try {
                                sessionStorage.removeItem('moviemall_edit_active');
                            } catch (e) {}
                        }

                        const response = await axios.post(
                            '{{ route('cart.details') }}',
                            { items: window.CartService.getCart() },
                            { headers: { 'X-CSRF-TOKEN': window.csrfToken } }
                        );
                        this.cartDetails = response.data.items || [];
                    } catch (e) {
                        console.error(e);
                        this.cartDetails = [];
                    }

                    this.generateLayout();

                    try {
                        const edit = window.__editingCartItem;
                        if (edit && edit.options && Array.isArray(edit.options.seat_ids)) {
                            const seatIds = edit.options.seat_ids.map(String);
                            this.layout.forEach((r) =>
                                r.seats.forEach((s) => {
                                    if (seatIds.includes(String(s.id))) s.status = 'selected';
                                })
                            );
                        }
                    } catch (e) {
                        console.error(e);
                    }
                },

                generateLayout() {
                    const seed = this.selectedDate + this.selectedTime;

                    const cartItems = this.cartDetails && this.cartDetails.length > 0 ? this.cartDetails : window.CartService.getCart();
                    const seatsInCart = [];
                    const editing = window.__editingCartItem || null;

                    const optionsMatch = (a, b) => {
                        if (!a || !b) return false;
                        if (String(a.schedule_slot_id || '') !== String(b.schedule_slot_id || '')) return false;
                        if (String(a.date || '') !== String(b.date || '')) return false;
                        if (String(a.time || '') !== String(b.time || '')) return false;
                        const aSeats = Array.isArray(a.seat_ids) ? [...a.seat_ids].map(String).sort() : [];
                        const bSeats = Array.isArray(b.seat_ids) ? [...b.seat_ids].map(String).sort() : [];
                        if (aSeats.length !== bSeats.length) return false;
                        for (let i = 0; i < aSeats.length; i++) if (aSeats[i] !== bSeats[i]) return false;
                        return true;
                    };

                    cartItems.forEach((item) => {
                        if (item.type === 'ticket' && String(item.reference_id) === String({{ $movie['id'] ?? $movie['movie_id'] ?? 1 }})) {
                            if (editing) {
                                if (editing.cart_item_id && item.cart_item_id && String(item.cart_item_id) === String(editing.cart_item_id)) {
                                    return;
                                }
                                if (
                                    item.type === editing.type &&
                                    String(item.reference_id) === String(editing.reference_id) &&
                                    optionsMatch(item.options || {}, editing.options || {})
                                ) {
                                    return;
                                }
                            }

                            if (
                                item.options &&
                                String(item.options.schedule_slot_id ?? '') === String({{ $movie['schedule_slot_id'] ?? 1 }}) &&
                                String(item.options.date ?? '') === String(this.selectedDate) &&
                                String(item.options.time ?? '') === String(this.selectedTime)
                            ) {
                                if (item.options.seat_ids) {
                                    seatsInCart.push(...item.options.seat_ids.map((sid) => String(sid)));
                                }
                                return;
                            }
                        }
                    });

                    const grouped = {};
                    this.allSeats.forEach((s) => {
                        if (!grouped[s.row_label]) grouped[s.row_label] = [];
                        grouped[s.row_label].push(s);
                    });

                    this.layout = Object.keys(grouped)
                        .sort()
                        .map((r) => {
                            const seats = grouped[r]
                                .sort((a, b) => a.seat_number - b.seat_number)
                                .map((s) => {
                                    const id = s.id;
                                    const label = s.row_label + s.seat_number;
                                    let occ = (s.row_label.charCodeAt(0) + s.seat_number + seed.length + (seed.charCodeAt(0) || 0)) % 5 === 0;
                                    if (seatsInCart.includes(String(id))) {
                                        occ = true;
                                    }
                                    return {
                                        id: id,
                                        label: label,
                                        status: occ || seatsInCart.includes(String(id)) ? 'occupied' : 'available'
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
                    return this.layout.flatMap((r) => r.seats.filter((s) => s.status === 'selected'));
                },

                get selectedSeatsCount() {
                    let count = this.selectedSeats.length;
                    return count > 0 ? `(${count})` : '';
                },

                get selectedSeatLabels() {
                    return this.selectedSeats.map((s) => s.label).join(', ');
                },

                get totalPrice() {
                    return (this.selectedSeats.length * this.pricePerTicket).toFixed(2);
                },

                async addToCart() {
                    if (this.selectedSeats.length === 0) return;

                    const newItem = {
                        type: 'ticket',
                        reference_id: {{ $movie['id'] ?? 1 }},
                        quantity: this.selectedSeats.length,
                        options: {
                            schedule_slot_id: {{ $movie['schedule_slot_id'] ?? 1 }},
                            date: this.selectedDate,
                            time: this.selectedTime,
                            seat_ids: this.selectedSeats.map((s) => s.id)
                        }
                    };

                    let editing = window.__editingCartItem;
                    if (!editing) {
                        try {
                            const raw = localStorage.getItem('moviemall_edit_item');
                            if (raw) editing = JSON.parse(raw);
                        } catch (e) {}
                    }

                    if (editing && String(editing.reference_id) === String({{ $movie['id'] ?? 1 }})) {
                        if (!window.isLoggedIn) {
                            const cart = window.CartService.getCart();
                            const idx = cart.findIndex(
                                (c) =>
                                    c.type === editing.type &&
                                    String(c.reference_id) === String(editing.reference_id) &&
                                    JSON.stringify(c.options) === JSON.stringify(editing.options)
                            );
                            if (idx !== -1) cart.splice(idx, 1);
                            cart.push({ ...newItem, quantity: newItem.quantity });
                            localStorage.setItem(window.CartService.cartKey, JSON.stringify(cart));
                        } else {
                            try {
                                await axios.post(
                                    '{{ route('cart.remove') }}',
                                    { type: editing.type, reference_id: editing.reference_id, options: editing.options },
                                    { headers: { 'X-CSRF-TOKEN': window.csrfToken } }
                                );
                            } catch (e) {
                                console.error(e);
                            }

                            await window.CartService.add(newItem);
                        }

                        localStorage.removeItem('moviemall_edit_item');
                        sessionStorage.removeItem('moviemall_edit_active');
                        window.__editingCartItem = null;
                        window.location.href = '{{ route('cart.index') }}';
                        return;
                    }

                    await window.CartService.add(newItem);
                    window.location.href = '{{ route('cart.index') }}';
                }
            }));
        });
    </script>
</body>
</html>
