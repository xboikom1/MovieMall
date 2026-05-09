<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout | MovieMall</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main x-data="{ delivery: '{{ old('delivery', 'courier') }}' }" class="mx-auto max-w-7xl px-4 py-6 tablet:px-6 tablet:py-10">
        <a href="{{ route('cart.index') }}" class="block pb-4 text-placeholder transition hover:text-accent">← Back to Cart</a>

        <div class="mb-6 flex flex-col gap-3 tablet:flex-row tablet:items-start tablet:justify-between">
            <div class="flex flex-col gap-2">
                <h1 class="text-2xl font-bold tablet:text-4xl">Checkout</h1>
                <p class="text-sm text-placeholder">Complete your purchase securely. Review your items before confirming.</p>
            </div>

            @guest
                <div class="mt-2 flex items-center gap-3 text-sm tablet:mt-1">
                    <span class="text-placeholder">Already have an account?</span>
                    <a
                        href="{{ route('login') }}"
                        class="rounded-lg border border-border bg-button px-5 py-2 font-semibold shadow-[0_10px_24px_rgba(0,0,0,.35)] transition hover:bg-accent"
                        >Sign In</a
                    >
                </div>
            @endguest
        </div>

        <div class="grid grid-cols-1 gap-6 desktop:grid-cols-[1fr_380px] desktop:gap-8">
            {{-- Checkout Form --}}
            <section class="rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-6">
                @if(session('error'))
                    <div class="mb-5 rounded-xl border border-red-500 bg-red-500/10 px-4 py-3 text-sm text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                <form id="checkout-form" method="POST" action="{{ route('checkout.submit') }}" class="flex flex-col gap-8">
                    @csrf
                    {{-- Contact --}}
                    <div>
                        <h2 class="mb-4 text-xl font-bold">Contact</h2>
                        <div class="grid grid-cols-1 gap-4 tablet:grid-cols-2">
                            <div>
                                <label for="firstName" class="mb-1 block text-xs font-semibold text-placeholder">First Name</label>

                                <input
                                    id="firstName"
                                    name="firstName"
                                    type="text"
                                    autocomplete="given-name"
                                    placeholder="First name"
                                    value="{{ old('firstName') }}"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    required
                                />
                                @error('firstName') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="lastName" class="mb-1 block text-xs font-semibold text-placeholder">Last Name</label>
                                <input
                                    id="lastName"
                                    name="lastName"
                                    type="text"
                                    autocomplete="family-name"
                                    placeholder="Last name"
                                    value="{{ old('lastName') }}"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    required
                                />
                                @error('lastName') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="mb-1 block text-xs font-semibold text-placeholder">Email</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    placeholder="email@example.com"
                                    value="{{ old('email') }}"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    required
                                />
                                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="mb-1 block text-xs font-semibold text-placeholder">Phone</label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    autocomplete="tel"
                                    placeholder="+421 123 456 789"
                                    value="{{ old('phone') }}"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                />
                                @error('phone') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Delivery --}}
                    <div class="mb-6 rounded-2xl border border-border bg-bg p-4">
                        <h3 class="text-sm font-semibold">Delivery Method (Souvenirs)</h3>
                        <p class="mt-1 text-xs text-placeholder">Choose how you'd like your souvenir order delivered.</p>

                        <div class="mt-4 grid grid-cols-1 gap-3 tablet:grid-cols-3">
                            <label
                                class="flex cursor-pointer items-start gap-3 rounded-xl border border-border bg-dark p-4 transition hover:border-accent hover:bg-button"
                            >
                                <input type="radio" name="delivery" value="courier" x-model="delivery" class="mt-1 accent-accent" checked />
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold">Courier</span>
                                    <span class="block text-xs text-placeholder">Delivery to your address.</span>
                                </span>
                            </label>

                            <label
                                class="flex cursor-pointer items-start gap-3 rounded-xl border border-border bg-dark p-4 transition hover:border-accent hover:bg-button"
                            >
                                <input type="radio" name="delivery" value="locker" x-model="delivery" class="mt-1 accent-accent" />
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold">Parcel locker</span>
                                    <span class="block text-xs text-placeholder">Pickup from a nearby locker.</span>
                                </span>
                            </label>

                            <label
                                class="flex cursor-pointer items-start gap-3 rounded-xl border border-border bg-dark p-4 transition hover:border-accent hover:bg-button"
                            >
                                <input type="radio" name="delivery" value="pickup" x-model="delivery" class="mt-1 accent-accent" />
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold">Pick up myself</span>
                                    <span class="block text-xs text-placeholder">Collect from our store.</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div x-show="delivery !== 'pickup'" x-cloak>
                        <h2 class="mb-4 text-lg font-bold">Delivery Address</h2>

                        <div class="grid grid-cols-1 gap-4 tablet:grid-cols-2">
                            <div class="tablet:col-span-2">
                                <label for="address1" class="mb-1 block text-xs font-semibold text-placeholder">Street Address</label>
                                <input
                                    id="address1"
                                    name="address1"
                                    type="text"
                                    autocomplete="address-line1"
                                    placeholder="Street address"
                                    value="{{ old('address1') }}"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    :required="delivery !== 'pickup'"
                                />
                                @error('address1') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="city" class="mb-1 block text-xs font-semibold text-placeholder">City</label>
                                <input
                                    id="city"
                                    name="city"
                                    type="text"
                                    autocomplete="address-level2"
                                    placeholder="City"
                                    value="{{ old('city') }}"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    :required="delivery !== 'pickup'"
                                />
                                @error('city') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="postal" class="mb-1 block text-xs font-semibold text-placeholder">Postal Code</label>
                                <input
                                    id="postal"
                                    name="postal"
                                    type="text"
                                    autocomplete="postal-code"
                                    placeholder="Postal code"
                                    value="{{ old('postal') }}"
                                    class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    :required="delivery !== 'pickup'"
                                />
                                @error('postal') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="tablet:col-span-2">
                                <label for="country" class="mb-1 block text-xs font-semibold text-placeholder">Country</label>
                                <select
                                    id="country"
                                    name="country"
                                    autocomplete="country-name"
                                    class="w-full appearance-none rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    :required="delivery !== 'pickup'"
                                >
                                    <option value="" @selected(!old('country')) disabled>Select Country</option>
                                    <option @selected(old('country') === 'Slovakia')>Slovakia</option>
                                    <option @selected(old('country') === 'United States')>United States</option>
                                    <option @selected(old('country') === 'Canada')>Canada</option>
                                    <option @selected(old('country') === 'United Kingdom')>United Kingdom</option>
                                    <option @selected(old('country') === 'Germany')>Germany</option>
                                    <option @selected(old('country') === 'France')>France</option>
                                    <option @selected(old('country') === 'Spain')>Spain</option>
                                    <option @selected(old('country') === 'Australia')>Australia</option>
                                </select>
                                @error('country') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Payment --}}
                    <div>
                        <h2 class="mb-4 text-lg font-bold">Payment Method</h2>

                        <div class="rounded-2xl border border-border bg-bg p-4">
                            <div class="flex flex-wrap items-center gap-4 border-b border-border pb-4">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="radio" name="payment" value="card" class="accent-accent" checked />
                                    <span class="font-semibold">Credit Card</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm text-placeholder">
                                    <input type="radio" name="payment" value="paypal" class="accent-accent" />
                                    <span class="font-semibold">PayPal</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm text-placeholder">
                                    <input type="radio" name="payment" value="google-pay" class="accent-accent" />
                                    <span class="font-semibold">Google Pay</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm text-placeholder">
                                    <input type="radio" name="payment" value="apple-pay" class="accent-accent" />
                                    <span class="font-semibold">Apple Pay</span>
                                </label>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-4 tablet:grid-cols-2">
                                <div class="tablet:col-span-2">
                                    <label for="cardNumber" class="mb-1 block text-xs font-semibold text-placeholder">Card Number</label>
                                    <input
                                        id="cardNumber"
                                        name="cardNumber"
                                        type="text"
                                        inputmode="numeric"
                                        autocomplete="cc-number"
                                        placeholder="XXXX XXXX XXXX XXXX"
                                        value="{{ old('cardNumber') }}"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    />
                                    @error('cardNumber') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="expiry" class="mb-1 block text-xs font-semibold text-placeholder">Expiry Date</label>
                                    <input
                                        id="expiry"
                                        name="expiry"
                                        type="text"
                                        inputmode="numeric"
                                        autocomplete="cc-exp"
                                        placeholder="MM/YY"
                                        value="{{ old('expiry') }}"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    />
                                    @error('expiry') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="cvv" class="mb-1 block text-xs font-semibold text-placeholder">CVV</label>
                                    <input
                                        id="cvv"
                                        name="cvv"
                                        type="text"
                                        inputmode="numeric"
                                        autocomplete="cc-csc"
                                        placeholder="XXX"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    />
                                    @error('cvv') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div class="tablet:col-span-2">
                                    <label for="cardName" class="mb-1 block text-xs font-semibold text-placeholder">Name on Card</label>
                                    <input
                                        id="cardName"
                                        name="cardName"
                                        type="text"
                                        autocomplete="cc-name"
                                        placeholder="Name on card"
                                        value="{{ old('cardName') }}"
                                        class="w-full rounded-xl border border-border bg-button px-4 py-3 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-1 focus:ring-accent"
                                    />
                                    @error('cardName') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            {{-- Order Summary --}}
            <aside class="self-start rounded-2xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-6 desktop:sticky desktop:top-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold">Order Summary</h2>
                    <span class="rounded-full border border-border bg-button px-3 py-1 text-xs font-semibold text-placeholder">
                        {{ count($tickets) + count($souvenirs) }} items
                    </span>
                </div>

                <div class="flex flex-col gap-4">
                    @if(count($tickets) > 0)
                    <div>
                        <p class="text-[0.85rem] font-semibold mb-2">Tickets</p>
                        <div class="flex flex-col gap-3">
                            @foreach($tickets as $item)
                            <div class="flex items-center gap-3 rounded-xl border border-border bg-bg p-3">
                                <a href="{{ $item['url'] }}" class="shrink-0 w-14 overflow-hidden rounded-lg">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover aspect-[2/3]" />
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ $item['url'] }}" class="block font-semibold truncate">{{ $item['name'] }} ({{ $item['quantity'] }}×)</a>
                                    <div class="text-xs text-placeholder">{{ $item['schedule'] }}</div>
                                </div>
                                <div class="flex-shrink-0 text-sm font-bold text-accent">{{ number_format($item['subtotal'], 2) }}€</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(count($souvenirs) > 0)
                    <div>
                        <p class="text-[0.85rem] font-semibold mb-2">Souvenirs</p>
                        <div class="flex flex-col gap-3">
                            @foreach($souvenirs as $item)
                            <div class="flex items-center gap-3 rounded-xl border border-border bg-bg p-3">
                                <a href="{{ $item['url'] }}" class="shrink-0 w-14 overflow-hidden rounded-lg">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover aspect-square" />
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ $item['url'] }}" class="block font-semibold truncate">{{ $item['name'] }} × {{ $item['quantity'] }}</a>
                                    <div class="text-xs text-placeholder">{{ $item['description'] }}</div>
                                </div>
                                <div class="flex-shrink-0 text-sm font-bold text-accent">{{ number_format($item['subtotal'], 2) }}€</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-5 rounded-2xl border border-border bg-bg p-4">
                    <div class="flex flex-col gap-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-placeholder">Subtotal</span>
                            <span class="font-medium">{{ number_format($subtotal, 2) }}€</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-placeholder">Shipping</span>
                            <span class="font-medium">{{ number_format($shipping, 2) }}€</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-placeholder">Taxes</span>
                            <span class="font-medium">{{ number_format($taxes, 2) }}€</span>
                        </div>
                        <div class="mt-1 flex justify-between font-bold">
                            <span>Total</span>
                            <span class="text-accent">{{ number_format($grandTotal, 2) }}€</span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        form="checkout-form"
                        class="mt-5 flex w-full items-center justify-center rounded-xl bg-accent px-6 py-3 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.4)] transition hover:brightness-110"
                    >
                        Confirm Purchase
                    </button>
                </div>
            </aside>
        </div>
    </main>

    <x-layout.footer />
</body>
</html>
