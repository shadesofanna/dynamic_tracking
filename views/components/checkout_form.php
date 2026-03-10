<?php
// views/components/checkout_form.php
// Reusable checkout form component for order placement
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
?>

<div class="checkout-form">
    <h2>Shipping & Payment</h2>
    <form id="checkout-form">
        <fieldset>
            <legend>Shipping Information</legend>
            
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input 
                    type="text" 
                    id="full_name" 
                    name="full_name" 
                    placeholder="Enter your full name"
                    required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    placeholder="Enter your phone number"
                    required>
            </div>

            <div class="form-group">
                <label for="street">Street Address *</label>
                <input 
                    type="text" 
                    id="street" 
                    name="street" 
                    placeholder="Enter street address"
                    required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City *</label>
                    <input 
                        type="text" 
                        id="city" 
                        name="city" 
                        placeholder="Enter city"
                        required>
                </div>

                <div class="form-group">
                    <label for="state">State/Province *</label>
                    <input 
                        type="text" 
                        id="state" 
                        name="state" 
                        placeholder="Enter state"
                        required>
                </div>

                <div class="form-group">
                    <label for="zip">ZIP/Postal Code *</label>
                    <input 
                        type="text" 
                        id="zip" 
                        name="zip" 
                        placeholder="Enter ZIP code"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label for="country">Country *</label>
                <input 
                    type="text" 
                    id="country" 
                    name="country" 
                    placeholder="Enter country"
                    required>
            </div>
        </fieldset>

        <fieldset>
            <legend>Order Notes</legend>
            
            <div class="form-group">
                <label for="notes">Special Instructions (Optional)</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="3" 
                    placeholder="Add any special instructions for your order..."></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Terms & Conditions</legend>
            
            <div class="form-group checkbox">
                <label>
                    <input type="checkbox" id="agree-terms" name="agree-terms" required>
                    <span>I agree to the terms and conditions</span>
                </label>
            </div>
        </fieldset>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                Place Order
            </button>
            <a href="<?php echo BASE_URL; ?>/buyer/cart" class="btn btn-secondary">Back to Cart</a>
        </div>
    </form>
</div>
