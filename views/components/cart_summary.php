<?php
// views/components/cart_summary.php
// Reusable cart summary component showing items and totals
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
?>

<div class="cart-summary">
    <h2>Order Summary</h2>
    <div id="order-items" class="cart-items-list">
        <p class="loading">Loading cart items...</p>
    </div>
    
    <div class="summary-totals">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span id="subtotal">₦0.00</span>
        </div>
        <div class="summary-row">
            <span>Tax (estimated):</span>
            <span id="tax">₦0.00</span>
        </div>
        <div class="summary-row total">
            <span>Total:</span>
            <span id="total">₦0.00</span>
        </div>
    </div>
</div>
