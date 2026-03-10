// public/assets/js/buyer/checkout.js

class Checkout {
    static cartItems = [];
    static TAX_RATE = 0.10; // 10% tax
    
    static async init() {
        // Get cart items from localStorage
        const cartData = localStorage.getItem('cart');
        if (!cartData) {
            document.getElementById('order-items').innerHTML = 
                '<p class="empty-message">Your cart is empty</p>';
            return;
        }
        
        try {
            this.cartItems = JSON.parse(cartData);
            await this.displayOrderSummary();
            this.attachFormListener();
        } catch (error) {
            Toast.show('Error loading cart', 'error');
            console.error('Cart loading error:', error);
        }
    }
    
    static async displayOrderSummary() {
        if (this.cartItems.length === 0) {
            document.getElementById('order-items').innerHTML = 
                '<p class="empty-message">Your cart is empty</p>';
            return;
        }
        
        // Validate and fetch current product prices
        try {
            const validatedCart = await this.validateCart();
            
            let html = '<div class="items-list">';
            let subtotal = 0;
            
            for (const item of validatedCart) {
                const itemTotal = item.current_price * item.quantity;
                subtotal += itemTotal;
                
                html += `
                    <div class="checkout-item">
                        <div class="item-info">
                            <h4>${this.escapeHtml(item.name)}</h4>
                            <p class="seller-name">${this.escapeHtml(item.seller_name || 'Unknown Seller')}</p>
                            <p class="item-sku">SKU: ${this.escapeHtml(item.sku || 'N/A')}</p>
                        </div>
                        <div class="item-price">
                            <span class="price">₦${parseFloat(item.current_price).toFixed(2)} × ${item.quantity}</span>
                            <span class="total">₦${itemTotal.toFixed(2)}</span>
                        </div>
                    </div>
                `;
            }
            
            html += '</div>';
            
            // Calculate totals
            const tax = subtotal * this.TAX_RATE;
            const total = subtotal + tax;
            
            document.getElementById('order-items').innerHTML = html;
            document.getElementById('subtotal').textContent = '₦' + subtotal.toFixed(2);
            document.getElementById('tax').textContent = '₦' + tax.toFixed(2);
            document.getElementById('total').textContent = '₦' + total.toFixed(2);
            
            // Store validated items for submission
            this.cartItems = validatedCart;
            
        } catch (error) {
            Toast.show('Error validating cart items', 'error');
            console.error('Cart validation error:', error);
            // Display items anyway without validation
            this.displayCartAsIs();
        }
    }
    
    static displayCartAsIs() {
        // Display cart items without validation
        let html = '<div class="items-list">';
        let subtotal = 0;
        
        for (const item of this.cartItems) {
            const price = item.current_price || item.price || 0;
            const itemTotal = price * item.quantity;
            subtotal += itemTotal;
            
            html += `
                <div class="checkout-item">
                    <div class="item-info">
                        <h4>${this.escapeHtml(item.name)}</h4>
                        <p class="seller-name">${this.escapeHtml(item.seller_name || 'Unknown Seller')}</p>
                    </div>
                    <div class="item-price">
                        <span class="price">₦${parseFloat(price).toFixed(2)} × ${item.quantity}</span>
                        <span class="total">₦${itemTotal.toFixed(2)}</span>
                    </div>
                </div>
            `;
        }
        
        html += '</div>';
        
        const tax = subtotal * this.TAX_RATE;
        const total = subtotal + tax;
        
        document.getElementById('order-items').innerHTML = html;
        document.getElementById('subtotal').textContent = '₦' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '₦' + tax.toFixed(2);
        document.getElementById('total').textContent = '₦' + total.toFixed(2);
    }
    
    static async validateCart() {
        try {
            const baseUrl = this.getBaseUrl();
            const validateUrl = baseUrl ? `${baseUrl}/cart/validate` : '/cart/validate';
            
            console.log('Validating cart at:', validateUrl);
            
            const response = await fetch(
                validateUrl,
                {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ items: this.cartItems })
                }
            );
            
            console.log('Validation response status:', response.status);
            
            if (!response.ok) {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (data.errors && data.errors.length > 0) {
                        data.errors.forEach(error => Toast.show(error, 'error'));
                    }
                } else {
                    console.error('Server returned non-JSON response');
                    throw new Error('Cart validation failed - server error');
                }
                throw new Error('Cart validation failed');
            }
            
            const data = await response.json();
            console.log('Validated cart:', data);
            return data.data || [];
            
        } catch (error) {
            console.error('Validation error:', error);
            throw error;
        }
    }
    
    static attachFormListener() {
        const form = document.getElementById('checkout-form');
        if (!form) return;
        
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitOrder();
        });
    }
    
    static async submitOrder() {
        const form = document.getElementById('checkout-form');
        if (!form) return;
        
        // Validate form
        if (!form.checkValidity()) {
            Toast.show('Please fill in all required fields', 'warning');
            return;
        }
        
        if (this.cartItems.length === 0) {
            Toast.show('Your cart is empty', 'warning');
            return;
        }
        
        // Ensure each item has product_id
        const itemsForOrder = this.cartItems.map(item => ({
            product_id: item.product_id || item.id,
            quantity: item.quantity,
            price: item.current_price || item.price
        }));
        
        const formData = new FormData(form);
        const orderData = {
            items: itemsForOrder,
            shipping_address: formData.get('shipping_address'),
            notes: formData.get('notes')
        };
        
        console.log('Submitting order with data:', orderData);
        
        // Disable submit button
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        try {
            // Use the global BASE_URL from app.js (already extracted as a path)
            const ordersUrl = BASE_URL + '/orders';
            
            console.log('Submitting to:', ordersUrl);
            console.log('BASE_URL:', BASE_URL);
            
            const response = await fetch(
                ordersUrl,
                {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(orderData)
                }
            );
            
            const contentType = response.headers.get('content-type');
            let data;
            
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                console.error('Server response (non-JSON):', text);
                console.error('Content-Type:', contentType);
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 100));
            }
            
            if (!response.ok) {
                throw new Error(data.error || data.message || 'Failed to place order');
            }
            
            Toast.show('Order placed successfully!', 'success');
            
            // Clear cart
            localStorage.removeItem('cart');
            
            // Redirect to orders page
            setTimeout(() => {
                window.location.href = BASE_URL + '/buyer/orders';
            }, 1500);
            
        } catch (error) {
            Toast.show(error.message || 'Failed to place order', 'error');
            console.error('Order submission error:', error);
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Place Order';
        }
    }
    
    static getBaseUrl() {
        // Return the global BASE_URL which has been properly extracted by app.js
        return BASE_URL || '/';
    }
    
    static escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
}
