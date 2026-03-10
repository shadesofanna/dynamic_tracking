<?php
// models/Order.php

require_once __DIR__ . '/../core/Model.php';

class Order extends Model {
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    
    public function createOrder($data) {
        // Get seller_id from first item if available, otherwise use 0 (for multi-seller orders)
        $sellerId = isset($data['seller_id']) ? $data['seller_id'] : 0;
        
        $orderData = [
            'buyer_id' => $data['buyer_id'],
            'seller_id' => $sellerId,
            'order_number' => 'ORD-' . strtoupper(substr(uniqid(), -8)),
            'order_status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->create($orderData);
    }
    
    public function getOrdersByBuyer($buyerId) {
        $orders = $this->findAll(['buyer_id' => $buyerId], 'created_at DESC');
        
        // Transform field names and load order items
        foreach ($orders as &$order) {
            // Map database field names to expected view names
            $order['id'] = $order['order_id'];
            $order['status'] = $order['order_status'];
            $order['payment_method'] = $order['payment_method'] ?? 'Not specified';
            
            // Load order items
            require_once __DIR__ . '/OrderItem.php';
            $orderItemModel = new OrderItem($this->db);
            $order['items'] = $orderItemModel->getByOrderId($order['order_id']);
        }
        
        return $orders;
    }
    
    public function getOrdersBySeller($sellerId) {
        $query = "SELECT DISTINCT o.*, u.full_name as buyer_name,
                    SUM(oi.subtotal) as order_total
                  FROM {$this->table} o
                  INNER JOIN order_items oi ON o.order_id = oi.order_id
                  INNER JOIN products p ON oi.product_id = p.product_id
                  INNER JOIN users u ON o.buyer_id = u.user_id
                  WHERE p.seller_id = :seller_id
                  GROUP BY o.order_id
                  ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':seller_id' => $sellerId]);
        $orders = $stmt->fetchAll();
        
        // Transform field names and load order items
        foreach ($orders as &$order) {
            $order['customer_name'] = $order['buyer_name'];
            $order['status'] = $order['order_status'];
            $order['total_amount'] = $order['order_total'] ?? 0;
            
            // Load order items - query already filters by seller_id so all items are this seller's
            require_once __DIR__ . '/OrderItem.php';
            $orderItemModel = new OrderItem($this->db);
            $order['items'] = $orderItemModel->getByOrderId($order['order_id']);
        }
        
        return $orders;
    }
    
    public function getRevenueStats($sellerId, $period = 'month') {
        $dateRange = $this->getDateRange($period);
        $query = "SELECT 
                    SUM(oi.subtotal) as total_revenue,
                    COUNT(DISTINCT o.order_id) as total_orders,
                    AVG(oi.subtotal) as avg_order_value
                  FROM {$this->table} o
                  INNER JOIN order_items oi ON o.order_id = oi.order_id
                  INNER JOIN products p ON oi.product_id = p.product_id
                  WHERE p.seller_id = :seller_id
                  AND o.created_at >= :created_at";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':seller_id' => $sellerId, ':created_at' => $dateRange['start']]);
        return $stmt->fetch();
    }
    
    public function getOrderStatsByStatus($sellerId) {
        $query = "SELECT o.order_status, COUNT(*) as count
                  FROM {$this->table} o
                  INNER JOIN order_items oi ON o.order_id = oi.order_id
                  INNER JOIN products p ON oi.product_id = p.product_id
                  WHERE p.seller_id = :seller_id
                  GROUP BY o.order_status";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':seller_id' => $sellerId]);
        return $stmt->fetchAll();
    }
    
    private function getDateRange($period) {
        $end = date('Y-m-d H:i:s');
        switch ($period) {
            case 'day':
                $start = date('Y-m-d 00:00:00');
                break;
            case 'week':
                $start = date('Y-m-d H:i:s', strtotime('-7 days'));
                break;
            case 'month':
                $start = date('Y-m-d H:i:s', strtotime('-30 days'));
                break;
            case 'year':
                $start = date('Y-01-01 00:00:00');
                break;
            default:
                $start = date('Y-m-d H:i:s', strtotime('-30 days'));
        }
        return ['start' => $start, 'end' => $end];
    }
}
?>
