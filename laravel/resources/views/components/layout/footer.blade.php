<footer class="mt-12 border-t border-border bg-dark">
  <div class="mx-auto max-w-7xl px-4 tablet:px-6 py-10 tablet:py-14">
    <div class="flex flex-col tablet:flex-row items-start tablet:items-center justify-between gap-8">
      <div class="flex flex-col gap-3">
        <a href="{{ route('home') }}" class="flex items-center rounded-xl bg-accent px-4 py-2 font-semibold w-fit">MovieMall</a>
      </div>
      <nav class="flex gap-6 text-sm">
        <a href="movies.html" class="text-placeholder transition hover:text-accent">Movies</a>
        <a href="souvenirs.html" class="text-placeholder transition hover:text-accent">Souvenirs</a>
        <a href="cart.html" class="text-placeholder transition hover:text-accent">Cart</a>
        <a href="{{ route('profile.edit') }}" class="text-placeholder transition hover:text-accent">Account</a>
      </nav>
    </div>
    <div class="mt-10 border-t border-border pt-6 text-xs text-placeholder">© 2026 MovieMall. All rights reserved.</div>
  </div>
</footer>
