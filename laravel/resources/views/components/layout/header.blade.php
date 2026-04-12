<script>
    window.csrfToken = "{{ csrf_token() }}";
    window.isLoggedIn = @json(auth()->check());
</script>
<header class="border-b border-border bg-dark shadow-[0_14px_36px_rgba(0,0,0,.4)]">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 tablet:px-6">
    <div class="flex items-center gap-3 tablet:gap-6">
      <a href="{{ route('home') }}" class="flex items-center rounded-xl bg-accent px-4 py-2 tablet:text-lg font-semibold">MovieMall</a>
      <nav class="tablet:flex hidden gap-2 text-sm">
        <a href="{{ route('movies.index') }}" class="rounded-lg border border-border bg-button px-4 py-2 transition hover:bg-accent">Movies</a>
        <a href="{{ route('souvenirs.index') }}" class="rounded-lg border border-border bg-button px-4 py-2 transition hover:bg-accent">Souvenirs</a>
      </nav>
    </div>
    <div class="flex items-center gap-2 tablet:gap-3 text-xs tablet:text-sm">
      <input
        type="search"
        placeholder="Search..."
        class="hidden tablet:block rounded-lg border border-border bg-button px-3 py-2 text-sm placeholder:text-placeholder outline-none focus:border-accent focus:ring-1 focus:ring-accent/30 w-44"
      />
      <a href="{{ route('cart.index') }}" class="rounded-lg border border-border bg-button px-3 tablet:px-4 py-2 transition hover:bg-accent">Cart</a>
      @auth
        <a href="{{ route('profile.edit') }}" class="rounded-lg border border-border bg-button px-3 tablet:px-4 py-2 transition hover:bg-accent">Profile</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="rounded-lg border border-border bg-button px-3 tablet:px-4 py-2 transition hover:bg-accent">Log Out</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="rounded-lg border border-border bg-button px-3 tablet:px-4 py-2 transition hover:bg-accent">Log In</a>
        <a href="{{ route('register') }}" class="rounded-lg border border-border bg-button px-3 tablet:px-4 py-2 transition hover:bg-accent">Register</a>
      @endauth
    </div>
  </div>
</header>
