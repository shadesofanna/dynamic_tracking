<?php
// models/OrderItem.php

require_once __DIR__ . '/../core/Model.php';

class OrderItem extends Model {
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    
    public function getByOrderId($orderId) {
        // Join with products to get product details
        $query = "SELECT oi.*, 
                         p.product_name, 
                         p.sku, 
                         p.image_url,
                         oi.unit_price AS price
                  FROM {$this->table} oi
                  LEFT JOIN products p ON oi.product_id = p.product_id
                  WHERE oi.order_id = :order_id
                  ORDER BY oi.created_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':order_id' => $orderId]);
        $items = $stmt->fetchAll();
        
        // Extract filename from full image path and map to product_image for view compatibility
        foreach ($items as &$item) {
            $imageUrl = $item['image_url'] ?? '';
            // Extract just the filename from the path (e.g., "filename.png" from "/assets/images/products/filename.png")
            $item['product_image'] = basename($imageUrl);
        }
        
        return $items;
    }
    
    public function createItem($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
}
?>
