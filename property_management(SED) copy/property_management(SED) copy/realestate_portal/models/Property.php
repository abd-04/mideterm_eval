<?php
/**
 * Property Model Class - Enhanced Version
 * Software Construction & Development - Midterm Project
 * MVC Architecture - Model Layer
 * 
 * This class handles all property-related database operations and business logic.
 * Part of the Model layer in MVC pattern.
 * Enhanced with advanced features like search, filtering, and statistics.
 */

require_once __DIR__ . '/../config/Database.php';

class Property {
    private $db;
    
    /**
     * Constructor - initializes database connection using Singleton pattern
     */
    public function __construct() {
        // Get database instance using Singleton pattern
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new property listing
     * 
     * @param array $propertyData Array containing property information
     * @return int The ID of the newly created property
     */
    public function create($propertyData) {
        $sql = "INSERT INTO properties (
            user_id, title, description, city_id, area_id, property_type_id, 
            bedrooms, bathrooms, area_size, area_unit, price, price_unit, purpose, main_image
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->executeQuery($sql, [
                $propertyData['user_id'],
                $propertyData['title'],
                $propertyData['description'],
                $propertyData['city_id'],
                $propertyData['area_id'],
                $propertyData['property_type_id'],
                $propertyData['bedrooms'],
                $propertyData['bathrooms'],
                $propertyData['area_size'],
                $propertyData['area_unit'],
                $propertyData['price'],
                $propertyData['price_unit'],
                $propertyData['purpose'],
                $propertyData['main_image']
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Failed to create property listing');
        }
    }
    
    /**
     * Update existing property
     * 
     * @param int $propertyId Property ID
     * @param array $propertyData Array containing updated property information
     * @return bool True if successful
     */
    public function update($propertyId, $propertyData) {
        $sql = "UPDATE properties SET 
            title = ?, description = ?, city_id = ?, area_id = ?, property_type_id = ?, 
            bedrooms = ?, bathrooms = ?, area_size = ?, area_unit = ?, price = ?, 
            price_unit = ?, purpose = ?, main_image = ?, updated_at = CURRENT_TIMESTAMP
        WHERE id = ? AND user_id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [
                $propertyData['title'],
                $propertyData['description'],
                $propertyData['city_id'],
                $propertyData['area_id'],
                $propertyData['property_type_id'],
                $propertyData['bedrooms'],
                $propertyData['bathrooms'],
                $propertyData['area_size'],
                $propertyData['area_unit'],
                $propertyData['price'],
                $propertyData['price_unit'],
                $propertyData['purpose'],
                $propertyData['main_image'],
                $propertyId,
                $propertyData['user_id']
            ]);
            
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception('Failed to update property');
        }
    }
    
    /**
     * Get property by ID with full details
     * 
     * @param int $id Property ID
     * @return array|null Property data if found, null otherwise
     */
    public function findById($id) {
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            u.email as owner_email,
            u.phone as owner_phone,
            u.profile_image as owner_profile_image,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count,
            (SELECT COUNT(*) FROM favorites f WHERE f.property_id = p.id) as favorite_count
        FROM properties p 
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id 
        WHERE p.id = ? LIMIT 1";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$id]);
            $property = $stmt->fetch();
            
            if ($property) {
                // Get property images
                $property['images'] = $this->getPropertyImages($id);
                
                // Get property amenities
                $property['amenities'] = $this->getPropertyAmenities($id);
            }
            
            return $property;
        } catch (Exception $e) {
            throw new Exception('Failed to find property');
        }
    }
    
    /**
     * Get all properties with advanced filtering and pagination
     * 
     * @param array $filters Array of filter criteria
     * @param int $offset Pagination offset
     * @param int $limit Pagination limit
     * @return array Array of properties
     */
    public function getAll($filters = [], $offset = 0, $limit = 20) {
        $where = ["p.status = 'approved'"];
        $params = [];
        
        // Apply filters
        if (!empty($filters['city_id'])) {
            $where[] = "p.city_id = ?";
            $params[] = $filters['city_id'];
        }
        
        if (!empty($filters['area_id'])) {
            $where[] = "p.area_id = ?";
            $params[] = $filters['area_id'];
        }
        
        if (!empty($filters['property_type_id'])) {
            $where[] = "p.property_type_id = ?";
            $params[] = $filters['property_type_id'];
        }
        
        if (!empty($filters['purpose'])) {
            $where[] = "p.purpose = ?";
            $params[] = $filters['purpose'];
        }
        
        if (!empty($filters['min_price'])) {
            $where[] = "p.price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $where[] = "p.price <= ?";
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['bedrooms'])) {
            if ($filters['bedrooms'] == '5+') {
                $where[] = "p.bedrooms >= 5";
            } else {
                $where[] = "p.bedrooms >= ?";
                $params[] = $filters['bedrooms'];
            }
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(p.title LIKE ? OR p.description LIKE ? OR a.name LIKE ? OR c.name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count,
            (SELECT COUNT(*) FROM favorites f WHERE f.property_id = p.id) as favorite_count
        FROM properties p 
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id 
        WHERE $whereClause 
        ORDER BY p.featured DESC, p.created_at DESC
        LIMIT ?, ?";
        
        $params = array_merge($params, [$offset, $limit]);
        
        try {
            $stmt = $this->db->executeQuery($sql, $params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch properties');
        }
    }
    
    /**
     * Get user's properties
     * 
     * @param int $userId User ID
     * @return array Array of user's properties
     */
    public function getUserProperties($userId) {
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count,
            (SELECT COUNT(*) FROM inquiries i WHERE i.property_id = p.id) as inquiry_count
        FROM properties p
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        WHERE p.user_id = ? 
        ORDER BY p.created_at DESC";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$userId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch user properties');
        }
    }
    
    /**
     * Update property status (for admin)
     * 
     * @param int $propertyId Property ID
     * @param string $status New status
     * @return bool True if successful
     */
    public function updateStatus($propertyId, $status) {
        $sql = "UPDATE properties SET status = ? WHERE id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$status, $propertyId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception('Failed to update property status');
        }
    }
    
    /**
     * Delete property
     * 
     * @param int $propertyId Property ID
     * @param int $userId User ID (for ownership verification)
     * @return bool True if successful
     */
    public function delete($propertyId, $userId) {
        $sql = "DELETE FROM properties WHERE id = ? AND user_id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$propertyId, $userId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception('Failed to delete property');
        }
    }
    
    /**
     * Get featured properties
     * 
     * @param int $limit Number of properties to return
     * @return array Array of featured properties
     */
    public function getFeatured($limit = 6) {
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count
        FROM properties p 
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'approved' AND p.featured = true
        ORDER BY p.created_at DESC 
        LIMIT ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch featured properties');
        }
    }
    
    /**
     * Get recent properties
     * 
     * @param int $limit Number of properties to return
     * @return array Array of recent properties
     */
    public function getRecent($limit = 10) {
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count
        FROM properties p 
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'approved'
        ORDER BY p.created_at DESC 
        LIMIT ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch recent properties');
        }
    }
    
    /**
     * Get popular properties (most viewed)
     * 
     * @param int $limit Number of properties to return
     * @return array Array of popular properties
     */
    public function getPopular($limit = 10) {
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count
        FROM properties p 
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'approved'
        ORDER BY view_count DESC, p.created_at DESC 
        LIMIT ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch popular properties');
        }
    }
    
    /**
     * Get property statistics
     * 
     * @return array Array of statistics
     */
    public function getStatistics() {
        $stats = [];
        
        try {
            // Get property statistics from view
            $sql = "SELECT * FROM property_statistics";
            $stmt = $this->db->executeQuery($sql);
            $stats = $stmt->fetch();
            
            // Add additional statistics
            $stats['properties_today'] = $this->getPropertiesCountToday();
            $stats['properties_this_month'] = $this->getPropertiesCountThisMonth();
            
            return $stats;
        } catch (Exception $e) {
            throw new Exception('Failed to fetch property statistics');
        }
    }
    
    /**
     * Get properties count for today
     * 
     * @return int Number of properties added today
     */
    public function getPropertiesCountToday() {
        $sql = "SELECT COUNT(*) FROM properties WHERE DATE(created_at) = CURRENT_DATE";
        
        try {
            $stmt = $this->db->executeQuery($sql);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch today\'s properties count');
        }
    }
    
    /**
     * Get properties count for this month
     * 
     * @return int Number of properties added this month
     */
    public function getPropertiesCountThisMonth() {
        $sql = "SELECT COUNT(*) FROM properties WHERE MONTH(created_at) = MONTH(CURRENT_DATE) AND YEAR(created_at) = YEAR(CURRENT_DATE)";
        
        try {
            $stmt = $this->db->executeQuery($sql);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch this month\'s properties count');
        }
    }
    
    /**
     * Get property images
     * 
     * @param int $propertyId Property ID
     * @return array Array of property images
     */
    public function getPropertyImages($propertyId) {
        $sql = "SELECT id, property_id, image_url as image_path, sort_order FROM property_images WHERE property_id = ? ORDER BY sort_order ASC";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$propertyId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch property images');
        }
    }
    
    /**
     * Get property amenities
     * 
     * @param int $propertyId Property ID
     * @return array Array of property amenities
     */
    public function getPropertyAmenities($propertyId) {
        $sql = "SELECT a.* FROM amenities a 
                JOIN property_amenities pa ON a.id = pa.amenity_id 
                WHERE pa.property_id = ? AND a.status = 'active'";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$propertyId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch property amenities');
        }
    }
    
    /**
     * Add property view
     * 
     * @param int $propertyId Property ID
     * @param int|null $userId User ID (optional)
     * @param string $ipAddress IP address
     * @param string $userAgent User agent string
     * @return bool True if successful
     */
    public function addView($propertyId, $userId = null, $ipAddress = null, $userAgent = null) {
        $sql = "INSERT INTO property_views (property_id, user_id, ip_address, user_agent) VALUES (?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->executeQuery($sql, [
                $propertyId,
                $userId,
                $ipAddress,
                $userAgent
            ]);
            
            // Update view count in properties table
            $this->updateViewCount($propertyId);
            
            return true;
        } catch (Exception $e) {
            // Don't throw exception for view tracking failures
            return false;
        }
    }
    
    /**
     * Update property view count
     * 
     * @param int $propertyId Property ID
     * @return bool True if successful
     */
    private function updateViewCount($propertyId) {
        $sql = "UPDATE properties SET view_count = (SELECT COUNT(*) FROM property_views WHERE property_id = ?) WHERE id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$propertyId, $propertyId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Toggle property favorite
     * 
     * @param int $propertyId Property ID
     * @param int $userId User ID
     * @return bool True if added to favorites, false if removed
     */
    public function toggleFavorite($propertyId, $userId) {
        // Check if already favorited
        $sql = "SELECT id FROM favorites WHERE user_id = ? AND property_id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$userId, $propertyId]);
            $favorite = $stmt->fetch();
            
            if ($favorite) {
                // Remove from favorites
                $sql = "DELETE FROM favorites WHERE user_id = ? AND property_id = ?";
                $this->db->executeQuery($sql, [$userId, $propertyId]);
                return false;
            } else {
                // Add to favorites
                $sql = "INSERT INTO favorites (user_id, property_id) VALUES (?, ?)";
                $this->db->executeQuery($sql, [$userId, $propertyId]);
                return true;
            }
        } catch (Exception $e) {
            throw new Exception('Failed to toggle favorite');
        }
    }
    
    /**
     * Check if property is favorited by user
     * 
     * @param int $propertyId Property ID
     * @param int $userId User ID
     * @return bool True if favorited
     */
    public function isFavorited($propertyId, $userId) {
        $sql = "SELECT id FROM favorites WHERE user_id = ? AND property_id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$userId, $propertyId]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get user's favorite properties
     * 
     * @param int $userId User ID
     * @return array Array of favorite properties
     */
    public function getUserFavorites($userId) {
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            f.created_at as favorite_date
        FROM properties p
        JOIN favorites f ON p.id = f.property_id
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id
        WHERE f.user_id = ? AND p.status = 'approved'
        ORDER BY f.created_at DESC";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$userId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch user favorites');
        }
    }
    
    /**
     * Get similar properties
     * 
     * @param int $propertyId Property ID
     * @param int $limit Number of similar properties to return
     * @return array Array of similar properties
     */
    public function getSimilarProperties($propertyId, $limit = 6) {
        // First get the current property details
        $currentProperty = $this->findById($propertyId);
        
        if (!$currentProperty) {
            return [];
        }
        
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count
        FROM properties p
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'approved' 
            AND p.id != ?
            AND (p.city_id = ? OR p.property_type_id = ?)
        ORDER BY 
            CASE 
                WHEN p.city_id = ? AND p.property_type_id = ? THEN 1
                WHEN p.city_id = ? THEN 2
                WHEN p.property_type_id = ? THEN 3
                ELSE 4
            END,
            ABS(p.price - ?) ASC,
            p.created_at DESC
        LIMIT ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [
                $propertyId,
                $currentProperty['city_id'],
                $currentProperty['property_type_id'],
                $currentProperty['city_id'],
                $currentProperty['property_type_id'],
                $currentProperty['city_id'],
                $currentProperty['property_type_id'],
                $currentProperty['price'],
                $limit
            ]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch similar properties');
        }
    }
    
    /**
     * Get property types
     * 
     * @return array Array of property types
     */
    public function getPropertyTypes() {
        $sql = "SELECT * FROM property_types WHERE status = 'active' ORDER BY name ASC";
        
        try {
            $stmt = $this->db->executeQuery($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch property types');
        }
    }
    
    /**
     * Get cities
     * 
     * @return array Array of cities
     */
    public function getCities() {
        $sql = "SELECT * FROM cities WHERE status = 'active' ORDER BY name ASC";
        
        try {
            $stmt = $this->db->executeQuery($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch cities');
        }
    }
    
    /**
     * Get areas by city
     * 
     * @param int $cityId City ID
     * @return array Array of areas
     */
    public function getAreasByCity($cityId) {
        $sql = "SELECT * FROM areas WHERE city_id = ? AND status = 'active' ORDER BY name ASC";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$cityId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch areas');
        }
    }
    
    /**
     * Get price range for properties
     * 
     * @param array $filters Filter criteria
     * @return array Min and max prices
     */
    public function getPriceRange($filters = []) {
        $where = ["status = 'approved'"];
        $params = [];
        
        if (!empty($filters['city_id'])) {
            $where[] = "city_id = ?";
            $params[] = $filters['city_id'];
        }
        
        if (!empty($filters['property_type_id'])) {
            $where[] = "property_type_id = ?";
            $params[] = $filters['property_type_id'];
        }
        
        if (!empty($filters['purpose'])) {
            $where[] = "purpose = ?";
            $params[] = $filters['purpose'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM properties WHERE $whereClause";
        
        try {
            $stmt = $this->db->executeQuery($sql, $params);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch price range');
        }
    }
    
    /**
     * Search properties
     * 
     * @param string $searchTerm Search term
     * @param int $limit Number of results to return
     * @return array Array of search results
     */
    public function search($searchTerm, $limit = 10) {
        $sql = "SELECT 
            p.*,
            pt.name as property_type_name,
            pt.icon as property_type_icon,
            c.name as city_name,
            a.name as area_name,
            u.name as owner_name,
            (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count
        FROM properties p
        JOIN property_types pt ON p.property_type_id = pt.id
        JOIN cities c ON p.city_id = c.id
        JOIN areas a ON p.area_id = a.id
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'approved' 
            AND (p.title LIKE ? OR p.description LIKE ? OR a.name LIKE ? OR c.name LIKE ?)
        ORDER BY 
            CASE 
                WHEN p.title LIKE ? THEN 1
                WHEN a.name LIKE ? THEN 2
                WHEN c.name LIKE ? THEN 3
                ELSE 4
            END,
            p.created_at DESC
        LIMIT ?";
        
        $searchTerm = '%' . $searchTerm . '%';
        $params = [
            $searchTerm, $searchTerm, $searchTerm, $searchTerm,
            $searchTerm, $searchTerm, $searchTerm,
            $limit
        ];
        
        try {
            $stmt = $this->db->executeQuery($sql, $params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to search properties');
        }
    }
}