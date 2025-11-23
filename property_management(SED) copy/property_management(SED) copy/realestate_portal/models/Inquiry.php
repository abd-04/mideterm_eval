<?php
/**
 * Inquiry Model Class
 * Software Construction & Development - Midterm Project
 * MVC Architecture - Model Layer
 * 
 * This class handles all inquiry-related database operations and business logic.
 * Part of the Model layer in MVC pattern.
 */

require_once __DIR__ . '/../config/Database.php';

class Inquiry {
    private $db;
    
    /**
     * Constructor - initializes database connection using Singleton pattern
     */
    public function __construct() {
        // Get database instance using Singleton pattern
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new inquiry
     * 
     * @param array $inquiryData Array containing inquiry information
     * @return int The ID of the newly created inquiry
     */
    public function create($inquiryData) {
        $sql = "INSERT INTO inquiries (property_id, name, email, phone, message) VALUES (?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->executeQuery($sql, [
                $inquiryData['property_id'],
                $inquiryData['name'],
                $inquiryData['email'],
                $inquiryData['phone'],
                $inquiryData['message']
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Failed to create inquiry');
        }
    }
    
    /**
     * Get inquiries for a specific property
     * 
     * @param int $propertyId Property ID
     * @return array Array of inquiries
     */
    public function getByProperty($propertyId) {
        $sql = "SELECT * FROM inquiries WHERE property_id = ? ORDER BY created_at DESC";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$propertyId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch property inquiries');
        }
    }
    
    /**
     * Get inquiries for properties owned by a specific user
     * 
     * @param int $userId User ID
     * @return array Array of inquiries with property details
     */
    public function getByUserProperties($userId) {
        $sql = "SELECT i.*, p.title as property_title, c.name as city, a.name as area_name 
                FROM inquiries i 
                JOIN properties p ON i.property_id = p.id 
                LEFT JOIN cities c ON p.city_id = c.id
                LEFT JOIN areas a ON p.area_id = a.id
                WHERE p.user_id = ? 
                ORDER BY i.created_at DESC";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$userId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch user property inquiries: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all inquiries (for admin)
     * 
     * @return array Array of all inquiries with property and user details
     */
    public function getAll() {
        $sql = "SELECT i.*, p.title as property_title, p.city, p.area_name, u.name as owner_name 
                FROM inquiries i 
                JOIN properties p ON i.property_id = p.id 
                JOIN users u ON p.user_id = u.id 
                ORDER BY i.created_at DESC";
        
        try {
            $stmt = $this->db->executeQuery($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch all inquiries');
        }
    }
    
    /**
     * Get inquiry by ID
     * 
     * @param int $id Inquiry ID
     * @return array|null Inquiry data if found, null otherwise
     */
    public function findById($id) {
        $sql = "SELECT i.*, p.title as property_title, p.city, p.area_name 
                FROM inquiries i 
                JOIN properties p ON i.property_id = p.id 
                WHERE i.id = ? LIMIT 1";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Failed to find inquiry');
        }
    }
    
    /**
     * Delete inquiry
     * 
     * @param int $inquiryId Inquiry ID
     * @return bool True if successful
     */
    public function delete($inquiryId) {
        $sql = "DELETE FROM inquiries WHERE id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$inquiryId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception('Failed to delete inquiry');
        }
    }
    
    /**
     * Get inquiry statistics for admin dashboard
     * 
     * @return array Array of statistics
     */
    public function getStatistics() {
        $stats = [];
        
        try {
            // Total inquiries
            $sql = "SELECT COUNT(*) FROM inquiries";
            $stmt = $this->db->executeQuery($sql);
            $stats['total'] = $stmt->fetchColumn();
            
            // Inquiries this month
            $sql = "SELECT COUNT(*) FROM inquiries WHERE MONTH(created_at) = MONTH(CURRENT_DATE) AND YEAR(created_at) = YEAR(CURRENT_DATE)";
            $stmt = $this->db->executeQuery($sql);
            $stats['this_month'] = $stmt->fetchColumn();
            
            // Inquiries last 7 days
            $sql = "SELECT COUNT(*) FROM inquiries WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)";
            $stmt = $this->db->executeQuery($sql);
            $stats['last_7_days'] = $stmt->fetchColumn();
            
            return $stats;
        } catch (Exception $e) {
            throw new Exception('Failed to fetch inquiry statistics');
        }
    }
}