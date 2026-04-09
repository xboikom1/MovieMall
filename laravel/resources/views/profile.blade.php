<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="min-h-screen bg-bg text-text">
    <x-layout.header />

    <main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6 tablet:py-12">
      <header class="mb-6 flex flex-col gap-2">
        <h1 class="text-2xl font-bold tablet:text-3xl">Profile</h1>
        <p class="text-sm text-placeholder">Manage your personal details and avatar.</p>
      </header>

      {{-- Flash messages --}}
      @if (session('status') === 'profile-updated')
        <div class="mb-6 rounded-xl border border-green-600 bg-green-800/30 px-4 py-3 text-sm text-green-300">
          Profile updated successfully.
        </div>
      @elseif (session('status') === 'address-added')
        <div class="mb-6 rounded-xl border border-green-600 bg-green-800/30 px-4 py-3 text-sm text-green-300">
          Address added successfully.
        </div>
      @elseif (session('status') === 'address-deleted')
        <div class="mb-6 rounded-xl border border-green-600 bg-green-800/30 px-4 py-3 text-sm text-green-300">
          Address removed.
        </div>
      @endif

      {{-- Profile card --}}
      <section class="rounded-3xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-10">
        <div class="grid grid-cols-1 gap-8 desktop:grid-cols-[320px_1fr] desktop:gap-10">

          {{-- Avatar --}}
          <aside class="flex flex-col items-center gap-4">
            <div class="grid h-44 w-44 place-items-center rounded-full border border-border bg-button text-center shadow-[0_14px_36px_rgba(0,0,0,.35)]">
              @if ($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="h-full w-full rounded-full object-cover" />
              @else
                <span class="text-4xl font-bold uppercase">
                  {{ mb_substr($user->first_name, 0, 1) }}{{ mb_substr($user->last_name, 0, 1) }}
                </span>
              @endif
            </div>

            <div class="mt-1 flex flex-wrap items-center justify-center gap-3">
              <label class="cursor-pointer rounded-xl border border-border bg-bg px-4 py-2 text-sm font-semibold transition hover:border-accent hover:text-accent">
                Upload New
                <input type="file" class="sr-only" accept="image/*" />
              </label>
              <button type="button"
                class="rounded-xl border border-border bg-bg px-4 py-2 text-sm font-semibold transition hover:border-accent hover:text-accent">
                Delete Avatar
              </button>
            </div>
          </aside>

          {{-- Profile form --}}
          <div class="flex flex-col gap-6">
            <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 gap-5">
              @csrf
              @method('PATCH')

              <div class="grid grid-cols-1 gap-5 tablet:grid-cols-2">
                <div>
                  <label for="first_name" class="mb-2 block text-xs font-semibold uppercase tracking-widest">First Name</label>
                  <input
                    id="first_name" name="first_name" type="text"
                    value="{{ old('first_name', $user->first_name) }}"
                    class="w-full rounded-xl border bg-bg px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('first_name') ? 'border-red-500 focus:border-red-500' : 'border-border focus:border-accent' }}"
                  />
                  @error('first_name')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="last_name" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Last Name</label>
                  <input
                    id="last_name" name="last_name" type="text"
                    value="{{ old('last_name', $user->last_name) }}"
                    class="w-full rounded-xl border bg-bg px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('last_name') ? 'border-red-500 focus:border-red-500' : 'border-border focus:border-accent' }}"
                  />
                  @error('last_name')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div>
                <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Email</label>
                <input
                  id="email" name="email" type="email"
                  value="{{ old('email', $user->email) }}"
                  class="w-full rounded-xl border bg-bg px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('email') ? 'border-red-500 focus:border-red-500' : 'border-border focus:border-accent' }}"
                />
                @error('email')
                  <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="phone_number" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Phone Number</label>
                <input
                  id="phone_number" name="phone_number" type="tel" inputmode="tel"
                  value="{{ old('phone_number', $user->phone_number) }}"
                  class="w-full rounded-xl border bg-bg px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('phone_number') ? 'border-red-500 focus:border-red-500' : 'border-border focus:border-accent' }}"
                />
                @error('phone_number')
                  <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="date_of_birth" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Date of Birth</label>
                <input
                  id="date_of_birth" name="date_of_birth" type="date"
                  value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                  class="w-full rounded-xl border bg-bg px-4 py-3.5 text-sm outline-none focus:ring-2 focus:ring-accent {{ $errors->has('date_of_birth') ? 'border-red-500 focus:border-red-500' : 'border-border focus:border-accent' }}"
                />
                @error('date_of_birth')
                  <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
              </div>

              <div class="pt-2">
                <button type="submit"
                  class="mx-auto mt-4 block w-full max-w-md rounded-xl bg-accent px-6 py-3.5 font-semibold text-white shadow-[0_14px_36px_rgba(0,0,0,.4)] transition hover:brightness-110 tablet:text-lg">
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>

      {{-- Delivery Addresses --}}
      <section class="mt-8 rounded-3xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:p-10">
        <h2 class="mb-6 text-xl font-bold">Saved Delivery Options</h2>

        {{-- Add address form --}}
        <details class="mb-6" {{ $errors->hasAny(['street', 'city', 'postal_code', 'country']) ? 'open' : '' }}>
          <summary class="mb-4 w-fit cursor-pointer list-none rounded-xl border border-border bg-bg px-4 py-2 text-sm font-semibold transition hover:border-accent hover:text-accent">
            + Add New
          </summary>

          <div class="rounded-2xl border border-border bg-bg p-5">
            <h3 class="mb-4 text-sm font-semibold uppercase tracking-widest text-placeholder">New Address</h3>
            <form method="POST" action="{{ route('profile.address.store') }}" class="grid grid-cols-1 gap-4">
              @csrf

              <div class="grid grid-cols-1 gap-4 tablet:grid-cols-2">
                <div>
                  <label for="street" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Street</label>
                  <input id="street" name="street" type="text" value="{{ old('street') }}"
                    class="w-full rounded-xl border bg-button px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('street') ? 'border-red-500' : 'border-border focus:border-accent' }}" />
                  @error('street') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label for="building_number" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Building / Apt</label>
                  <input id="building_number" name="building_number" type="text" value="{{ old('building_number') }}"
                    class="w-full rounded-xl border border-border bg-button px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:border-accent focus:ring-2 focus:ring-accent" />
                </div>
              </div>

              <div class="grid grid-cols-1 gap-4 tablet:grid-cols-3">
                <div>
                  <label for="city" class="mb-2 block text-xs font-semibold uppercase tracking-widest">City</label>
                  <input id="city" name="city" type="text" value="{{ old('city') }}"
                    class="w-full rounded-xl border bg-button px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('city') ? 'border-red-500' : 'border-border focus:border-accent' }}" />
                  @error('city') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label for="postal_code" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Postal Code</label>
                  <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}"
                    class="w-full rounded-xl border bg-button px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('postal_code') ? 'border-red-500' : 'border-border focus:border-accent' }}" />
                  @error('postal_code') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label for="country" class="mb-2 block text-xs font-semibold uppercase tracking-widest">Country</label>
                  <input id="country" name="country" type="text" value="{{ old('country') }}"
                    class="w-full rounded-xl border bg-button px-4 py-3.5 text-sm outline-none placeholder:text-placeholder focus:ring-2 focus:ring-accent {{ $errors->has('country') ? 'border-red-500' : 'border-border focus:border-accent' }}" />
                  @error('country') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
              </div>

              <div class="flex items-center gap-2">
                <input id="is_default" name="is_default" type="checkbox" value="1"
                  {{ old('is_default') ? 'checked' : '' }}
                  class="h-4 w-4 rounded border-border accent-accent" />
                <label for="is_default" class="text-sm text-placeholder">Set as default address</label>
              </div>

              <div class="flex justify-end pt-1">
                <button type="submit"
                  class="rounded-xl bg-accent px-5 py-2.5 text-sm font-semibold text-white shadow-[0_14px_36px_rgba(0,0,0,.4)] transition hover:brightness-110">
                  Add Address
                </button>
              </div>
            </form>
          </div>
        </details>

        {{-- Address list --}}
        @if ($addresses->isEmpty())
          <p class="text-sm text-placeholder">No addresses saved yet. Add one above.</p>
        @else
          <ul class="flex flex-col gap-4">
            @foreach ($addresses as $address)
              <li class="flex items-start justify-between gap-4 rounded-2xl border border-border bg-bg p-4">
                <div class="flex items-start gap-4">
                  <div>
                    <div class="flex items-center gap-2">
                      <p class="text-sm font-semibold">
                        {{ $address->street }}{{ $address->building_number ? ', ' . $address->building_number : '' }}
                      </p>
                      @if ($address->is_default)
                        <span class="rounded-full bg-accent/10 px-2 py-0.5 text-xs font-semibold text-accent">Default</span>
                      @endif
                    </div>
                    <p class="mt-0.5 text-xs text-placeholder">
                      {{ $address->city }}, {{ $address->postal_code }}, {{ $address->country }}
                    </p>
                  </div>
                </div>
                <div class="flex shrink-0 gap-2">
                  <form method="POST" action="{{ route('profile.address.destroy', $address) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="rounded-lg border border-border bg-button px-3 py-1.5 text-xs font-semibold transition hover:border-accent hover:text-accent">
                      Delete
                    </button>
                  </form>
                </div>
              </li>
            @endforeach
          </ul>
        @endif
      </section>
    </main>

    <x-layout.footer />
  </body>
</html>
