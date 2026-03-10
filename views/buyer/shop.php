<?php
// views/buyer/shop.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';
require_once __DIR__ . '/../../models/Product.php';

Session::start();

$productModel = new Product();
$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;
$minPrice = $_GET['min_price'] ?? null;
$maxPrice = $_GET['max_price'] ?? null;
$page = (int)($_GET['page'] ?? 1);
$limit = PRODUCTS_PER_PAGE ?? 12;
$offset = ($page - 1) * $limit;

if ($search) {
    $products = $productModel->searchProducts($search, $category);
} elseif ($minPrice && $maxPrice) {
    $products = $productModel->getProductsByPriceRange($minPrice, $maxPrice, $category);
} elseif ($category) {
    $products = $productModel->getProductsByCategory($category, $limit);
} else {
    $products = $productModel->findAll(['is_active' => 1], 'created_at DESC', $limit, $offset);
}

$categories = ['Electronics', 'Fashion', 'Home', 'Books', 'Sports'];
$pageTitle = APP_NAME . ' - Premium Shop';
require_once __DIR__ . '/../../views/layouts/header.php';
require_once __DIR__ . '/../../views/layouts/buyer_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%);
    }

    .container {
        max-width: 1450px;
        margin: 0 auto;
        padding: 50px 25px;
    }

    /* PREMIUM HERO SECTION */
    .shop-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px 50px;
        border-radius: 25px;
        margin-bottom: 60px;
        text-align: center;
        box-shadow: 0 25px 80px rgba(102, 126, 234, 0.35);
        position: relative;
        overflow: hidden;
        animation: heroSlideDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .shop-hero::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -15%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .shop-hero::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .shop-hero-content {
        position: relative;
        z-index: 1;
    }

    .shop-hero h1 {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 20px;
        letter-spacing: -1.5px;
        text-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .shop-hero p {
        font-size: 1.3rem;
        opacity: 0.98;
        font-weight: 300;
        letter-spacing: 0.5px;
    }

    @keyframes heroSlideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* PREMIUM FILTER SECTION */
    .shop-filters {
        background: white;
        border-radius: 20px;
        padding: 50px;
        margin-bottom: 60px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.1);
        border: 1px solid rgba(226, 232, 240, 0.6);
        animation: filterFadeIn 0.8s ease 0.2s both;
    }

    @keyframes filterFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 35px;
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 30px;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .filter-group label {
        font-weight: 800;
        color: #1e293b;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .filter-group input,
    .filter-group select {
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.12);
        transform: translateY(-2px);
    }

    .filter-actions {
        display: flex;
        gap: 18px;
    }

    .btn-filter {
        flex: 1;
        padding: 14px 28px;
        border: none;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .btn-apply {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .btn-apply:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
    }

    .btn-clear {
        background: white;
        color: #475569;
        border: 2px solid #e2e8f0;
    }

    .btn-clear:hover {
        background: #f8fafc;
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
    }

    /* RESULTS INFO */
    .shop-results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 45px;
        padding-bottom: 25px;
        border-bottom: 3px solid #e2e8f0;
    }

    .results-count {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .results-count strong {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.5em;
    }

    /* PRODUCTS GRID - PREMIUM */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 35px;
        animation: gridFadeIn 0.7s ease;
    }

    @keyframes gridFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* PRODUCT CARD - PREMIUM */
    .product-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.5);
        display: flex;
        flex-direction: column;
        height: 100%;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.2);
        border-color: #667eea;
    }

    .product-image {
        position: relative;
        width: 100%;
        height: 280px;
        overflow: hidden;
        background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%);
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card:hover .product-image img {
        transform: scale(1.12);
    }

    .badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        animation: badgeFloat 0.4s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    @keyframes badgeFloat {
        from {
            opacity: 0;
            transform: translateX(30px) translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0) translateY(0);
        }
    }

    .badge-low-stock {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .product-info {
        padding: 25px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        flex: 1;
    }

    .product-category {
        font-size: 0.8rem;
        color: #7c3aed;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .product-name {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.5;
    }

    .product-price {
        font-size: 1.7rem;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-top: auto;
    }

    .product-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 10px;
    }

    .btn-view, .btn-cart {
        padding: 12px 16px;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-view {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e2e8f0;
    }

    .btn-view:hover {
        background: #e2e8f0;
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .btn-cart {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 6px 15px rgba(102, 126, 234, 0.25);
    }

    .btn-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.35);
    }

    /* EMPTY STATE */
    .no-products {
        text-align: center;
        padding: 80px 40px;
        background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%);
        border-radius: 20px;
        border: 3px dashed #e2e8f0;
    }

    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 25px;
    }

    .no-products h2 {
        font-size: 2rem;
        color: #0f172a;
        margin-bottom: 15px;
        font-weight: 800;
    }

    .no-products p {
        color: #64748b;
        margin-bottom: 12px;
        font-size: 1.05rem;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .shop-hero {
            padding: 60px 30px;
        }

        .shop-hero h1 {
            font-size: 2.5rem;
        }

        .shop-filters {
            padding: 30px;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            flex-direction: column;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .container {
            padding: 30px 15px;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <!-- HERO SECTION -->
        <div class="shop-hero">
            <div class="shop-hero-content">
                <h1>✨ Premium Marketplace</h1>
                <p>Discover curated products with exclusive deals</p>
            </div>
        </div>
        
        <!-- FILTER SECTION -->
        <div class="shop-filters">
            <div class="filter-header">
                🔍 Refine Your Search
            </div>
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" 
                           id="search"
                           name="search" 
                           placeholder="Find products..." 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" 
                                    <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="min_price">Min Price (₦)</label>
                    <input type="number" 
                           id="min_price"
                           name="min_price" 
                           placeholder="Minimum" 
                           value="<?php echo htmlspecialchars($minPrice ?? ''); ?>"
                           min="0"
                           step="5000">
                </div>
                
                <div class="filter-group">
                    <label for="max_price">Max Price (₦)</label>
                    <input type="number" 
                           id="max_price"
                           name="max_price" 
                           placeholder="Maximum" 
                           value="<?php echo htmlspecialchars($maxPrice ?? ''); ?>"
                           min="0"
                           step="5000">
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-apply">
                        ✓ Apply Filters
                    </button>
                    
                    <?php if ($search || $category || $minPrice || $maxPrice): ?>
                        <a href="<?php echo BASE_URL; ?>/buyer/shop" class="btn-filter btn-clear">
                            ✕ Clear Filters
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- RESULTS INFO -->
        <?php if (!empty($products)): ?>
            <div class="shop-results-header">
                <div class="results-count">
                    <strong><?php echo count($products); ?></strong> 
                    product<?php echo count($products) !== 1 ? 's' : ''; ?> available
                    <?php if ($category): ?>
                        in <strong><?php echo htmlspecialchars($category); ?></strong>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- PRODUCTS GRID -->
        <?php if (empty($products)): ?>
            <div class="no-products">
                <div class="empty-state-icon">🔍</div>
                <h2>No Products Found</h2>
                <p>We couldn't find any products matching your search.</p>
                <p>Try adjusting your filters or browse all available products.</p>
                <a href="<?php echo BASE_URL; ?>/buyer/shop" class="btn-filter btn-apply" style="display: inline-block; margin-top: 25px;">
                    ← Browse All Products
                </a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (isset($product['image_url']) && $product['image_url']): ?>
                                <img src="<?php 
                                    $imgUrl = $product['image_url'];
                                    $imgUrl = preg_replace('/^\/?(assets\/)?/', '', $imgUrl);
                                    echo BASE_URL . '/assets/' . $imgUrl; 
                                ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>/assets/images/no-image.png" alt="No image">
                            <?php endif; ?>
                            
                            <?php if (isset($product['quantity_available']) && $product['quantity_available'] <= ($product['low_stock_threshold'] ?? 20)): ?>
                                <span class="badge badge-low-stock">
                                    ⚠️ Limited Stock
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-info">
                            <p class="product-category"><?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></p>
                            <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            
                            <div class="product-price">
                                ₦<?php echo number_format($product['current_price'], 2); ?>
                            </div>
                            
                            <div class="product-actions">
                                <a href="<?php echo BASE_URL; ?>/buyer/product/<?php echo $product['product_id']; ?>" class="btn-view">
                                    👁️ View
                                </a>
                                <button class="btn-cart" onclick="addToCart(<?php echo $product['product_id']; ?>)">
                                    🛒 Add
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- SCRIPTS -->
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/buyer/cart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                window.cartManager = new CartManager();
            } catch (error) {
                console.error('Error initializing cart:', error);
            }
        });
    </script>

    <?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>
</body>
</html>
