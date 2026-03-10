<?php
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
$pageTitle = APP_NAME . ' - Pricing';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/seller_nav.php';
?>

<style>
    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .pricing-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 2.5rem 1.5rem 3rem 1.5rem;
        margin: 0 -1.5rem 2rem -1.5rem;
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.6s ease;
    }
    
    .pricing-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .pricing-header-content {
        position: relative;
        z-index: 1;
    }
    
    .pricing-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .pricing-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        animation: fadeInUp 0.6s ease 0.2s both;
    }
    
    .pricing-title h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 900;
        animation: fadeInUp 0.6s ease 0.3s both;
    }
    
    .pricing-subtitle {
        font-size: 0.95rem;
        opacity: 0.95;
        margin-top: 0.25rem;
        animation: fadeInUp 0.6s ease 0.4s both;
    }
    
    .pricing-info {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 0.75rem;
        padding: 1rem;
        font-size: 0.85rem;
    }
    
    .pricing-container {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(226, 232, 240, 0.6);
        animation: fadeInUp 0.6s ease 0.6s both;
    }
    
    .pricing-table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .pricing-table-header h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #0f172a;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .pricing-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .pricing-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .pricing-table th {
        padding: 1.5rem;
        text-align: left;
        font-weight: 700;
        color: #0f172a;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .pricing-table td {
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
    }
    
    .pricing-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .pricing-table tbody tr:hover {
        background: linear-gradient(135deg, #fffbeb 0%, #f1f5f9 100%);
    }
    
    .product-name-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .product-name {
        font-weight: 600;
        color: #0f172a;
    }
    
    .product-sku {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    
    .price-display {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-weight: 600;
        color: #0f172a;
    }
    
    .price-value {
        font-size: 1.15rem;
        color: #f59e0b;
    }
    
    .margin-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
    }
    
    .margin-good {
        background: #d1fae5;
        color: #065f46;
    }
    
    .margin-warning {
        background: #fef3c7;
        color: #78350f;
    }
    
    .margin-low {
        background: #fee2e2;
        color: #7f1d1d;
    }
    
    .update-price-btn {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    
    .update-price-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(245, 158, 11, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
    }
    
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h4 {
        color: #64748b;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        margin: 0;
        color: #94a3b8;
    }

    /* Pricing Modal Styles */
    .pricing-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .pricing-modal-overlay.active {
        display: flex;
    }

    .pricing-modal {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 100%;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .pricing-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .pricing-modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        color: #0f172a;
        font-weight: 700;
    }

    .pricing-modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #64748b;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border-radius: 0.5rem;
    }

    .pricing-modal-close:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .pricing-modal-form {
        padding: 2rem;
    }

    .pricing-form-group {
        margin-bottom: 1.5rem;
    }

    .pricing-form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pricing-product-display,
    .pricing-price-display,
    .pricing-cost-display {
        padding: 1rem;
        background: #f1f5f9;
        border-radius: 0.5rem;
        font-weight: 600;
        color: #0f172a;
        font-size: 1.125rem;
    }

    .pricing-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .pricing-form-row .pricing-form-group {
        margin-bottom: 0;
    }

    .pricing-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .pricing-currency-symbol {
        position: absolute;
        left: 1rem;
        color: #64748b;
        font-weight: 600;
    }

    .pricing-input-group input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .pricing-input-group input:hover {
        border-color: #cbd5e1;
    }

    .pricing-input-group input:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
    }

    .pricing-warning {
        display: none;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 0.5rem;
        color: #92400e;
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.75rem;
    }

    .pricing-warning i {
        color: #f59e0b;
    }

    .pricing-form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .pricing-btn-cancel,
    .pricing-btn-update {
        flex: 1;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .pricing-btn-cancel {
        background: #e2e8f0;
        color: #0f172a;
    }

    .pricing-btn-cancel:hover {
        background: #cbd5e1;
        transform: translateY(-2px);
    }

    .pricing-btn-update {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .pricing-btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    .pricing-btn-update:active {
        transform: translateY(0);
    }

    /* Notification Alerts */
    .pricing-alert {
        position: fixed;
        top: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 2000;
        opacity: 0;
        transform: translateX(400px);
        transition: all 0.3s ease;
    }

    .pricing-alert.show {
        opacity: 1;
        transform: translateX(0);
    }

    .pricing-alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .pricing-alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    @media (max-width: 640px) {
        .pricing-modal {
            max-width: calc(100% - 2rem);
        }

        .pricing-modal-header,
        .pricing-modal-form {
            padding: 1.5rem;
        }

        .pricing-form-row {
            grid-template-columns: 1fr;
        }

        .pricing-form-actions {
            flex-direction: column-reverse;
        }

        .pricing-alert {
            top: 1rem;
            right: 1rem;
            left: 1rem;
        }
    }
</style>

<div class="container" style="max-width: 1400px;">
    <!-- Header -->
    <div class="pricing-header">
        <div class="pricing-header-content">
            <div class="pricing-title">
                <div class="pricing-icon">💰</div>
                <div>
                    <h1>Pricing Management</h1>
                    <p class="pricing-subtitle">Set and optimize your product prices with dynamic pricing</p>
                </div>
            </div>
            
            <div class="pricing-info">
                💡 <strong>Pro Tip:</strong> Prices automatically adjust based on inventory levels and market demand. Low stock triggers price increases, while excess inventory may lower prices to move inventory faster.
            </div>
        </div>
    </div>
    
    <!-- Pricing Table -->
    <?php if (empty($products)): ?>
    <div class="pricing-container">
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h4>No Products to Price</h4>
            <p>Add products to your catalog first before managing pricing.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="pricing-container">
        <div class="pricing-table-header">
            <h3>📊 Product Pricing</h3>
        </div>
        
        <div class="table-container">
            <table class="pricing-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th style="width: 100px;">SKU</th>
                        <th style="width: 120px;">Current Price</th>
                        <th style="width: 120px;">Base Cost</th>
                        <th style="width: 100px;">Profit Margin</th>
                        <th style="width: 140px;">Last Updated</th>
                        <th style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): 
                        $current_price = $product['current_price'] ?? 0;
                        $base_cost = $product['base_cost'] ?? 0;
                        $margin = $base_cost > 0 ? (($current_price - $base_cost) / $base_cost) * 100 : 0;
                        $margin_class = $margin >= 25 ? 'margin-good' : ($margin >= 10 ? 'margin-warning' : 'margin-low');
                    ?>
                    <tr>
                        <td>
                            <div class="product-name-cell">
                                <span class="product-name"><?php echo htmlspecialchars($product['product_name'] ?? 'Unnamed Product'); ?></span>
                            </div>
                        </td>
                        <td>
                            <code style="background: #f1f5f9; padding: 0.3rem 0.6rem; border-radius: 0.3rem; font-size: 0.85rem; color: #3b82f6;">
                                <?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?>
                            </code>
                        </td>
                        <td>
                            <div class="price-display">
                                <span class="price-value">₦<?php echo number_format($current_price, 2); ?></span>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #475569;">₦<?php echo number_format($base_cost, 2); ?></span>
                        </td>
                        <td>
                            <span class="margin-badge <?php echo $margin_class; ?>">
                                <?php echo number_format($margin, 1); ?>%
                            </span>
                        </td>
                        <td>
                            <?php if ($product['last_price_update']): ?>
                                <span style="color: #475569; font-weight: 500;">
                                    <?php echo date('M j, Y', strtotime($product['last_price_update'])); ?>
                                </span>
                                <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.25rem;">
                                    <?php echo date('g:i A', strtotime($product['last_price_update'])); ?>
                                </div>
                            <?php else: ?>
                                <span style="color: #94a3b8;">Never</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <button class="update-price-btn update-price" 
                                    data-product-id="<?php echo $product['product_id']; ?>"
                                    data-current-price="<?php echo $current_price; ?>"
                                    data-product-name="<?php echo htmlspecialchars($product['product_name']); ?>">
                                Update Price
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<script src="<?php echo ASSETS_URL; ?>/js/seller/pricing-ui.js?v=<?php echo time(); ?>" defer></script>