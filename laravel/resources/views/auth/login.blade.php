<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>MovieMall</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen bg-bg text-text">
        <header class="border-b border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,0.4)]">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 tablet:px-6">
                <div class="flex items-center gap-3 tablet:gap-6">
                    <a href="{{ route('home') }}" class="flex items-center rounded-xl bg-accent px-4 py-2 font-semibold tablet:text-lg">
                        MovieMall
                    </a>
                    <nav class="hidden gap-2 text-sm tablet:flex">
                        <a href="{{ route('movies.index') }}" class="rounded-lg border border-border bg-button px-4 py-2 transition hover:bg-accent">
                            Movies
                        </a>
                        <a href="{{ route('souvenirs.index') }}" class="rounded-lg border border-border bg-button px-4 py-2 transition hover:bg-accent">
                            Souvenirs
                        </a>
                    </nav>
                </div>

                <div class="flex items-center gap-2 text-xs tablet:gap-4 tablet:text-sm">
                    <span class="hidden text-placeholder tablet:block">Don't have an account?</span>
                    <a
                        href="{{ route('register') }}"
                        class="rounded-lg border border-border bg-button px-3 py-2 font-semibold transition hover:bg-accent focus:bg-accent tablet:px-5"
                    >
                        Register
                    </a>
                </div>
            </div>
        </header>

        <main class="mx-auto flex min-h-[calc(100vh-76px)] max-w-7xl items-center justify-center px-4 py-8 tablet:px-6 tablet:py-14">
            <section
                class="w-full max-w-xl rounded-3xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:max-w-2xl tablet:p-10"
            >
                <h1 class="mb-6 text-2xl font-bold tablet:mb-8 tablet:text-4xl">Log in to your account</h1>

                @if (session('status'))
                    <p class="mb-4 text-sm text-green-400">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mx-auto flex max-w-xl flex-col gap-4 tablet:gap-6">
                    @csrf

                    <div>
                        <label class="sr-only" for="login-email">Email</label>
                        <input
                            id="login-email"
                            name="email"
                            type="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3.5 outline-none focus:border-accent focus:ring-2 focus:ring-accent tablet:px-5 tablet:py-4 tablet:text-lg"
                        />
                        @error('email')
                            <p class="mt-2 text-sm text-accent">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="sr-only" for="login-password">Password</label>
                        <input
                            id="login-password"
                            name="password"
                            type="password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3.5 outline-none focus:border-accent focus:ring-2 focus:ring-accent tablet:px-5 tablet:py-4 tablet:text-lg"
                        />
                        @error('password')
                            <p class="mt-2 text-sm text-accent">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-placeholder">
                            <input
                                id="remember_me"
                                name="remember"
                                type="checkbox"
                                class="rounded border-border bg-button text-accent focus:ring-accent"
                            />
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-placeholder transition hover:text-text">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="mt-2 w-full rounded-xl bg-accent px-6 py-3.5 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:py-4 tablet:text-xl"
                    >
                        Sign In
                    </button>
                </form>
            </section>
        </main>
    </body>
</html>
