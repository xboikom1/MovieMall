// Sync session cart → DB when user is logged in (runs once per page load)
window.cartSyncPromise = window.isLoggedIn
    ? axios.post('/cart/sync', {}, { headers: { 'X-CSRF-TOKEN': window.csrfToken } }).catch(console.error)
    : Promise.resolve();
