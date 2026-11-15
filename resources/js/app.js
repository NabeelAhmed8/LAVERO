import Alpine from 'alpinejs';
import { animate, inView, scroll } from 'motion';

window.Alpine = Alpine;

// Reactive Global Cart Store
Alpine.store('cart', {
    items: (Array.isArray(window.__LAVERO_CART__) && window.__LAVERO_CART__.length === 0) ? {} : (window.__LAVERO_CART__ || {}),
    open: false,
    loading: false,
    toast: null,
    toastTimeout: null,

    get itemsList() {
        return Object.entries(this.items || {}).map(([k, item]) => ({
            ...item,
            key: item.key || k
        }));
    },

    get count() {
        return Object.values(this.items || {}).reduce((acc, item) => acc + (parseInt(item.quantity) || 0), 0);
    },

    get subtotal() {
        return Object.values(this.items || {}).reduce((acc, item) => acc + ((parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0)), 0);
    },

    get formattedSubtotal() {
        return this.subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },

    get progress() {
        return Math.min(100, (this.subtotal / 500) * 100);
    },

    get remainingForFreeShipping() {
        return Math.max(0, 500 - this.subtotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },

    get shippingFee() {
        return (this.count > 0 && this.subtotal < 500) ? 45 : 0;
    },

    get finalTotal() {
        return (this.subtotal + this.shippingFee).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },

    showToast(message) {
        this.toast = message;
        if (this.toastTimeout) {
            clearTimeout(this.toastTimeout);
        }
        this.toastTimeout = setTimeout(() => {
            this.toast = null;
        }, 3200);
    },

    async add(productId, size, quantity = 1) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ product_id: productId, size: size, quantity: quantity })
            });
            const data = await res.json();
            if (data.success && data.cart) {
                this.items = data.cart;
                this.showToast(data.message || 'Item added to your bag.');
            }
        } catch (e) {
            console.error('Cart add failed:', e);
        } finally {
            this.loading = false;
        }
    },

    pendingUpdates: {},

    async update(key, quantity) {
        const item = this.items?.[key];
        if (!item) return;

        if (quantity < 1) {
            return this.remove(key);
        }

        // Direct instant update: zero debounce, zero latency
        const previousQuantity = item.quantity;
        item.quantity = quantity;
        this.items = { ...this.items };

        if (!this.pendingUpdates) {
            this.pendingUpdates = {};
        }
        this.pendingUpdates[key] = quantity;

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ item_key: key, quantity: quantity })
            });
            const data = await res.json();
            if (data.success && data.cart) {
                // Only sync server response if user hasn't clicked a newer quantity in the meantime
                if (this.pendingUpdates[key] === quantity) {
                    this.items = data.cart;
                    delete this.pendingUpdates[key];
                }
            } else {
                if (this.pendingUpdates[key] === quantity) {
                    item.quantity = previousQuantity;
                    this.items = { ...this.items };
                    delete this.pendingUpdates[key];
                }
            }
        } catch (e) {
            console.error('Cart update failed:', e);
            if (this.pendingUpdates[key] === quantity) {
                item.quantity = previousQuantity;
                this.items = { ...this.items };
                delete this.pendingUpdates[key];
            }
        }
    },

    async remove(key) {
        const previousItem = this.items?.[key];
        if (!previousItem) return;

        if (this.pendingUpdates?.[key]) {
            delete this.pendingUpdates[key];
        }

        // Instant optimistic removal
        const nextItems = { ...this.items };
        delete nextItems[key];
        this.items = nextItems;

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ item_key: key })
            });
            const data = await res.json();
            if (data.success && data.cart) {
                this.items = data.cart;
            } else {
                this.items = { ...this.items, [key]: previousItem };
            }
        } catch (e) {
            console.error('Cart remove failed:', e);
            this.items = { ...this.items, [key]: previousItem };
        }
    }
});

// Expose Motion for inline Blade animation scripts if needed
window.animate = animate;
window.inView = inView;
window.scroll = scroll;

// Global interactive animations on DOM load
document.addEventListener('DOMContentLoaded', () => {
    // 1. Reveal on scroll for elements with [data-motion="fade-up"]
    inView('[data-motion="fade-up"]', ({ target }) => {
        animate(
            target,
            { opacity: [0, 1], y: [40, 0] },
            { duration: 0.85, easing: [0.16, 1, 0.3, 1] }
        );
    });

    // 2. Reveal on scroll for elements with [data-motion="fade-in"]
    inView('[data-motion="fade-in"]', ({ target }) => {
        animate(
            target,
            { opacity: [0, 1] },
            { duration: 0.9, easing: [0.16, 1, 0.3, 1] }
        );
    });

    // 3. Magnetic cursor tracking for buttons with [data-magnetic]
    const magneticElements = document.querySelectorAll('[data-magnetic]');
    magneticElements.forEach((el) => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = (e.clientX - (rect.left + rect.width / 2)) * 0.35;
            const y = (e.clientY - (rect.top + rect.height / 2)) * 0.35;
            animate(el, { x, y }, { duration: 0.2, easing: 'ease-out' });
        });

        el.addEventListener('mouseleave', () => {
            animate(el, { x: 0, y: 0 }, { duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        });
    });
});

Alpine.start();
