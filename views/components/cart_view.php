<?php
// views/components/cart_view.php
// Dedicated cart page view component with items management
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
?>

<div class="cart-view">
    <div class="cart-header">
        <h2>Your Shopping Cart</h2>
        <button id="continue-shopping" class="btn btn-secondary">Continue Shopping</button>
    </div>

    <div id="cart-container" class="cart-container">
        <!-- Cart items and controls will be loaded dynamically via JavaScript -->
        <div class="loading">Loading cart items...</div>
    </div>

    <div id="cart-actions" class="cart-actions" style="display: none;">
        <div class="cart-summary">
            <div class="summary-totals">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="cart-subtotal">₦0.00</span>
                </div>
                <div class="summary-row">
                    <span>Estimated Tax:</span>
                    <span id="cart-tax">₦0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span id="cart-total">₦0.00</span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="<?php echo BASE_URL; ?>/buyer/checkout" class="btn btn-primary btn-lg">
                    Proceed to Checkout
                </a>
                <button id="clear-cart" class="btn btn-danger" onclick="if(confirm('Clear your cart?')) clearCart();">
                    Clear Cart
                </button>
            </div>
        </div>
    </div>

    <div id="empty-cart" class="empty-cart" style="display: none;">
        <p class="empty-message">Your cart is empty</p>
        <a href="<?php echo BASE_URL; ?>/buyer/shop" class="btn btn-primary">
            Start Shopping
        </a>
    </div>
</div>
