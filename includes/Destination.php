<?php
/**
 * Destination Class for BenangkuMode
 * 
 * Handles all tourist destination-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Destination {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    /**
     * Get all destinations with optional filters
     */
    public function getAll($filters = []) {
        $sql = "SELECT d.*, dc.name as category_name, dc.icon as category_icon, dc.color as category_color
                FROM destinations d 
                LEFT JOIN destination_categories dc ON d.category_id = dc.id 
                WHERE d.is_active = 1";
        
        $params = [];
        
        // Apply filters
        if (!empty($filters['category'])) {
            $sql .= " AND d.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (d.name LIKE ? OR d.description LIKE ? OR d.location LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (isset($filters['featured'])) {
            $sql .= " AND d.is_featured = ?";
            $params[] = $filters['featured'] ? 1 : 0;
        }
        
        if (!empty($filters['min_rating'])) {
            $sql .= " AND d.rating >= ?";
            $params[] = $filters['min_rating'];
        }
        
        $sql .= " ORDER BY d.rating DESC, d.review_count DESC";
        
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
     * Get destination by ID
     */
    public function getById($id) {
        $sql = "SELECT d.*, dc.name as category_name, dc.icon as category_icon, dc.color as category_color
                FROM destinations d 
                LEFT JOIN destination_categories dc ON d.category_id = dc.id 
                WHERE d.id = ? AND d.is_active = 1";
        
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Get featured destinations
     */
    public function getFeatured($limit = 6) {
        return $this->getAll(['featured' => true, 'limit' => $limit]);
    }
    
    /**
     * Get destinations by category
     */
    public function getByCategory($categoryId, $limit = null) {
        $filters = ['category' => $categoryId];
        if ($limit) {
            $filters['limit'] = $limit;
        }
        return $this->getAll($filters);
    }
    
    /**
     * Search destinations
     */
    public function search($searchTerm, $limit = null) {
        $filters = ['search' => $searchTerm];
        if ($limit) {
            $filters['limit'] = $limit;
        }
        return $this->getAll($filters);
    }
    
    /**
     * Get destination categories
     */
    public function getCategories() {
        $sql = "SELECT * FROM destination_categories WHERE is_active = 1 ORDER BY name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Create new destination
     */
    public function create($data) {
        $sql = "INSERT INTO destinations (name, description, category_id, location, address, latitude, longitude, 
                rating, review_count, operating_hours, ticket_price, contact, main_image, images, features, tips, facilities, is_featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['name'],
            $data['description'],
            $data['category_id'],
            $data['location'],
            $data['address'],
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['rating'] ?? 0.00,
            $data['review_count'] ?? 0,
            $data['operating_hours'],
            $data['ticket_price'],
            $data['contact'],
            $data['main_image'] ?? null,
            $data['images'] ? json_encode($data['images']) : null,
            $data['features'] ? json_encode($data['features']) : null,
            $data['tips'] ? json_encode($data['tips']) : null,
            $data['facilities'] ? json_encode($data['facilities']) : null,
            $data['is_featured'] ?? false
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    /**
     * Update destination
     */
    public function update($id, $data) {
        $sql = "UPDATE destinations SET 
                name = ?, 
                description = ?, 
                category_id = ?, 
                location = ?, 
                address = ?, 
                latitude = ?, 
                longitude = ?, 
                rating = ?, 
                review_count = ?, 
                operating_hours = ?, 
                ticket_price = ?, 
                contact = ?, 
                main_image = ?, 
                images = ?, 
                features = ?, 
                tips = ?, 
                facilities = ?, 
                is_featured = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $params = [
            $data['name'],
            $data['description'],
            $data['category_id'],
            $data['location'],
            $data['address'],
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['rating'] ?? 0.00,
            $data['review_count'] ?? 0,
            $data['operating_hours'],
            $data['ticket_price'],
            $data['contact'],
            $data['main_image'] ?? null,
            $data['images'] ? json_encode($data['images']) : null,
            $data['features'] ? json_encode($data['features']) : null,
            $data['tips'] ? json_encode($data['tips']) : null,
            $data['facilities'] ? json_encode($data['facilities']) : null,
            $data['is_featured'] ?? false,
            $id
        ];
        
        return $this->db->update($sql, $params);
    }
    
    /**
     * Delete destination (soft delete)
     */
    public function delete($id) {
        $sql = "UPDATE destinations SET is_active = 0 WHERE id = ?";
        return $this->db->update($sql, [$id]);
    }
    
    /**
     * Update destination rating
     */
    public function updateRating($id, $rating, $reviewCount = null) {
        $sql = "UPDATE destinations SET rating = ?";
        $params = [$rating];
        
        if ($reviewCount !== null) {
            $sql .= ", review_count = ?";
            $params[] = $reviewCount;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        return $this->db->update($sql, $params);
    }
    
    /**
     * Get destination count
     */
    public function getCount($filters = []) {
        $sql = "SELECT COUNT(*) as count FROM destinations d WHERE d.is_active = 1";
        $params = [];
        
        // Apply filters
        if (!empty($filters['category'])) {
            $sql .= " AND d.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (d.name LIKE ? OR d.description LIKE ? OR d.location LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'];
    }
    
    /**
     * Get destinations with pagination
     */
    public function getPaginated($page = 1, $perPage = 12, $filters = []) {
        $offset = ($page - 1) * $perPage;
        $filters['limit'] = $perPage;
        $filters['offset'] = $offset;
        
        $destinations = $this->getAll($filters);
        $total = $this->getCount($filters);
        
        return [
            'destinations' => $destinations,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page,
            'per_page' => $perPage
        ];
    }
    
    /**
     * Get related destinations
     */
    public function getRelated($destinationId, $limit = 4) {
        $destination = $this->getById($destinationId);
        if (!$destination) {
            return [];
        }
        
        $sql = "SELECT d.*, dc.name as category_name, dc.icon as category_icon, dc.color as category_color
                FROM destinations d 
                LEFT JOIN destination_categories dc ON d.category_id = dc.id 
                WHERE d.is_active = 1 
                AND d.category_id = ? 
                AND d.id != ? 
                ORDER BY d.rating DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$destination['category_id'], $destinationId, $limit]);
    }
    
    /**
     * Get destinations by rating range
     */
    public function getByRatingRange($minRating, $maxRating, $limit = null) {
        $filters = [
            'min_rating' => $minRating
        ];
        
        if ($limit) {
            $filters['limit'] = $limit;
        }
        
        return $this->getAll($filters);
    }
    
    /**
     * Get top rated destinations
     */
    public function getTopRated($limit = 6) {
        $sql = "SELECT d.*, dc.name as category_name, dc.icon as category_icon, dc.color as category_color
                FROM destinations d 
                LEFT JOIN destination_categories dc ON d.category_id = dc.id 
                WHERE d.is_active = 1 
                ORDER BY d.rating DESC, d.review_count DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Get recently added destinations
     */
    public function getRecent($limit = 6) {
        $sql = "SELECT d.*, dc.name as category_name, dc.icon as category_icon, dc.color as category_color
                FROM destinations d 
                LEFT JOIN destination_categories dc ON d.category_id = dc.id 
                WHERE d.is_active = 1 
                ORDER BY d.created_at DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Get destinations by location
     */
    public function getByLocation($location, $limit = null) {
        $sql = "SELECT d.*, dc.name as category_name, dc.icon as category_icon, dc.color as category_color
                FROM destinations d 
                LEFT JOIN destination_categories dc ON d.category_id = dc.id 
                WHERE d.is_active = 1 
                AND d.location LIKE ? 
                ORDER BY d.rating DESC";
        
        $params = ['%' . $location . '%'];
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get destinations within radius (for future map features)
     */
    public function getWithinRadius($lat, $lng, $radius = 10, $limit = null) {
        $sql = "SELECT d.*, dc.name as category_name, dc.icon as category_icon, dc.color as category_color,
                       (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
                FROM destinations d 
                LEFT JOIN destination_categories dc ON d.category_id = dc.id 
                WHERE d.is_active = 1 
                HAVING distance <= ? 
                ORDER BY distance";
        
        $params = [$lat, $lng, $lat, $radius];
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get destinations statistics
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total_destinations,
                    AVG(rating) as avg_rating,
                    SUM(review_count) as total_reviews,
                    COUNT(CASE WHEN is_featured = 1 THEN 1 END) as featured_count
                FROM destinations 
                WHERE is_active = 1";
        
        return $this->db->fetchOne($sql);
    }
    
    /**
     * Get destinations by category with count
     */
    public function getByCategoryWithCount() {
        $sql = "SELECT dc.name, dc.icon, dc.color, COUNT(d.id) as count
                FROM destination_categories dc 
                LEFT JOIN destinations d ON dc.id = d.category_id AND d.is_active = 1
                WHERE dc.is_active = 1
                GROUP BY dc.id
                ORDER BY count DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Validate destination data
     */
    public function validate($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Nama destinasi harus diisi';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'Deskripsi destinasi harus diisi';
        }
        
        if (empty($data['category_id'])) {
            $errors[] = 'Kategori destinasi harus dipilih';
        }
        
        if (empty($data['location'])) {
            $errors[] = 'Lokasi destinasi harus diisi';
        }
        
        if (empty($data['address'])) {
            $errors[] = 'Alamat destinasi harus diisi';
        }
        
        if (isset($data['rating']) && ($data['rating'] < 0 || $data['rating'] > 5)) {
            $errors[] = 'Rating harus antara 0-5';
        }
        
        if (isset($data['latitude']) && !is_numeric($data['latitude'])) {
            $errors[] = 'Latitude harus berupa angka';
        }
        
        if (isset($data['longitude']) && !is_numeric($data['longitude'])) {
            $errors[] = 'Longitude harus berupa angka';
        }
        
        return $errors;
    }
    
    /**
     * Format destination data for display
     */
    public function formatDestination($destination) {
        if (!$destination) {
            return null;
        }
        
        $destination['images_array'] = $destination['images'] ? json_decode($destination['images'], true) : [];
        $destination['features_array'] = $destination['features'] ? json_decode($destination['features'], true) : [];
        $destination['tips_array'] = $destination['tips'] ? json_decode($destination['tips'], true) : [];
        $destination['facilities_array'] = $destination['facilities'] ? json_decode($destination['facilities'], true) : [];
        $destination['created_date'] = formatDate($destination['created_at']);
        $destination['updated_date'] = formatDate($destination['updated_at']);
        
        return $destination;
    }
    
    /**
     * Format multiple destinations
     */
    public function formatDestinations($destinations) {
        return array_map([$this, 'formatDestination'], $destinations);
    }
}
?> 