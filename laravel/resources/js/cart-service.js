import axios from 'axios';

export const CartService = {
    cartKey: 'moviemall_cart',

    init() {
        if (window.isLoggedIn) {
            this.syncCart();
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
            let existing = cart.find(c => c.type === item.type && c.reference_id === item.reference_id && JSON.stringify(c.options) === JSON.stringify(item.options));
            if (existing) {
                existing.quantity += item.quantity || 1;
            } else {
                cart.push({ ...item, quantity: item.quantity || 1 });
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
            cart = cart.filter(c => !(c.type === type && c.reference_id === reference_id && JSON.stringify(c.options) === JSON.stringify(options)));
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
