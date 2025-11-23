<?php
/**
 * Admin Controller Class
 * Software Construction & Development - Midterm Project
 * MVC Architecture - Controller Layer
 * 
 * This class handles all admin-related operations including user management and property moderation.
 * Part of the Controller layer in MVC pattern.
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Property.php';
require_once __DIR__ . '/../models/Inquiry.php';

class AdminController {
    private $userModel;
    private $propertyModel;
    private $inquiryModel;
    
    /**
     * Constructor - initializes models and checks admin access
     */
    public function __construct() {
        $this->userModel = new User();
        $this->propertyModel = new Property();
        $this->inquiryModel = new Inquiry();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check admin access
        $this->checkAdminAccess();
    }
    
    /**
     * Display admin dashboard with statistics
     */
    public function dashboard() {
        try {
            // Get statistics
            $userStats = $this->getUserStatistics();
            $propertyStats = $this->propertyModel->getStatistics();
            $inquiryStats = $this->inquiryModel->getStatistics();
            
            // Get recent data
            $recentUsers = $this->userModel->getAllUsers();
            $recentProperties = $this->propertyModel->getAll(); // Get all properties for admin
            $recentInquiries = $this->inquiryModel->getAll();
            
            // Limit recent data
            $recentUsers = array_slice($recentUsers, 0, 5);
            $recentProperties = array_slice($recentProperties, 0, 5);
            $recentInquiries = array_slice($recentInquiries, 0, 5);
            
            return [
                'userStats' => $userStats,
                'propertyStats' => $propertyStats,
                'inquiryStats' => $inquiryStats,
                'recentUsers' => $recentUsers,
                'recentProperties' => $recentProperties,
                'recentInquiries' => $recentInquiries
            ];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load admin dashboard data';
            return [
                'userStats' => [],
                'propertyStats' => [],
                'inquiryStats' => [],
                'recentUsers' => [],
                'recentProperties' => [],
                'recentInquiries' => []
            ];
        }
    }
    
    /**
     * Display all users for management
     */
    public function users() {
        try {
            $users = $this->userModel->getAllUsers();
            return ['users' => $users];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load users';
            return ['users' => []];
        }
    }
    
    /**
     * Display all properties for moderation
     */
    public function properties() {
        try {
            // Get all properties including pending ones
            $properties = $this->propertyModel->getAll();
            
            // Add pending properties that might not be included
            $pendingProperties = $this->getPendingProperties();
            
            // Merge and remove duplicates
            $allProperties = array_merge($properties, $pendingProperties);
            $allProperties = array_unique($allProperties, SORT_REGULAR);
            
            return ['properties' => $allProperties];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load properties';
            return ['properties' => []];
        }
    }
    
    /**
     * Display all inquiries
     */
    public function inquiries() {
        try {
            $inquiries = $this->inquiryModel->getAll();
            return ['inquiries' => $inquiries];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load inquiries';
            return ['inquiries' => []];
        }
    }
    
    /**
     * Update property status (approve/reject)
     */
    public function updatePropertyStatus($propertyId, $status) {
        // Validate status
        $validStatuses = ['pending', 'approved', 'rejected', 'inactive'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error_message'] = 'Invalid status';
            header('Location: index.php?page=admin_properties');
            exit();
        }
        
        try {
            $success = $this->propertyModel->updateStatus($propertyId, $status);
            
            if ($success) {
                $message = $status === 'approved' ? 'Property approved successfully!' : 'Property status updated successfully!';
                $_SESSION['success_message'] = $message;
            } else {
                $_SESSION['error_message'] = 'Failed to update property status';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to update property status';
        }
        
        // Redirect back to properties page
        header('Location: index.php?page=admin_properties');
        exit();
    }
    
    /**
     * Update user status (activate/deactivate)
     */
    public function updateUserStatus($userId, $action) {
        // Validate action
        $validActions = ['activate', 'deactivate'];
        if (!in_array($action, $validActions)) {
            $_SESSION['error_message'] = 'Invalid action';
            header('Location: index.php?page=admin_users');
            exit();
        }
        
        // Prevent admin self-deactivation
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'You cannot deactivate your own account';
            header('Location: index.php?page=admin_users');
            exit();
        }
        
        try {
            $newStatus = $action === 'activate' ? 'user' : 'inactive';
            $success = $this->userModel->updateStatus($userId, $newStatus);
            
            if ($success) {
                $message = $action === 'activate' ? 'User activated successfully!' : 'User deactivated successfully!';
                $_SESSION['success_message'] = $message;
            } else {
                $_SESSION['error_message'] = 'Failed to update user status';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to update user status';
        }
        
        // Redirect back to users page
        header('Location: index.php?page=admin_users');
        exit();
    }
    
    /**
     * Delete inquiry
     */
    public function deleteInquiry($inquiryId) {
        try {
            $success = $this->inquiryModel->delete($inquiryId);
            
            if ($success) {
                $_SESSION['success_message'] = 'Inquiry deleted successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to delete inquiry';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to delete inquiry';
        }
        
        // Redirect back to inquiries page
        header('Location: index.php?page=admin_inquiries');
        exit();
    }
    
    /**
     * Check if user has admin access
     */
    private function checkAdminAccess() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Access denied. Admin privileges required.';
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Get user statistics for dashboard
     */
    private function getUserStatistics() {
        try {
            // Total users
            $users = $this->userModel->getAllUsers();
            $totalUsers = count($users);
            
            // Users by role
            $adminCount = 0;
            $regularUserCount = 0;
            
            foreach ($users as $user) {
                if ($user['role'] === 'admin') {
                    $adminCount++;
                } else {
                    $regularUserCount++;
                }
            }
            
            // Users this month
            $usersThisMonth = array_filter($users, function($user) {
                $createdAt = new DateTime($user['created_at']);
                $now = new DateTime();
                return $createdAt->format('Y-m') === $now->format('Y-m');
            });
            
            return [
                'total' => $totalUsers,
                'admins' => $adminCount,
                'users' => $regularUserCount,
                'this_month' => count($usersThisMonth)
            ];
        } catch (Exception $e) {
            return [
                'total' => 0,
                'admins' => 0,
                'users' => 0,
                'this_month' => 0
            ];
        }
    }
    
    /**
     * Get pending properties for admin review
     */
    private function getPendingProperties() {
        try {
            // This method doesn't exist in the current Property model
            // For now, return empty array. In a full implementation,
            // you would add this method to the Property model
            return [];
        } catch (Exception $e) {
            return [];
        }
    }
}