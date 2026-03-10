<?php
// views/buyer/cart.php
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?php echo BASE_URL; ?>">
    <title>Shopping Cart - Dynamic Pricing</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/main.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/buyer.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/toast.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            max-width: 1200px;
            margin: 40px auto;
            width: 100%;
            padding: 0 20px;
        }

        /* Cart Hero Section */
        .cart-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            animation: slideDown 0.6s ease-out;
        }

        .cart-hero h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .cart-hero p {
            font-size: 1.1em;
            opacity: 0.95;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Main Cart Layout */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            margin-bottom: 40px;
        }

        /* Cart Items Section */
        .cart-items-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.6s ease-out 0.2s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-items-section h2 {
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            font-size: 1.5em;
        }

        .cart-items-section h2::before {
            content: '🛒';
            margin-right: 10px;
            font-size: 1.4em;
        }

        #cart-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Cart Item */
        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr 100px;
            gap: 20px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: #f9f9f9;
            transition: all 0.3s ease;
            animation: fadeIn 0.4s ease-out;
        }

        .cart-item:hover {
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
            background: white;
        }

        .cart-item-image {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            min-height: 100px;
            color: white;
            font-size: 2em;
            font-weight: bold;
        }

        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-item-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-name {
            font-size: 1.1em;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .item-seller {
            font-size: 0.85em;
            color: #888;
            margin-bottom: 8px;
        }

        .item-seller::before {
            content: '👤 ';
        }

        .item-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e0e0e0;
        }

        .item-price {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.2em;
            font-weight: 700;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px;
        }

        .qty-btn {
            background: #667eea;
            color: white;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: #764ba2;
            transform: scale(1.1);
        }

        .qty-input {
            width: 40px;
            text-align: center;
            border: none;
            font-weight: 600;
            font-size: 0.95em;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .remove-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
            transition: all 0.3s ease;
            width: 100%;
        }

        .remove-btn:hover {
            background: #ff5252;
            transform: scale(1.05);
        }

        /* Cart Summary Sidebar */
        .cart-summary-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 120px;
            animation: fadeIn 0.6s ease-out 0.3s both;
        }

        .cart-summary-section h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3em;
            display: flex;
            align-items: center;
        }

        .cart-summary-section h3::before {
            content: '📋';
            margin-right: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 0.95em;
            color: #666;
        }

        .summary-row.total {
            border-bottom: none;
            border-top: 2px solid #667eea;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 1.2em;
            font-weight: 700;
            color: #333;
        }

        .summary-row.total span:last-child {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #ddd;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
            border-color: #667eea;
        }

        .btn-danger {
            background: #ff6b6b;
            color: white;
        }

        .btn-danger:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }

        /* Empty Cart State */
        #empty-cart {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-state-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .empty-message {
            font-size: 1.3em;
            color: #666;
            margin-bottom: 30px;
        }

        .empty-message strong {
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        /* Loading State */
        .loading {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }

            .cart-summary-section {
                position: static;
                top: auto;
            }

            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }

            .item-actions {
                grid-column: 2;
            }

            .cart-hero {
                padding: 40px 20px;
            }

            .cart-hero h1 {
                font-size: 1.8em;
            }

            .container {
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../views/layouts/buyer_nav.php'; ?>
    
    <div class="container">
        <div class="cart-hero">
            <h1>🛒 Your Shopping Cart</h1>
            <p>Review your items and proceed to checkout</p>
        </div>

        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items-section">
                <h2>Cart Items</h2>
                <div id="cart-container">
                    <div class="loading">Loading cart items...</div>
                </div>
                
                <!-- Empty Cart Message -->
                <div id="empty-cart" style="display: none;">
                    <div class="empty-state-icon">🛍️</div>
                    <p class="empty-message"><strong>Your cart is empty</strong></p>
                    <a href="<?php echo BASE_URL; ?>/buyer/shop" class="btn btn-primary">
                        Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary-section" id="cart-actions" style="display: none;">
                <h3>Order Summary</h3>
                
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="cart-subtotal">₦0.00</span>
                </div>
                <div class="summary-row">
                    <span>Estimated Tax:</span>
                    <span id="cart-tax">₦0.00</span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span id="cart-shipping">₦0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span id="cart-total">₦0.00</span>
                </div>

                <div class="action-buttons">
                    <a href="<?php echo BASE_URL; ?>/buyer/checkout" class="btn btn-primary">
                        ✓ Proceed to Checkout
                    </a>
                    <button onclick="if(confirm('Clear your cart?')) clearCart();" class="btn btn-danger">
                        🗑️ Clear Cart
                    </button>
                    <a href="<?php echo BASE_URL; ?>/buyer/shop" class="btn btn-secondary">
                        ← Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Define the base URL for use in scripts
        window.BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo ASSETS_URL; ?>/js/app.js"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/buyer/cart.js"></script>
    <script>
        // Initialize cart page functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Render cart items
                renderCart();
            } catch (error) {
                console.error('Error rendering cart:', error);
                document.getElementById('cart-container').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #ff6b6b;">
                        <p>⚠️ Failed to load cart items. Please try again.</p>
                        <button onclick="window.location.reload()" class="btn btn-primary" style="margin-top: 15px;">Retry</button>
                    </div>`;
            }
        });
    </script>
    <?php include __DIR__ . '/../../views/layouts/footer.php'; ?>
</body>
</html>