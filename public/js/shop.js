document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    initSearchAutocomplete();
});

/**
 * Update Cart Count in Header
 */
async function updateCartCount() {
    try {
        const response = await fetch('api/cart-count.php');
        const result = await response.json();
        if (result.success) {
            const counter = document.getElementById('cart-counter');
            if (counter) {
                counter.textContent = result.data.count;
                counter.style.display = result.data.count > 0 ? 'inline-block' : 'none';
            }
        }
    } catch (e) {
        console.error('Failed to update cart count', e);
    }
}

/**
 * AJAX Add to Cart
 */
async function addToCart(productId, quantity = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const response = await fetch('api/cart-add.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            updateCartCount();
            showNotification('Product added to cart!', 'success');
        } else {
            showNotification(result.error || 'Failed to add product', 'error');
        }
    } catch (e) {
        showNotification('An error occurred.', 'error');
    }
}

/**
 * Quantity Control in Cart Page
 */
async function updateQuantity(cartItemId, newQty) {
    if (newQty < 1) return;

    const formData = new FormData();
    formData.append('id', cartItemId);
    formData.append('quantity', newQty);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const response = await fetch('api/cart-update.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            location.reload(); // Simple refresh for now to update totals
        } else {
            alert(result.error);
        }
    } catch (e) {
        console.error(e);
    }
}

/**
 * Search Autocomplete
 */
function initSearchAutocomplete() {
    const searchInput = document.getElementById('search-input');
    const suggestBox = document.getElementById('search-suggestions');
    if (!searchInput || !suggestBox) return;

    searchInput.addEventListener('input', async (e) => {
        const query = e.target.value;
        if (query.length < 2) {
            suggestBox.style.display = 'none';
            return;
        }

        const response = await fetch(`api/search-suggest.php?q=${encodeURIComponent(query)}`);
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            suggestBox.innerHTML = result.data.map(p => `
                <a href="product.php?id=${p.id}" class="suggestion-item">
                    ${p.name}
                </a>
            `).join('');
            suggestBox.style.display = 'block';
        } else {
            suggestBox.style.display = 'none';
        }
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target)) suggestBox.style.display = 'none';
    });
}

/**
 * UI Notification
 */
function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
