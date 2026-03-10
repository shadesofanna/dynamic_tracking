<?php
$pageTitle = APP_NAME . ' - Order Details';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/buyer_nav.php';
?>

<style>
    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .order-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem 1.5rem 3rem 1.5rem;
        margin: 0 -1.5rem 2rem -1.5rem;
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.5s ease;
    }
    
    .order-detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .order-detail-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }
    
    .order-detail-title {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .order-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    
    .order-detail-title h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 900;
    }
    
    .order-detail-subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 0.25rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 2rem;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: capitalize;
    }
    
    .status-badge.pending {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .status-badge.confirmed {
        background: rgba(76, 175, 80, 0.2);
        color: #4caf50;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .status-badge.processing {
        background: rgba(33, 150, 243, 0.2);
        color: #2196f3;
        border: 1px solid rgba(33, 150, 243, 0.3);
    }
    
    .status-badge.shipped {
        background: rgba(156, 39, 176, 0.2);
        color: #9c27b0;
        border: 1px solid rgba(156, 39, 176, 0.3);
    }
    
    .status-badge.delivered {
        background: rgba(76, 175, 80, 0.2);
        color: #4caf50;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .status-badge.cancelled {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
        border: 1px solid rgba(244, 67, 54, 0.3);
    }

    .detail-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(226, 232, 240, 0.6);
        margin-bottom: 2rem;
    }

    .detail-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
    }

    .detail-card-header h5 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-card-body {
        padding: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.875rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
    }

    .info-value.muted {
        color: #475569;
        font-weight: 500;
        font-size: 1rem;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .items-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        color: #475569;
        font-size: 0.9rem;
    }

    .items-table th.text-end {
        text-align: right;
    }

    .items-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s ease;
    }

    .items-table tbody tr:hover {
        background: #f8fafc;
    }

    .items-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .product-image {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 0.375rem;
        border: 1px solid #e2e8f0;
    }

    .product-details h6 {
        margin: 0;
        font-weight: 600;
        color: #0f172a;
    }

    .product-details small {
        color: #94a3b8;
        display: block;
    }

    .alert-box {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0;
    }

    .alert-info {
        background: rgba(33, 150, 243, 0.1);
        border: 1px solid rgba(33, 150, 243, 0.2);
        color: #1976d2;
    }

    .sidebar-card {
        position: sticky;
        top: 1rem;
    }

    .order-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .order-summary-row:last-child {
        border-bottom: none;
        padding-top: 1rem;
        font-size: 1.125rem;
        font-weight: 700;
        color: #667eea;
    }

    .order-summary-label {
        color: #475569;
    }

    @media (max-width: 768px) {
        .order-detail-header-content {
            flex-direction: column;
            text-align: center;
        }

        .order-detail-title {
            flex-direction: column;
        }

        .order-detail-title h1 {
            font-size: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .sidebar-card {
            position: static;
        }
    }
</style>

<div class="order-detail-header">
    <div class="order-detail-header-content">
        <div class="order-detail-title">
            <div class="order-icon">📦</div>
            <div>
                <h1>Order #<?php echo htmlspecialchars($order['order_id']); ?></h1>
                <p class="order-detail-subtitle">
                    <i class="fas fa-calendar"></i>
                    <?php echo date('M j, Y \a\t g:i A', strtotime($order['created_at'])); ?>
                </p>
            </div>
        </div>
        <span class="status-badge <?php echo $order['status']; ?>">
            <?php echo ucfirst($order['status']); ?>
        </span>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Order Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5><i class="fas fa-box"></i> Order Information</h5>
                </div>
                <div class="detail-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Order Date</span>
                            <span class="info-value muted"><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Payment Status</span>
                            <div>
                                <span class="status-badge <?php echo $order['payment_status']; ?>">
                                    <i class="fas fa-credit-card"></i> <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Order Total</span>
                            <span class="info-value" style="color: #667eea;">₦<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5><i class="fas fa-list"></i> Order Items</h5>
                </div>
                <div class="detail-card-body">
                    <?php if (empty($order['items'])): ?>
                        <div class="alert-box alert-info">
                            <i class="fas fa-info-circle"></i> No items found for this order.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <?php if (!empty($item['product_image'])): ?>
                                                    <img src="<?php echo ASSETS_URL . '/images/products/' . htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-image">
                                                <?php else: ?>
                                                    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                                        <i class="fas fa-image" style="color: #94a3b8; font-size: 1.25rem;"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="product-details">
                                                    <h6><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                    <?php if (!empty($item['sku'])): ?>
                                                        <small>SKU: <?php echo htmlspecialchars($item['sku']); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end" style="font-weight: 600;"><?php echo (int)$item['quantity']; ?></td>
                                        <td class="text-end">₦<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="text-end" style="font-weight: 700; color: #667eea;">₦<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="col-lg-4">
            <div class="detail-card sidebar-card">
                <div class="detail-card-header">
                    <h5><i class="fas fa-calculator"></i> Summary</h5>
                </div>
                <div class="detail-card-body">
                    <div class="order-summary-row">
                        <span class="order-summary-label">Subtotal:</span>
                        <span>₦<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                    <div class="order-summary-row">
                        <span class="order-summary-label">Tax:</span>
                        <span>₦<?php echo number_format(0, 2); ?></span>
                    </div>
                    <div class="order-summary-row">
                        <span class="order-summary-label">Shipping:</span>
                        <span>₦<?php echo number_format(0, 2); ?></span>
                    </div>
                    <div class="order-summary-row">
                        <span class="order-summary-label">Total Amount:</span>
                        <span>₦<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Delivery Status -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5><i class="fas fa-truck"></i> Delivery Status</h5>
                </div>
                <div class="detail-card-body">
                    <div class="info-item">
                        <span class="info-label">Current Status</span>
                        <span class="status-badge <?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5><i class="fas fa-ellipsis-h"></i> Actions</h5>
                </div>
                <div class="detail-card-body">
                    <a href="<?php echo url('buyer/orders'); ?>" class="btn btn-outline-secondary" style="width: 100%; text-decoration: none; display: inline-block; text-align: center; padding: 0.75rem;">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
