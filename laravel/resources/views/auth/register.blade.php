<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{MovieMall</title>

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
                    <span class="hidden text-placeholder tablet:block">Already have an account?</span>
                    <a
                        href="{{ route('login') }}"
                        class="rounded-lg border border-border bg-button px-3 py-2 font-semibold transition hover:bg-accent focus:bg-accent tablet:px-5"
                    >
                        Sign In
                    </a>
                </div>
            </div>
        </header>

        <main class="mx-auto flex min-h-[calc(100vh-76px)] max-w-7xl items-center justify-center px-4 py-8 tablet:px-6 tablet:py-14">
            <section
                class="w-full max-w-xl rounded-3xl border border-border bg-dark p-5 shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:max-w-2xl tablet:p-10"
            >
                <h1 class="mb-6 text-2xl font-bold tablet:mb-8 tablet:text-4xl">Create your account</h1>

                <form method="POST" action="{{ route('register') }}" class="mx-auto flex max-w-xl flex-col gap-4 tablet:gap-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 tablet:grid-cols-2">
                        <div>
                            <label class="sr-only" for="register-first-name">First Name</label>
                            <input
                                id="register-first-name"
                                name="first_name"
                                type="text"
                                placeholder="First Name"
                                value="{{ old('first_name') }}"
                                required
                                autocomplete="given-name"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3.5 outline-none focus:border-accent focus:ring-2 focus:ring-accent tablet:px-5 tablet:py-4 tablet:text-lg"
                            />
                            @error('first_name')
                                <p class="mt-2 text-sm text-accent">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="sr-only" for="register-last-name">Last Name</label>
                            <input
                                id="register-last-name"
                                name="last_name"
                                type="text"
                                placeholder="Last Name"
                                value="{{ old('last_name') }}"
                                required
                                autocomplete="family-name"
                                class="w-full rounded-xl border border-border bg-button px-4 py-3.5 outline-none focus:border-accent focus:ring-2 focus:ring-accent tablet:px-5 tablet:py-4 tablet:text-lg"
                            />
                            @error('last_name')
                                <p class="mt-2 text-sm text-accent">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="sr-only" for="register-email">Email</label>
                        <input
                            id="register-email"
                            name="email"
                            type="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3.5 outline-none focus:border-accent focus:ring-2 focus:ring-accent tablet:px-5 tablet:py-4 tablet:text-lg"
                        />
                        @error('email')
                            <p class="mt-2 text-sm text-accent">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="sr-only" for="register-password">Password</label>
                        <input
                            id="register-password"
                            name="password"
                            type="password"
                            placeholder="Password"
                            required
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3.5 outline-none focus:border-accent focus:ring-2 focus:ring-accent tablet:px-5 tablet:py-4 tablet:text-lg"
                        />
                        @error('password')
                            <p class="mt-2 text-sm text-accent">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="sr-only" for="register-password-confirm">Confirm Password</label>
                        <input
                            id="register-password-confirm"
                            name="password_confirmation"
                            type="password"
                            placeholder="Confirm Password"
                            required
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-border bg-button px-4 py-3.5 outline-none focus:border-accent focus:ring-2 focus:ring-accent tablet:px-5 tablet:py-4 tablet:text-lg"
                        />
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-accent">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="mt-2 w-full rounded-xl bg-accent px-6 py-3.5 font-semibold shadow-[0_14px_36px_rgba(0,0,0,.4)] tablet:py-4 tablet:text-xl"
                    >
                        Register
                    </button>
                </form>
            </section>
        </main>
    </body>
</html>
