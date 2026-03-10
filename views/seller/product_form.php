<?php
// views/seller/product_form.php
$isEditing = isset($product);
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/seller_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditing ? 'Edit Product' : 'Add Product'; ?> - Dynamic Pricing</title>
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
        max-width: 900px;
        margin: 0 auto;
        padding: 50px 25px;
    }

    /* PAGE HEADER */
    .page-header {
        margin-bottom: 50px;
        animation: headerSlideDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes headerSlideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-title {
        font-size: 3rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }

    .page-subtitle {
        font-size: 1.1rem;
        color: #64748b;
        font-weight: 300;
    }

    /* FORM CONTAINER */
    .form-section {
        background: white;
        border-radius: 20px;
        padding: 50px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.1);
        border: 1px solid rgba(226, 232, 240, 0.6);
        animation: formFadeIn 0.8s ease 0.2s both;
    }

    @keyframes formFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* SECTION HEADERS */
    .form-section-header {
        margin-bottom: 35px;
        padding-bottom: 20px;
        border-bottom: 3px solid #e2e8f0;
    }

    .form-section-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .form-section-header p {
        font-size: 0.95rem;
        color: #64748b;
    }

    /* FORM GROUPS */
    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 10px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.12);
        transform: translateY(-2px);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    /* FORM ROWS */
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 15px;
    }

    /* SMALL TEXT */
    small {
        display: block;
        margin-top: 8px;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
    }

    /* IMAGE PREVIEW */
    .current-image-preview {
        margin-bottom: 25px;
    }

    .current-image-preview label {
        display: block;
        font-weight: 700;
        color: #475569;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }

    .image-preview-container {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%);
        border-radius: 12px;
        padding: 30px;
        border: 2px solid #e2e8f0;
    }

    .product-preview-img {
        max-width: 250px;
        max-height: 250px;
        border-radius: 10px;
        object-fit: contain;
    }

    /* FILE INPUT STYLING */
    input[type="file"] {
        padding: 10px;
    }

    input[type="file"]::file-selector-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        margin-right: 15px;
        transition: all 0.3s ease;
    }

    input[type="file"]::file-selector-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
    }

    /* PROFIT INDICATOR */
    .profit-indicator {
        background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%);
        border-left: 5px solid #667eea;
        border-radius: 12px;
        padding: 20px 25px;
        display: none;
        animation: slideIn 0.4s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .profit-indicator strong {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.05rem;
    }

    #profit-margin,
    #profit-amount {
        font-weight: 900;
        font-size: 1.15rem;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .ms-2 {
        margin-left: 10px;
    }

    /* FORM ACTIONS */
    .form-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #e2e8f0;
    }

    .btn {
        padding: 14px 28px;
        border: none;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #475569;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .form-section {
            padding: 30px 20px;
        }

        .page-title {
            font-size: 2rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            grid-template-columns: 1fr;
        }

        .container {
            padding: 30px 15px;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <?php echo $isEditing ? '✏️ Edit Product' : '➕ Add New Product'; ?>
            </h1>
            <p class="page-subtitle">
                <?php echo $isEditing ? 'Update your product information and pricing' : 'Create a new product listing for your store'; ?>
            </p>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <form action="<?php echo url('seller/product/' . ($isEditing ? 'update/' . $product['product_id'] : 'store')); ?>" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="product-form">
                
                <!-- Basic Information Section -->
                <div class="form-section-header">
                    <h2>📋 Basic Information</h2>
                    <p>Essential details about your product</p>
                </div>

                <!-- Product Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Product Name *</label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="product_name" 
                           placeholder="Enter a clear and descriptive product name"
                           value="<?php echo $isEditing ? htmlspecialchars($product['product_name']) : ''; ?>" 
                           required>
                    <small>Choose a name that clearly describes your product</small>
                </div>

                <!-- SKU -->
                <div class="form-group">
                    <label for="sku" class="form-label">SKU (Stock Keeping Unit) *</label>
                    <input type="text" 
                           class="form-control" 
                           id="sku" 
                           name="sku" 
                           placeholder="e.g., PROD-12345 or SKU-001"
                           value="<?php echo $isEditing ? htmlspecialchars($product['sku']) : ''; ?>" 
                           required>
                    <small>Unique identifier for inventory tracking and reference</small>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Product Description *</label>
                    <textarea class="form-control" 
                              id="description" 
                              name="product_description" 
                              placeholder="Describe your product in detail. Include features, benefits, specifications, and any important information..."
                              required><?php echo $isEditing ? htmlspecialchars($product['product_description']) : ''; ?></textarea>
                    <small>Provide detailed information that helps buyers understand your product</small>
                </div>

                <!-- Pricing & Inventory Section -->
                <div class="form-section-header">
                    <h2>💰 Pricing & Inventory</h2>
                    <p>Set your pricing strategy and stock levels</p>
                </div>

                <div class="form-row">
                    <!-- Price -->
                    <div class="form-group">
                        <label for="price" class="form-label">Selling Price (₦) *</label>
                        <input type="number" 
                               class="form-control" 
                               id="price" 
                               name="price" 
                               step="0.01" 
                               min="0" 
                               placeholder="0.00"
                               value="<?php echo $isEditing ? number_format($product['current_price'], 2, '.', '') : ''; ?>" 
                               required>
                        <small>The price customers will pay</small>
                    </div>

                    <!-- Cost -->
                    <div class="form-group">
                        <label for="cost" class="form-label">Base Cost (₦) *</label>
                        <input type="number" 
                               class="form-control" 
                               id="cost" 
                               name="cost" 
                               step="0.01" 
                               min="0" 
                               placeholder="0.00"
                               value="<?php echo $isEditing ? number_format($product['base_cost'], 2, '.', '') : ''; ?>" 
                               required>
                        <small>Your cost per unit (used to calculate profit)</small>
                    </div>

                    <!-- Initial Stock -->
                    <div class="form-group">
                        <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                        <input type="number" 
                               class="form-control" 
                               id="stock_quantity" 
                               name="stock_quantity" 
                               min="0" 
                               placeholder="0"
                               value="<?php echo $isEditing ? $product['quantity_available'] : ''; ?>" 
                               required>
                        <small>Available units in stock</small>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Min Stock Level -->
                    <div class="form-group">
                        <label for="min_stock_quantity" class="form-label">Low Stock Alert Level</label>
                        <input type="number" 
                               class="form-control" 
                               id="min_stock_quantity" 
                               name="min_stock_quantity" 
                               min="0" 
                               placeholder="5"
                               value="<?php echo $isEditing ? $product['low_stock_threshold'] : '5'; ?>">
                        <small>Get notified when stock falls below this level</small>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="is_active" class="form-label">Product Status</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" <?php echo ($isEditing && $product['is_active']) ? 'selected' : ''; ?>>
                                ✓ Active (Visible to buyers)
                            </option>
                            <option value="0" <?php echo ($isEditing && !$product['is_active']) ? 'selected' : ''; ?>>
                                ✕ Inactive (Hidden from buyers)
                            </option>
                        </select>
                        <small>Control whether this product appears in the shop</small>
                    </div>
                </div>

                <!-- Product Image Section -->
                <div class="form-section-header">
                    <h2>🖼️ Product Image</h2>
                    <p>Upload a high-quality product image</p>
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Product Image</label>
                    
                    <?php if ($isEditing && !empty($product['image_url'])): ?>
                        <div class="current-image-preview">
                            <label>Current Image:</label>
                            <div class="image-preview-container">
                                <img src="<?php echo $product['image_url']; ?>" 
                                     alt="Current product image" 
                                     class="product-preview-img">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" 
                           class="form-control" 
                           id="image" 
                           name="image" 
                           accept="image/*">
                    
                    <?php if ($isEditing): ?>
                        <small>Leave empty to keep the current image, or upload a new one to replace it</small>
                    <?php else: ?>
                        <small>Recommended: Square image, at least 800x800px, JPG or PNG format</small>
                    <?php endif; ?>
                </div>

                <!-- Profit Margin Indicator -->
                <div class="profit-indicator" id="profit-indicator">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Estimated Profit Margin:</strong>
                            <span id="profit-margin" class="ms-2">0%</span>
                        </div>
                        <div>
                            <strong>Profit per Unit:</strong>
                            <span id="profit-amount" class="ms-2">₦0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="<?php echo url('seller/products'); ?>" class="btn btn-secondary">
                        ← Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <?php echo $isEditing ? '✓ Update Product' : '✓ Create Product'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Calculate profit margin in real-time
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            const costInput = document.getElementById('cost');
            const profitIndicator = document.getElementById('profit-indicator');
            const profitMargin = document.getElementById('profit-margin');
            const profitAmount = document.getElementById('profit-amount');

            function calculateProfit() {
                const price = parseFloat(priceInput.value) || 0;
                const cost = parseFloat(costInput.value) || 0;

                if (price > 0 && cost > 0) {
                    const profit = price - cost;
                    const margin = ((profit / price) * 100).toFixed(2);
                    
                    profitMargin.textContent = margin + '%';
                    profitMargin.style.color = margin > 20 ? '#10b981' : margin > 10 ? '#f59e0b' : '#ef4444';
                    
                    profitAmount.textContent = '₦' + profit.toFixed(2);
                    profitAmount.style.color = profit > 0 ? '#10b981' : '#ef4444';
                    
                    profitIndicator.style.display = 'block';
                } else {
                    profitIndicator.style.display = 'none';
                }
            }

            priceInput.addEventListener('input', calculateProfit);
            costInput.addEventListener('input', calculateProfit);
            
            // Initial calculation if editing
            calculateProfit();
        });
    </script>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
