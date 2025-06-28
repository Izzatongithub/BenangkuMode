<?php
require_once 'config/database.php';

/**
 * OrderItem Class
 * Handles order items operations
 */
class OrderItem {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Get all order items for an order
     */
    public function getByOrderId($orderId) {
        $sql = "SELECT oi.*, p.name as product_name, p.image 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ? 
                ORDER BY oi.created_at ASC";
        
        return $this->db->fetchAll($sql, [$orderId]);
    }
    
    /**
     * Get order item by ID
     */
    public function getById($id) {
        $sql = "SELECT oi.*, p.name as product_name, p.image 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.id = ?";
        
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Create new order item
     */
    public function create($data) {
        // Validate required fields
        if (empty($data['order_id']) || empty($data['product_name']) || 
            !isset($data['quantity']) || !isset($data['price'])) {
            return false;
        }
        
        // Calculate subtotal
        $subtotal = $data['quantity'] * $data['price'];
        
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['order_id'],
            $data['product_id'] ?? null,
            $data['product_name'],
            $data['quantity'],
            $data['price'],
            $subtotal
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    /**
     * Update order item
     */
    public function update($id, $data) {
        // Calculate subtotal if quantity or price changed
        $subtotal = null;
        if (isset($data['quantity']) && isset($data['price'])) {
            $subtotal = $data['quantity'] * $data['price'];
        }
        
        $sql = "UPDATE order_items SET ";
        $params = [];
        $updates = [];
        
        if (isset($data['product_name'])) {
            $updates[] = "product_name = ?";
            $params[] = $data['product_name'];
        }
        
        if (isset($data['quantity'])) {
            $updates[] = "quantity = ?";
            $params[] = $data['quantity'];
        }
        
        if (isset($data['price'])) {
            $updates[] = "price = ?";
            $params[] = $data['price'];
        }
        
        if ($subtotal !== null) {
            $updates[] = "subtotal = ?";
            $params[] = $subtotal;
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql .= implode(', ', $updates);
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        return $this->db->update($sql, $params);
    }
    
    /**
     * Delete order item
     */
    public function delete($id) {
        $sql = "DELETE FROM order_items WHERE id = ?";
        return $this->db->delete($sql, [$id]);
    }
    
    /**
     * Get order total
     */
    public function getOrderTotal($orderId) {
        $sql = "SELECT SUM(subtotal) as total FROM order_items WHERE order_id = ?";
        $result = $this->db->fetchOne($sql, [$orderId]);
        return $result['total'] ?? 0;
    }
    
    /**
     * Get order item count
     */
    public function getOrderItemCount($orderId) {
        $sql = "SELECT COUNT(*) as count FROM order_items WHERE order_id = ?";
        $result = $this->db->fetchOne($sql, [$orderId]);
        return $result['count'] ?? 0;
    }
    
    /**
     * Validate order item data
     */
    public function validate($data) {
        $errors = [];
        
        if (empty($data['order_id'])) {
            $errors[] = 'Order ID harus diisi';
        }
        
        if (empty($data['product_name'])) {
            $errors[] = 'Nama produk harus diisi';
        }
        
        if (!isset($data['quantity']) || $data['quantity'] <= 0) {
            $errors[] = 'Jumlah produk harus lebih dari 0';
        }
        
        if (!isset($data['price']) || $data['price'] < 0) {
            $errors[] = 'Harga produk tidak boleh negatif';
        }
        
        return $errors;
    }
    
    /**
     * Format order item for display
     */
    public function formatOrderItem($item) {
        if (!$item) {
            return null;
        }
        
        $item['formatted_price'] = formatCurrency($item['price']);
        $item['formatted_subtotal'] = formatCurrency($item['subtotal']);
        $item['created_date'] = formatDate($item['created_at']);
        
        return $item;
    }
    
    /**
     * Format multiple order items
     */
    public function formatOrderItems($items) {
        return array_map([$this, 'formatOrderItem'], $items);
    }
    
    /**
     * Check if order item exists
     */
    public function exists($id) {
        $sql = "SELECT COUNT(*) as count FROM order_items WHERE id = ?";
        $result = $this->db->fetchOne($sql, [$id]);
        return ($result['count'] ?? 0) > 0;
    }
    
    /**
     * Get order items with product details
     */
    public function getWithProductDetails($orderId) {
        $sql = "SELECT oi.*, p.name as original_product_name, p.image, p.description 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ? 
                ORDER BY oi.created_at ASC";
        
        return $this->db->fetchAll($sql, [$orderId]);
    }
}
?> 