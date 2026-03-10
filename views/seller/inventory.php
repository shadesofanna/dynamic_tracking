<?php
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
$pageTitle = APP_NAME . ' - Inventory Management';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/seller_nav.php';
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

    /* PREMIUM HEADER */
    .inventory-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 50px;
        border-radius: 25px;
        margin-bottom: 50px;
        box-shadow: 0 25px 80px rgba(102, 126, 234, 0.35);
        position: relative;
        overflow: hidden;
        animation: headerSlideDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes headerSlideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .inventory-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -15%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .inventory-header::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .inventory-header-content {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 60px;
        align-items: center;
    }

    .inventory-title {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .inventory-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        backdrop-filter: blur(10px);
        animation: iconBounce 0.8s ease 0.3s both;
    }

    @keyframes iconBounce {
        from {
            opacity: 0;
            transform: scale(0.5) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .inventory-title h1 {
        margin: 0;
        font-size: 3.5rem;
        font-weight: 900;
        letter-spacing: -1px;
        text-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .inventory-subtitle {
        font-size: 1rem;
        opacity: 0.95;
        margin-top: 8px;
        font-weight: 300;
        letter-spacing: 0.5px;
    }

    .inventory-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        animation: statsSlideIn 0.8s ease 0.4s both;
    }

    @keyframes statsSlideIn {
        from {
            opacity: 0;
            transform: translateX(40px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .inventory-stat {
        text-align: center;
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .inventory-stat-value {
        font-size: 2.2rem;
        font-weight: 900;
        margin-bottom: 8px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .inventory-stat-label {
        font-size: 0.85rem;
        opacity: 0.95;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ALERT BOX */
    .alert-box {
        background: linear-gradient(135deg, #fff7ed 0%, #fef2f2 100%);
        border-left: 6px solid #f97316;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.15);
        animation: alertSlideIn 0.6s ease;
    }

    @keyframes alertSlideIn {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-box h5 {
        margin-top: 0;
        margin-bottom: 12px;
        color: #c2410c;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }

    .alert-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert-box li {
        margin-bottom: 8px;
        color: #7c2d12;
        font-weight: 600;
        line-height: 1.6;
    }

    /* INVENTORY CONTAINER */
    .inventory-container {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0,0,0,0.1);
        border: 1px solid rgba(226, 232, 240, 0.6);
        animation: containerFadeIn 0.6s ease 0.1s both;
    }

    @keyframes containerFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .inventory-table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 25px 35px;
        border-bottom: 3px solid #e2e8f0;
    }

    .inventory-table-header h3 {
        margin: 0;
        font-size: 1.4rem;
        color: #0f172a;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .table-container {
        overflow-x: auto;
    }

    .inventory-table {
        width: 100%;
        border-collapse: collapse;
    }

    .inventory-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .inventory-table th {
        padding: 18px 25px;
        text-align: left;
        font-weight: 800;
        color: #0f172a;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .inventory-table td {
        padding: 20px 25px;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
    }

    .inventory-table tbody tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .inventory-table tbody tr:hover {
        background: linear-gradient(135deg, #f0f4ff 0%, #f1f5f9 100%);
    }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .product-name {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.05rem;
    }

    .product-sku {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 700;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        width: fit-content;
    }

    .stock-display {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stock-bar {
        flex: 1;
        height: 10px;
        background: #e2e8f0;
        border-radius: 5px;
        overflow: hidden;
        min-width: 150px;
    }

    .stock-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 5px;
        transition: width 0.4s ease;
    }

    .stock-bar-fill.low {
        background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
    }

    .stock-bar-fill.critical {
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
    }

    .stock-number {
        font-weight: 800;
        color: #0f172a;
        min-width: 50px;
        text-align: right;
        font-size: 1.1rem;
    }

    .status-cell {
        text-align: center;
    }

    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .badge-warning {
        background: linear-gradient(135deg, #fed7aa, #fdba74);
        color: #9a3412;
    }

    .badge-danger {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
        color: #7f1d1d;
    }

    .action-cell {
        text-align: center;
    }

    .update-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 800;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }

    .update-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.35);
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
        color: #94a3b8;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 25px;
        opacity: 0.6;
    }

    .empty-state h4 {
        color: #64748b;
        font-size: 1.4rem;
        margin-bottom: 12px;
        font-weight: 800;
    }

    .empty-state p {
        margin: 0;
        color: #94a3b8;
        font-size: 1.05rem;
    }

    /* STOCK UPDATE MODAL */
    .stock-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(8px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .stock-modal-overlay.active {
        display: flex;
        animation: overlayFadeIn 0.3s ease;
    }

    @keyframes overlayFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .stock-modal {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 80px rgba(15, 23, 42, 0.3);
        max-width: 550px;
        width: 100%;
        overflow: hidden;
        animation: modalSlideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stock-modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stock-modal-header h3 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 800;
    }

    .stock-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.8rem;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .stock-modal-close:hover {
        transform: scale(1.15);
        opacity: 0.8;
    }

    .stock-modal-form {
        padding: 30px;
    }

    .stock-info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 25px;
    }

    .stock-info-item {
        background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%);
        padding: 16px;
        border-radius: 12px;
        border-left: 5px solid #667eea;
    }

    .stock-info-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .stock-info-value {
        font-size: 1.4rem;
        font-weight: 900;
        color: #0f172a;
    }

    .stock-form-group {
        margin-bottom: 20px;
    }

    .stock-form-group label {
        display: block;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stock-form-group input,
    .stock-form-group select,
    .stock-form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
    }

    .stock-form-group input:focus,
    .stock-form-group select:focus,
    .stock-form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.12);
    }

    .stock-form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .stock-btn-cancel,
    .stock-btn-update {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .stock-btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    .stock-btn-cancel:hover {
        background: #cbd5e1;
        transform: translateY(-2px);
    }

    .stock-btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
    }

    .stock-btn-update:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .stock-btn-update:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .inventory-header {
            padding: 50px 30px;
        }

        .inventory-header-content {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .inventory-title h1 {
            font-size: 2.2rem;
        }

        .inventory-stats {
            grid-template-columns: 1fr;
        }

        .inventory-table th,
        .inventory-table td {
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        .stock-modal {
            max-width: 100%;
            margin: 0 20px;
        }

        .stock-info-row {
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
        <!-- PREMIUM HEADER -->
        <div class="inventory-header">
            <div class="inventory-header-content">
                <div class="inventory-title">
                    <div class="inventory-icon">📦</div>
                    <div>
                        <h1>Inventory Management</h1>
                        <p class="inventory-subtitle">Monitor and update your product stock levels</p>
                    </div>
                </div>
                <div class="inventory-stats">
                    <div class="inventory-stat">
                        <div class="inventory-stat-value"><?php echo count($products ?? []); ?></div>
                        <div class="inventory-stat-label">Total Products</div>
                    </div>
                    <div class="inventory-stat">
                        <div class="inventory-stat-value"><?php echo count($lowStock ?? []); ?></div>
                        <div class="inventory-stat-label">Low Stock</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- LOW STOCK ALERT -->
        <?php if (!empty($lowStock)): ?>
        <div class="alert-box">
            <h5>⚠️ Low Stock Alert</h5>
            <p style="margin-bottom: 12px; color: #45301c;">The following products are running low on inventory:</p>
            <ul>
                <?php foreach ($lowStock as $product): ?>
                <li>
                    <strong><?php echo htmlspecialchars($product['product_name']); ?></strong> — 
                    <?php echo $product['quantity_available'] ?? $product['stock_quantity']; ?> units remaining
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <!-- INVENTORY CONTAINER -->
        <div class="inventory-container">
            <?php if (empty($products)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h4>No Products in Inventory</h4>
                <p>You haven't added any products yet. Start by creating your first product.</p>
            </div>
            <?php else: ?>
            <div class="inventory-table-header">
                <h3>📊 Product Inventory</h3>
            </div>
            
            <div class="table-container">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th style="width: 110px;">SKU</th>
                            <th style="width: 220px;">Stock Level</th>
                            <th style="width: 130px;">Min Stock</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 160px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): 
                            $available = $product['quantity_available'] ?? $product['stock_quantity'] ?? 0;
                            $minStock = $product['low_stock_threshold'] ?? $product['min_stock_quantity'] ?? 20;
                            $isLow = $available <= $minStock;
                            $isCritical = $available < 5;
                        ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <div class="product-info">
                                        <span class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="product-sku"><?php echo htmlspecialchars($product['sku']); ?></span>
                            </td>
                            <td>
                                <div class="stock-display">
                                    <div class="stock-bar">
                                        <div class="stock-bar-fill <?php echo $isCritical ? 'critical' : ($isLow ? 'low' : ''); ?>" 
                                             style="width: <?php echo min(100, ($available / max($minStock * 2, 100)) * 100); ?>%"></div>
                                    </div>
                                    <span class="stock-number"><?php echo $available; ?></span>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 800; color: #475569; font-size: 1.05rem;"><?php echo $minStock; ?></span>
                            </td>
                            <td>
                                <?php if ($isCritical): ?>
                                    <span class="badge badge-danger">🔴 Critical</span>
                                <?php elseif ($isLow): ?>
                                    <span class="badge badge-warning">🟠 Low</span>
                                <?php else: ?>
                                    <span class="badge badge-success">✓ In Stock</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="update-btn update-stock" 
                                        data-product-id="<?php echo $product['product_id']; ?>" 
                                        data-current-stock="<?php echo $available; ?>"
                                        data-product-name="<?php echo htmlspecialchars($product['product_name']); ?>">
                                    📝 Update
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- STOCK UPDATE MODAL -->
    <div id="stock-update-modal" class="stock-modal-overlay">
        <div class="stock-modal">
            <div class="stock-modal-header">
                <h3>📦 Update Stock Levels</h3>
                <button type="button" class="stock-modal-close" aria-label="Close">&times;</button>
            </div>
            <form id="stock-update-form" class="stock-modal-form">
                <input type="hidden" id="modal-product-id" value="">
                
                <div class="stock-info-row">
                    <div class="stock-info-item">
                        <div class="stock-info-label">Product</div>
                        <div class="stock-info-value" id="modal-product-name">-</div>
                    </div>
                    <div class="stock-info-item">
                        <div class="stock-info-label">Current Stock</div>
                        <div class="stock-info-value" id="modal-current-stock">0</div>
                    </div>
                </div>

                <div class="stock-form-group">
                    <label for="stock-operation">Operation Type</label>
                    <select id="stock-operation" name="operation" required>
                        <option value="add">➕ Add Stock</option>
                        <option value="remove">➖ Remove Stock</option>
                        <option value="set">⚙️ Set Stock Level</option>
                    </select>
                </div>

                <div class="stock-form-group">
                    <label for="stock-quantity">Quantity</label>
                    <input type="number" id="stock-quantity" name="quantity" step="1" min="0" required placeholder="Enter quantity">
                </div>

                <div class="stock-form-group">
                    <label for="stock-reason">Reason (Optional)</label>
                    <textarea id="stock-reason" name="reason" rows="3" placeholder="Why are you making this update?"></textarea>
                </div>

                <div class="stock-form-actions">
                    <button type="button" class="stock-btn-cancel">Cancel</button>
                    <button type="submit" class="stock-btn-update">✓ Update Stock</button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>

    <script src="<?php echo ASSETS_URL; ?>/js/seller/inventory-ui.js?v=<?php echo time(); ?>" defer></script>
    <script src="<?php echo ASSETS_URL; ?>/js/seller/inventory.js"></script>
</body>
</html>
