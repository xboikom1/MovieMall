<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Schedule — Admin — MovieMall</title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
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
                <a href="{{ route('admin.schedule') }}" class="rounded-lg border border-accent bg-accent/10 px-3 py-2 text-sm font-semibold text-accent">Schedule</a>
                <a href="{{ route('admin.orders') }}" class="rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent">Orders</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 rounded-lg border border-border bg-button px-3 py-2 text-sm transition hover:border-accent hover:text-accent">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 tablet:px-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl border border-green-500/30 bg-green-900/30 px-4 py-3 text-sm text-green-200">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-6 desktop:grid-cols-3">

            {{-- Add screening form --}}
            <div class="desktop:col-span-1">
                <div class="rounded-2xl border border-border bg-dark p-6 shadow-[0_14px_36px_rgba(0,0,0,.35)]">
                    <h2 class="mb-5 text-lg font-bold">Add Screening</h2>

                    <form method="POST" action="{{ route('admin.schedule.store', array_filter(['movie_id' => $movieFilter])) }}" class="flex flex-col gap-4">
                        @csrf

                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-widest text-placeholder">Movie</label>
                            <select name="movie_id" required
                                class="w-full rounded-xl border border-border bg-button px-3 py-2.5 text-sm focus:border-accent focus:outline-none">
                                <option value="">— select movie —</option>
                                @foreach ($movies as $m)
                                    <option value="{{ $m->id }}" {{ old('movie_id', $movieFilter) == $m->id ? 'selected' : '' }}>
                                        {{ $m->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('movie_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-widest text-placeholder">Hall</label>
                            <select name="hall_id" required
                                class="w-full rounded-xl border border-border bg-button px-3 py-2.5 text-sm focus:border-accent focus:outline-none">
                                <option value="">— select hall —</option>
                                @foreach ($halls as $h)
                                    <option value="{{ $h->id }}" {{ old('hall_id') == $h->id ? 'selected' : '' }}>
                                        {{ $h->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hall_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-widest text-placeholder">Date & Time</label>
                            <input
                                type="datetime-local"
                                name="starts_at"
                                value="{{ old('starts_at') }}"
                                required
                                class="w-full rounded-xl border border-border bg-button px-3 py-2.5 text-sm focus:border-accent focus:outline-none [color-scheme:dark]"
                            />
                            @error('starts_at') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                            class="mt-1 w-full rounded-xl bg-accent py-3 text-sm font-semibold transition hover:brightness-110">
                            Add Screening
                        </button>
                    </form>
                </div>
            </div>

            {{-- Screenings table --}}
            <div class="desktop:col-span-2">
                <div class="rounded-2xl border border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.35)] overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                        <h2 class="font-bold text-lg">Screenings</h2>
                        <form method="GET" action="{{ route('admin.schedule') }}" class="flex gap-2">
                            <select name="movie_id" onchange="this.form.submit()"
                                class="rounded-xl border border-border bg-button px-3 py-1.5 text-xs focus:border-accent focus:outline-none">
                                <option value="">All movies</option>
                                @foreach ($movies as $m)
                                    <option value="{{ $m->id }}" {{ $movieFilter == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    @if ($slots->isEmpty())
                        <p class="px-5 py-8 text-center text-sm text-placeholder">No screenings found.</p>
                    @else
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-border text-xs uppercase tracking-widest text-placeholder">
                                    <th class="px-5 py-3 text-left">Movie</th>
                                    <th class="px-5 py-3 text-left">Hall</th>
                                    <th class="px-5 py-3 text-left">Date</th>
                                    <th class="px-5 py-3 text-left">Time</th>
                                    <th class="px-5 py-3 text-left">Ends</th>
                                    <th class="px-5 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($slots as $slot)
                                    <tr class="border-b border-border last:border-0 hover:bg-button/50 transition">
                                        <td class="px-5 py-3 font-semibold">{{ $slot->movie_title }}</td>
                                        <td class="px-5 py-3 text-placeholder">{{ $slot->hall_name }}</td>
                                        <td class="px-5 py-3">{{ \Carbon\Carbon::parse($slot->starts_at)->format('M j, Y') }}</td>
                                        <td class="px-5 py-3">{{ \Carbon\Carbon::parse($slot->starts_at)->format('H:i') }}</td>
                                        <td class="px-5 py-3 text-placeholder">{{ \Carbon\Carbon::parse($slot->ends_at)->format('H:i') }}</td>
                                        <td class="px-5 py-3 text-right">
                                            <form method="POST" action="{{ route('admin.schedule.destroy', $slot->id) }}"
                                                onsubmit="return confirm('Delete this screening?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg border border-red-500/40 bg-red-900/20 px-3 py-1 text-xs text-red-400 transition hover:bg-red-900/40">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($slots->hasPages())
                            <div class="border-t border-border px-5 py-3">
                                {{ $slots->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>
