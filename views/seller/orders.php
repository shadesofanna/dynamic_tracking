<?php
if (!defined('BASE_PATH')) {
    exit('No direct script access allowed');
}
$pageTitle = APP_NAME . ' - Orders';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/seller_nav.php';

// Calculate stats
$pending = array_filter($orders, fn($o) => $o['status'] === 'pending' || $o['status'] === 'processing');
$completed = array_filter($orders, fn($o) => $o['status'] === 'completed');
$total_revenue = array_sum(array_map(fn($o) => $o['total_amount'], $orders));
?>

<style>
    .orders-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 2.5rem 1.5rem 3rem 1.5rem;
        margin: 0 -1.5rem 2rem -1.5rem;
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .orders-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .orders-header-content {
        position: relative;
        z-index: 1;
    }
    
    .orders-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .orders-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    
    .orders-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        color: white;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-5px);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.95;
    }
    
    .orders-container {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(226, 232, 240, 0.6);
    }
    
    .orders-table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .orders-table-header h3 {
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
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .orders-table th {
        padding: 1.5rem;
        text-align: left;
        font-weight: 700;
        color: #0f172a;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .orders-table td {
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
    }
    
    .orders-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .orders-table tbody tr:hover {
        background: linear-gradient(135deg, #f0f4ff 0%, #f1f5f9 100%);
    }
    
    .order-id {
        font-weight: 700;
        color: #3b82f6;
        font-size: 1.05rem;
    }
    
    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .customer-name {
        font-weight: 600;
        color: #0f172a;
    }
    
    .customer-email {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    
    .order-date {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .date-main {
        font-weight: 600;
        color: #0f172a;
    }
    
    .date-time {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    
    .order-amount {
        font-weight: 700;
        color: #10b981;
        font-size: 1.15rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
    }
    
    .view-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
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
</style>

<div class="container" style="max-width: 1400px;">
    <!-- Header with Stats -->
    <div class="orders-header">
        <div class="orders-header-content">
            <div class="orders-title">
                <div class="orders-icon">📦</div>
                <div>
                    <h1 style="margin: 0; font-size: 2.5rem; font-weight: 900;">Orders</h1>
                    <p style="margin: 0.25rem 0 0 0; opacity: 0.95;">Manage and track all your orders</p>
                </div>
            </div>
            
            <div class="orders-stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($orders); ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($pending); ?></div>
                    <div class="stat-label">Pending/Processing</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($completed); ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">₦<?php echo number_format($total_revenue, 0); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <?php if (empty($orders)): ?>
    <div class="orders-container">
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h4>No Orders Yet</h4>
            <p>You don't have any orders yet. Orders will appear here as they are placed.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="orders-container">
        <div class="orders-table-header">
            <h3>📊 Recent Orders</h3>
        </div>
        
        <div class="table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th style="text-align: right;">Amount</th>
                        <th>Status</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <span class="order-id">#<?php echo htmlspecialchars($order['order_id']); ?></span>
                        </td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="order-date">
                                <span class="date-main"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></span>
                                <span class="date-time"><?php echo date('g:i A', strtotime($order['created_at'])); ?></span>
                            </div>
                        </td>
                        <td>
                            <span style="background: #f1f5f9; padding: 0.4rem 0.8rem; border-radius: 0.4rem; font-size: 0.9rem; font-weight: 600; color: #3b82f6;">
                                <?php echo isset($order['item_count']) ? $order['item_count'] : '1'; ?> item<?php echo (isset($order['item_count']) && $order['item_count'] > 1) ? 's' : ''; ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <span class="order-amount">₦<?php echo number_format($order['total_amount'], 2); ?></span>
                        </td>
                        <td>
                            <span class="badge <?php 
                                if ($order['status'] === 'completed') {
                                    echo 'badge-success';
                                } elseif ($order['status'] === 'pending') {
                                    echo 'badge-danger';
                                } elseif ($order['status'] === 'processing') {
                                    echo 'badge-info';
                                } else {
                                    echo 'badge-warning';
                                }
                            ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="<?php echo url('seller/order/' . $order['order_id']); ?>" class="view-btn">
                                View Details
                            </a>
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