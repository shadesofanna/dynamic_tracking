<?php
$pageTitle = APP_NAME . ' - Analytics';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/seller_nav.php';
?>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .analytics-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        padding: 2.5rem 1.5rem 3rem 1.5rem;
        margin: 0 -1.5rem 2rem -1.5rem;
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .analytics-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .analytics-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .analytics-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    
    .analytics-title h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 900;
    }
    
    .analytics-subtitle {
        font-size: 0.95rem;
        opacity: 0.95;
        margin-top: 0.25rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(226, 232, 240, 0.6);
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(15, 23, 42, 0.1);
    }
    
    .stat-card-title {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .stat-card-value {
        font-size: 2.5rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    
    .stat-card-change {
        font-size: 0.875rem;
        color: #64748b;
    }
    
    .stat-card-change.positive {
        color: #10b981;
    }
    
    .stat-card-change.negative {
        color: #ef4444;
    }
    
    .chart-section {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(226, 232, 240, 0.6);
        margin-bottom: 2rem;
        animation: slideInLeft 0.5s ease forwards;
    }
    
    .chart-section h3 {
        margin: 0 0 1.5rem 0;
        font-size: 1.25rem;
        color: #0f172a;
        font-weight: 700;
    }
    
    .chart-placeholder {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 0.75rem;
        padding: 3rem;
        text-align: center;
        color: #94a3b8;
    }
    
    .mini-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .mini-stat:last-child {
        margin-bottom: 0;
    }
    
    .mini-stat-label {
        font-weight: 600;
        color: #64748b;
    }
    
    .mini-stat-value {
        font-size: 1.25rem;
        font-weight: 900;
        color: #0f172a;
    }
    
    @media (max-width: 768px) {
        .analytics-header {
            padding: 2rem 1.5rem;
            margin: 0 -1.5rem 1.5rem -1.5rem;
        }
        
        .analytics-header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .analytics-title h1 {
            font-size: 1.75rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="analytics-header">
    <div class="analytics-header-content">
        <div class="analytics-icon">📊</div>
        <div class="analytics-title">
            <h1>Analytics</h1>
            <p class="analytics-subtitle">Track your sales performance and insights</p>
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Key Metrics -->
    <div class="stats-grid">
        <!-- Revenue Today -->
        <div class="stat-card">
            <div class="stat-card-title">💰 Revenue Today</div>
            <div class="stat-card-value">
                ₦<?php echo isset($stats['revenue_stats']['today']) ? number_format($stats['revenue_stats']['today'], 2) : '0.00'; ?>
            </div>
            <div class="stat-card-change positive">↑ Track daily earnings</div>
        </div>

        <!-- Revenue This Month -->
        <div class="stat-card">
            <div class="stat-card-title">📈 Revenue This Month</div>
            <div class="stat-card-value">
                ₦<?php echo isset($stats['revenue_stats']['month']) ? number_format($stats['revenue_stats']['month'], 2) : '0.00'; ?>
            </div>
            <div class="stat-card-change positive">↑ Monthly performance</div>
        </div>

        <!-- Total Orders -->
        <div class="stat-card">
            <div class="stat-card-title">📦 Total Orders</div>
            <div class="stat-card-value">
                <?php 
                    $total_orders = 0;
                    if (isset($stats['order_stats']) && is_array($stats['order_stats'])) {
                        foreach ($stats['order_stats'] as $count) {
                            if (is_numeric($count)) {
                                $total_orders += intval($count);
                            }
                        }
                    }
                    echo $total_orders;
                ?>
            </div>
            <div class="stat-card-change">View details below</div>
        </div>

        <!-- Total Products -->
        <div class="stat-card">
            <div class="stat-card-title">🏷️ Active Products</div>
            <div class="stat-card-value">
                <?php echo isset($stats['total_products']) ? $stats['total_products'] : '0'; ?>
            </div>
            <div class="stat-card-change negative">⚠️ Low Stock: <?php echo isset($stats['low_stock_count']) ? $stats['low_stock_count'] : '0'; ?></div>
        </div>
    </div>

    <!-- Order Status Breakdown -->
    <div class="chart-section">
        <h3>Order Status Breakdown</h3>
        <div>
            <?php 
                if (isset($stats['order_stats']) && is_array($stats['order_stats'])) {
                    foreach ($stats['order_stats'] as $status => $count) {
                        $count_value = is_numeric($count) ? intval($count) : 0;
                        $status_display = ucfirst(str_replace('_', ' ', $status));
                        $status_colors = [
                            'pending' => '#f59e0b',
                            'processing' => '#3b82f6',
                            'completed' => '#10b981',
                            'cancelled' => '#ef4444'
                        ];
                        $color = $status_colors[strtolower($status)] ?? '#64748b';
            ?>
                <div class="mini-stat">
                    <div class="mini-stat-label">
                        <span style="display: inline-block; width: 12px; height: 12px; background: <?php echo $color; ?>; border-radius: 3px; margin-right: 0.5rem;"></span>
                        <?php echo $status_display; ?>
                    </div>
                    <div class="mini-stat-value"><?php echo $count_value; ?></div>
                </div>
            <?php
                    }
                } else {
                    echo '<p style="color: #94a3b8; text-align: center; padding: 2rem;">No order data available yet</p>';
                }
            ?>
        </div>
    </div>

    <!-- Additional Insights -->
    <div class="chart-section">
        <h3>Inventory Insights</h3>
        <div class="chart-placeholder">
            📦 Low Stock Items: <?php echo isset($stats['low_stock_count']) ? $stats['low_stock_count'] : '0'; ?> products need restocking
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>