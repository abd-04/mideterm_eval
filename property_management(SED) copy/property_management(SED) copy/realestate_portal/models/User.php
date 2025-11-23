<?php
/**
 * User Model Class
 * Software Construction & Development - Midterm Project
 * MVC Architecture - Model Layer
 * 
 * This class handles all user-related database operations and business logic.
 * Part of the Model layer in MVC pattern.
 */

require_once __DIR__ . '/../config/Database.php';

class User {
    private $db;
    
    /**
     * Constructor - initializes database connection using Singleton pattern
     */
    public function __construct() {
        // Get database instance using Singleton pattern
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new user account
     * 
     * @param array $userData Array containing user information
     * @return int The ID of the newly created user
     */
    public function create($userData) {
        $sql = "INSERT INTO users (name, email, password_hash, phone) VALUES (?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->executeQuery($sql, [
                $userData['name'],
                $userData['email'],
                $userData['password_hash'],
                $userData['phone']
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Failed to create user account');
        }
    }
    
    /**
     * Find user by email address
     * 
     * @param string $email User's email address
     * @return array|null User data if found, null otherwise
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Failed to find user');
        }
    }
    
    /**
     * Find user by ID
     * 
     * @param int $id User ID
     * @return array|null User data if found, null otherwise
     */
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Failed to find user');
        }
    }
    
    /**
     * Get all users (for admin dashboard)
     * 
     * @return array Array of all users
     */
    public function getAllUsers() {
        $sql = "SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC";
        
        try {
            $stmt = $this->db->executeQuery($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch users');
        }
    }
    
    /**
     * Update user status (activate/deactivate)
     * 
     * @param int $userId User ID
     * @param string $status New status
     * @return bool True if successful
     */
    public function updateStatus($userId, $status) {
        // Note: In this implementation, we'll use the existing role field
        // In a full implementation, you might want a separate status field
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$status, $userId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception('Failed to update user status');
        }
    }
    
    /**
     * Verify user password
     * 
     * @param string $password Plain text password
     * @param string $passwordHash Hashed password
     * @return bool True if password matches
     */
    public function verifyPassword($password, $passwordHash) {
        return password_verify($password, $passwordHash);
    }
    
    /**
     * Hash password for secure storage
     * 
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Check if email already exists
     * 
     * @param string $email Email to check
     * @return bool True if email exists
     */
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        
        try {
            $stmt = $this->db->executeQuery($sql, [$email]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            throw new Exception('Failed to check email existence');
        }
    }
}