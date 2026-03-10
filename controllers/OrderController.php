<?php
// controllers/OrderController.php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../utils/logger.php';

class OrderController {
    private $orderModel;
    private $orderItemModel;
    private $inventoryModel;
    private $productModel;
    
    public function __construct() {
        try {
            $this->orderModel = new Order();
            // Share the database connection with other models for transaction support
            $this->orderItemModel = new OrderItem($this->orderModel->db);
            $this->inventoryModel = new Inventory($this->orderModel->db);
            $this->productModel = new Product($this->orderModel->db);
        } catch (Exception $e) {
            // Log the error but don't fail - let the API method handle it
            Logger::error('OrderController constructor error: ' . $e->getMessage());
        }
    }
    
    /**
     * Create new order(s) - one per seller
     * In a multi-seller scenario, items from different sellers create separate orders
     */
    public function create($data) {
        if (!Session::isLoggedIn() || Session::getUserType() !== 'buyer') {
            throw new Exception('Unauthorized: User must be logged in as buyer');
        }
        
        // Ensure models are initialized (in case constructor failed)
        try {
            if (!$this->orderModel) {
                $this->orderModel = new Order();
            }
            if (!$this->productModel) {
                $this->productModel = new Product($this->orderModel->db);
            }
            if (!$this->orderItemModel) {
                $this->orderItemModel = new OrderItem($this->orderModel->db);
            }
            if (!$this->inventoryModel) {
                $this->inventoryModel = new Inventory($this->orderModel->db);
            }
        } catch (Exception $e) {
            throw new Exception('Failed to initialize models: ' . $e->getMessage());
        }
        
        try {
            // Group items by seller
            $itemsBySeller = [];
            foreach ($data['items'] as $item) {
                $productId = (int)($item['product_id'] ?? $item['id']);
                if (!$productId) {
                    continue;
                }
                
                $product = $this->productModel->find($productId);
                
                if (!$product) {
                    Logger::error("Product not found: {$productId}");
                    continue;
                }
                
                $sellerId = $product['seller_id'];
                if (!isset($itemsBySeller[$sellerId])) {
                    $itemsBySeller[$sellerId] = [];
                }
                
                $itemsBySeller[$sellerId][] = array_merge($item, ['seller_id' => $sellerId]);
            }
            
            if (empty($itemsBySeller)) {
                throw new Exception("No valid items found in order");
            }
            
            // Create orders for each seller
            $orderIds = [];
            
            if (!$this->orderModel->db) {
                throw new Exception('Database connection not available');
            }
            
            $this->orderModel->db->beginTransaction();
            
            foreach ($itemsBySeller as $sellerId => $items) {
                $orderId = $this->processSellerOrder([
                    'buyer_id' => Session::getUserId(),
                    'seller_id' => $sellerId,
                    'items' => $items
                ]);
                
                if (!$orderId) {
                    $this->orderModel->db->rollBack();
                    throw new Exception("Failed to create order for seller {$sellerId}");
                }
                
                $orderIds[] = $orderId;
            }
            
            $this->orderModel->db->commit();
            
            // Trigger price recalculation for affected products (after commit)
            foreach ($data['items'] as $item) {
                $productId = (int)($item['product_id'] ?? $item['id']);
                try {
                    require_once __DIR__ . '/../services/PricingEngine.php';
                    $engine = new PricingEngine();
                    $engine->checkAndUpdatePrice($productId);
                } catch (Exception $e) {
                    Logger::error("Price update failed for product {$productId}: " . $e->getMessage());
                }
            }
            
            Logger::info("Orders created successfully - Order IDs: " . implode(',', $orderIds));
            return $orderIds[0] ?? false; // Return first order ID for now
            
        } catch (Exception $e) {
            if ($this->orderModel && $this->orderModel->db) {
                try {
                    $this->orderModel->db->rollBack();
                } catch (Throwable $rollbackError) {
                    Logger::error("Rollback error: " . $rollbackError->getMessage());
                }
            }
            Logger::error("OrderController::create error: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
            throw $e; // Re-throw so the API handler can catch it
        }
    }
    
    /**
     * Process a single seller order
     */
    private function processSellerOrder($data) {
        error_log("processSellerOrder called for seller: " . $data['seller_id']);
        
        // Create the order
        $orderId = $this->orderModel->createOrder([
            'buyer_id' => $data['buyer_id'],
            'seller_id' => $data['seller_id']
        ]);
        
        error_log("Order created with ID: " . var_export($orderId, true));
        
        if (!$orderId) {
            throw new Exception("Failed to create order for seller");
        }
        
        // Process each order item and reduce inventory
        $total = 0;
        foreach ($data['items'] as $item) {
            $productId = (int)($item['product_id'] ?? $item['id']);
            $quantity = (int)($item['quantity'] ?? 0);
            
            error_log("Processing item - Product ID: {$productId}, Quantity: {$quantity}, Order ID: {$orderId}");
            
            if (!$productId || $quantity <= 0) {
                Logger::error("Invalid item data: product_id={$productId}, quantity={$quantity}");
                return false;
            }
            
            // Get product again for inventory check
            $product = $this->productModel->find($productId);
            if (!$product) {
                Logger::error("Product not found: {$productId}");
                return false;
            }
            
            // Check inventory availability
            if (!$this->inventoryModel->isAvailable($productId, $quantity)) {
                Logger::error("Insufficient inventory for product {$productId}");
                return false;
            }
            
            // Create order item record
            $price = (float)($item['price'] ?? $item['current_price'] ?? 0);
            $subtotal = $price * $quantity;
            
            error_log("Creating order item - Order ID: {$orderId}, Product ID: {$productId}, Price: {$price}");
            
            $itemId = $this->orderItemModel->createItem([
                'order_id' => $orderId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $price,
                'subtotal' => $subtotal
            ]);
            
            error_log("Order item created with ID: " . var_export($itemId, true));
            
            if (!$itemId) {
                Logger::error("Failed to create order item for order {$orderId}");
                return false;
            }
            
            // Reduce inventory
            $inventory = $this->inventoryModel->getByProductId($productId);
            if (!$inventory) {
                Logger::error("Inventory record not found for product {$productId}");
                return false;
            }
            
            $newQuantity = max(0, $inventory['quantity_available'] - $quantity);
            
            if (!$this->inventoryModel->update($inventory['inventory_id'], [
                'quantity_available' => $newQuantity,
                'last_restocked' => date('Y-m-d H:i:s')
            ])) {
                Logger::error("Failed to update inventory for product {$productId}");
                return false;
            }
            
            Logger::info("Order item created and inventory reduced - Product: {$productId}, Quantity: {$quantity}, Order: {$orderId}");
            
            $total += $subtotal;
        }
        
        // Update order total
        if (!$this->orderModel->update($orderId, ['total_amount' => $total])) {
            Logger::error("Failed to update order total for order {$orderId}");
            return false;
        }
        
        Logger::info("Seller order processed - Order ID: {$orderId}, Total: {$total}");
        return $orderId;
    }
    
    /**
     * Get buyer's orders
     */
    public function getBuyerOrders() {
        return $this->orderModel->getOrdersByBuyer(Session::getUserId());
    }
    
    /**
     * Get seller's orders
     */
    public function getSellerOrders() {
        return $this->orderModel->getOrdersBySeller(Session::getUserId());
    }
    
    /**
     * Get order details
     */
    public function getOrderDetails($orderId) {
        $order = $this->orderModel->find($orderId);
        
        if (!$order) {
            return null;
        }
        
        $order['items'] = $this->orderItemModel->getByOrderId($orderId);
        
        return $order;
    }
    
    /**
     * Update order status
     */
    public function updateStatus($orderId, $status) {
        $order = $this->orderModel->find($orderId);
        
        if (!$order) {
            return false;
        }
        
        if ($order['seller_id'] != Session::getUserId()) {
            return false;
        }
        
        return $this->orderModel->update($orderId, ['order_status' => $status]);
    }
    
    /**
     * Cancel order
     */
    public function cancel($orderId) {
        $order = $this->orderModel->find($orderId);
        
        if (!$order || $order['buyer_id'] != Session::getUserId()) {
            return false;
        }
        
        return $this->orderModel->update($orderId, ['order_status' => 'cancelled']);
    }

    /**
     * API handler for creating orders
     * Called from POST /orders route
     */
    public function createOrderApi() {
        // Write a log entry immediately
        error_log("OrderController::createOrderApi called");
        
        // Start output buffering to prevent any stray output
        ob_start();
        
        try {
            // Set JSON response header FIRST
            if (!headers_sent()) {
                header('Content-Type: application/json');
            }
            
            error_log("Checking authentication...");
            
            // Validate authentication
            if (!Session::isLoggedIn()) {
                error_log("User not logged in");
                http_response_code(401);
                echo json_encode(['error' => 'Not logged in']);
                ob_end_flush();
                return;
            }
            
            if (Session::getUserType() !== 'buyer') {
                error_log("User is not a buyer, type: " . Session::getUserType());
                http_response_code(401);
                echo json_encode(['error' => 'Only buyers can create orders']);
                ob_end_flush();
                return;
            }

            error_log("Reading POST data...");
            
            // Get POST data
            $inputRaw = file_get_contents('php://input');
            $input = json_decode($inputRaw, true);

            error_log("Input received: " . json_encode($input));
            
            // Validate input
            if (!isset($input['items']) || empty($input['items'])) {
                error_log("No items in order");
                http_response_code(400);
                echo json_encode(['error' => 'Order items required']);
                ob_end_flush();
                return;
            }

            // Prepare order data
            $orderData = [
                'items' => $input['items'],
                'shipping_address' => $input['shipping_address'] ?? null,
                'notes' => $input['notes'] ?? null
            ];

            error_log("Creating order...");
            
            // Create order
            $orderId = $this->create($orderData);

            if ($orderId) {
                error_log("Order created successfully: " . $orderId);
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'order_id' => $orderId
                ]);
            } else {
                error_log("Order creation failed - create() returned false");
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create order', 'debug' => 'create() returned false']);
            }
            
            ob_end_flush();
            
        } catch (Throwable $e) {
            error_log("Exception caught in createOrderApi: " . $e->getMessage());
            error_log("Exception file: " . $e->getFile() . " line: " . $e->getLine());
            
            // Clear any output that may have been generated
            ob_clean();
            
            // Ensure JSON header
            if (!headers_sent()) {
                header('Content-Type: application/json');
            }
            
            http_response_code(500);
            
            $errorMsg = $e->getMessage();
            $errorFile = $e->getFile();
            $errorLine = $e->getLine();
            
            Logger::error("OrderController::createOrderApi error: {$errorMsg} in {$errorFile}:{$errorLine}");
            Logger::error("Trace: " . $e->getTraceAsString());
            
            echo json_encode([
                'error' => 'Server error',
                'message' => $errorMsg,
                'file' => defined('APP_DEBUG') && APP_DEBUG ? $errorFile : null,
                'line' => defined('APP_DEBUG') && APP_DEBUG ? $errorLine : null,
            ]);
            
            ob_end_flush();
        }
    }
}
?>
