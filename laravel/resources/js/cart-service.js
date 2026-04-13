import axios from 'axios';

export const CartService = {
    cartKey: 'moviemall_cart',
    syncPromise: null,

    init() {
        if (window.isLoggedIn) {
            this.syncPromise = this.syncCart();
        }
    },

    getCart() {
        if (!window.isLoggedIn) {
            return JSON.parse(localStorage.getItem(this.cartKey)) || [];
        }
        return [];
    },

    async add(item) {
        if (window.isLoggedIn) {
            try {
                await axios.post('/cart/add', item, {
                    headers: { 'X-CSRF-TOKEN': window.csrfToken }
                });
            } catch (e) {
                console.error(e);
            }
        } else {
            let cart = this.getCart();
            let isOptionsEmpty = (opts) => !opts || (typeof opts === 'object' && Object.keys(opts).length === 0);
            let existing = cart.find(c => {
                if (c.type !== item.type || String(c.reference_id) !== String(item.reference_id)) return false;
                if (item.type === 'ticket') {
                    return c.options && item.options && c.options.date === item.options.date && c.options.time === item.options.time;
                }
                return isOptionsEmpty(c.options) ? isOptionsEmpty(item.options) : JSON.stringify(c.options) === JSON.stringify(item.options);
            });

            if (existing) {
                if (item.type === 'ticket') {
                    let existingSeats = existing.options.seat_ids || [];
                    let newSeats = item.options.seat_ids || [];
                    let merged = Array.from(new Set([...existingSeats, ...newSeats]));
                    existing.options.seat_ids = merged;
                    existing.quantity = merged.length;
                } else {
                    existing.quantity += item.quantity || 1;
                }
            } else {
                cart.push({ ...item, quantity: item.quantity || (item.type === 'ticket' && item.options && item.options.seat_ids ? item.options.seat_ids.length : 1) });
            }
            localStorage.setItem(this.cartKey, JSON.stringify(cart));
        }
    },

    async remove(type, reference_id, options = null) {
        if (window.isLoggedIn) {
            try {
                await axios.post('/cart/remove', { type, reference_id, options }, {
                    headers: { 'X-CSRF-TOKEN': window.csrfToken }
                });
                location.reload();
            } catch (e) {
                console.error(e);
            }
        } else {
            let cart = this.getCart();
            let isOptionsEmpty = (opts) => !opts || (typeof opts === 'object' && Object.keys(opts).length === 0);
            cart = cart.filter(c => !(
                c.type === type &&
                String(c.reference_id) === String(reference_id) &&
                (isOptionsEmpty(c.options) ? isOptionsEmpty(options) : JSON.stringify(c.options) === JSON.stringify(options))
            ));
            localStorage.setItem(this.cartKey, JSON.stringify(cart));
            location.reload();
        }
    },

    async syncCart() {
        let cart = JSON.parse(localStorage.getItem(this.cartKey));
        if (cart && cart.length > 0) {
            try {
                await axios.post('/cart/sync', { items: cart }, {
                    headers: { 'X-CSRF-TOKEN': window.csrfToken }
                });
                localStorage.removeItem(this.cartKey);
            } catch (e) {
                console.error(e);
            }
        }
    }
};

window.CartService = CartService;
CartService.init();
