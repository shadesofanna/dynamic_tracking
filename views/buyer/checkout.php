<?php
// views/buyer/checkout.php
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
if (!Session::isLoggedIn()) {
    redirect('/login');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?php echo BASE_URL; ?>">
    <title>Checkout - Dynamic Pricing</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/main.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/buyer.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/toast.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/buyer_nav.php'; ?>

    <div class="container">
        <h1>Checkout</h1>

        <div class="checkout-content">
            <!-- Order Summary Component -->
            <?php include __DIR__ . '/../components/cart_summary.php'; ?>

            <!-- Checkout Form Component -->
            <?php include __DIR__ . '/../components/checkout_form.php'; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <script>
        // Define the base URL for use in scripts
        window.BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo ASSETS_URL; ?>/js/app.js"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/buyer/checkout.js"></script>
    
    <script>
        // Initialize checkout when DOM and Toast are ready
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Checkout !== 'undefined') {
                Checkout.init();
            }
        });
    </script>
</body>
</html>
