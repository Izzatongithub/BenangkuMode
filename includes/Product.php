<?php
/**
 * Product Class for BenangkuMode
 * 
 * Handles all product-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    /**
     * Get all products with optional filters
     */
    public function getAll($filters = []) {
        $sql = "SELECT p.*, pc.name as category_name 
                FROM products p 
                LEFT JOIN product_categories pc ON p.category_id = pc.id 
                WHERE p.is_active = 1";
        
        $params = [];
        
        // Apply filters
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (isset($filters['featured'])) {
            $sql .= " AND p.is_featured = ?";
            $params[] = $filters['featured'] ? 1 : 0;
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        // Add limit and offset for pagination
        if (isset($filters['limit'])) {
            $sql .= " LIMIT ?";
            $params[] = $filters['limit'];
            
            if (isset($filters['offset'])) {
                $sql .= " OFFSET ?";
                $params[] = $filters['offset'];
            }
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get product by ID
     */
    public function getById($id) {
        $sql = "SELECT p.*, pc.name as category_name 
                FROM products p 
                LEFT JOIN product_categories pc ON p.category_id = pc.id 
                WHERE p.id = ? AND p.is_active = 1";
        
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Get featured products
     */
    public function getFeatured($limit = 6) {
        return $this->getAll(['featured' => true, 'limit' => $limit]);
    }
    
    /**
     * Get products by category
     */
    public function getByCategory($categoryId, $limit = null) {
        $filters = ['category' => $categoryId];
        if ($limit) {
            $filters['limit'] = $limit;
        }
        return $this->getAll($filters);
    }
    
    /**
     * Search products
     */
    public function search($searchTerm, $limit = null) {
        $filters = ['search' => $searchTerm];
        if ($limit) {
            $filters['limit'] = $limit;
        }
        return $this->getAll($filters);
    }
    
    /**
     * Get product categories
     */
    public function getCategories() {
        $sql = "SELECT * FROM product_categories WHERE is_active = 1 ORDER BY name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Create new product
     */
    public function create($data) {
        $sql = "INSERT INTO products (name, description, price, category_id, image, images, stock_quantity, is_featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category_id'],
            $data['image'] ?? null,
            $data['images'] ? json_encode($data['images']) : null,
            $data['stock_quantity'] ?? 0,
            $data['is_featured'] ?? false
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    /**
     * Update product
     */
    public function update($id, $data) {
        $sql = "UPDATE products SET 
                name = ?, 
                description = ?, 
                price = ?, 
                category_id = ?, 
                image = ?, 
                images = ?, 
                stock_quantity = ?, 
                is_featured = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $params = [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category_id'],
            $data['image'] ?? null,
            $data['images'] ? json_encode($data['images']) : null,
            $data['stock_quantity'] ?? 0,
            $data['is_featured'] ?? false,
            $id
        ];
        
        return $this->db->update($sql, $params);
    }
    
    /**
     * Delete product (soft delete)
     */
    public function delete($id) {
        $sql = "UPDATE products SET is_active = 0 WHERE id = ?";
        return $this->db->update($sql, [$id]);
    }
    
    /**
     * Update stock quantity
     */
    public function updateStock($id, $quantity) {
        $sql = "UPDATE products SET stock_quantity = ? WHERE id = ?";
        return $this->db->update($sql, [$quantity, $id]);
    }
    
    /**
     * Get product count
     */
    public function getCount($filters = []) {
        $sql = "SELECT COUNT(*) as count FROM products p WHERE p.is_active = 1";
        $params = [];
        
        // Apply filters
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'];
    }
    
    /**
     * Get products with pagination
     */
    public function getPaginated($page = 1, $perPage = 12, $filters = []) {
        $offset = ($page - 1) * $perPage;
        $filters['limit'] = $perPage;
        $filters['offset'] = $offset;
        
        $products = $this->getAll($filters);
        $total = $this->getCount($filters);
        
        return [
            'products' => $products,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page,
            'per_page' => $perPage
        ];
    }
    
    /**
     * Get related products
     */
    public function getRelated($productId, $limit = 4) {
        $product = $this->getById($productId);
        if (!$product) {
            return [];
        }
        
        $sql = "SELECT p.*, pc.name as category_name 
                FROM products p 
                LEFT JOIN product_categories pc ON p.category_id = pc.id 
                WHERE p.is_active = 1 
                AND p.category_id = ? 
                AND p.id != ? 
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$product['category_id'], $productId, $limit]);
    }
    
    /**
     * Get products by price range
     */
    public function getByPriceRange($minPrice, $maxPrice, $limit = null) {
        $filters = [
            'min_price' => $minPrice,
            'max_price' => $maxPrice
        ];
        
        if ($limit) {
            $filters['limit'] = $limit;
        }
        
        return $this->getAll($filters);
    }
    
    /**
     * Get low stock products
     */
    public function getLowStock($threshold = 5) {
        $sql = "SELECT p.*, pc.name as category_name 
                FROM products p 
                LEFT JOIN product_categories pc ON p.category_id = pc.id 
                WHERE p.is_active = 1 
                AND p.stock_quantity <= ? 
                ORDER BY p.stock_quantity ASC";
        
        return $this->db->fetchAll($sql, [$threshold]);
    }
    
    /**
     * Get recently added products
     */
    public function getRecent($limit = 6) {
        $sql = "SELECT p.*, pc.name as category_name 
                FROM products p 
                LEFT JOIN product_categories pc ON p.category_id = pc.id 
                WHERE p.is_active = 1 
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Get best selling products (based on order items)
     */
    public function getBestSelling($limit = 6) {
        $sql = "SELECT p.*, pc.name as category_name, 
                       COALESCE(SUM(oi.quantity), 0) as total_sold
                FROM products p 
                LEFT JOIN product_categories pc ON p.category_id = pc.id 
                LEFT JOIN order_items oi ON p.id = oi.product_id 
                WHERE p.is_active = 1 
                GROUP BY p.id 
                ORDER BY total_sold DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Validate product data
     */
    public function validate($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Nama produk harus diisi';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'Deskripsi produk harus diisi';
        }
        
        if (!isset($data['price']) || $data['price'] <= 0) {
            $errors[] = 'Harga produk harus lebih dari 0';
        }
        
        if (empty($data['category_id'])) {
            $errors[] = 'Kategori produk harus dipilih';
        }
        
        if (isset($data['stock_quantity']) && $data['stock_quantity'] < 0) {
            $errors[] = 'Stok produk tidak boleh negatif';
        }
        
        return $errors;
    }
    
    /**
     * Format product data for display
     */
    public function formatProduct($product) {
        if (!$product) {
            return null;
        }
        
        $product['formatted_price'] = formatCurrency($product['price']);
        $product['images_array'] = $product['images'] ? json_decode($product['images'], true) : [];
        $product['created_date'] = formatDate($product['created_at']);
        $product['updated_date'] = formatDate($product['updated_at']);
        
        return $product;
    }
    
    /**
     * Format multiple products
     */
    public function formatProducts($products) {
        return array_map([$this, 'formatProduct'], $products);
    }
}
?> 