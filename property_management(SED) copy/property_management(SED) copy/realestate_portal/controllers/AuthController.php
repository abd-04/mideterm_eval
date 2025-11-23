<?php
/**
 * Authentication Controller Class
 * Software Construction & Development - Midterm Project
 * MVC Architecture - Controller Layer
 * 
 * This class handles all authentication-related operations including login, registration, and logout.
 * Part of the Controller layer in MVC pattern.
 */

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    /**
     * Constructor - initializes the User model
     */
    public function __construct() {
        $this->userModel = new User();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Handle user registration
     * Implements both client-side and server-side validation
     */
    public function register() {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $errors = [];
        $formData = [];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input data
            $formData = $this->sanitizeInput($_POST);
            
            // Server-side validation
            $errors = $this->validateRegistration($formData);
            
            // Check if email already exists
            if (empty($errors) && $this->userModel->emailExists($formData['email'])) {
                $errors['email'] = 'Email address is already registered';
            }
            
            // If no errors, create user account
            if (empty($errors)) {
                try {
                    // Hash password
                    $hashedPassword = $this->userModel->hashPassword($formData['password']);
                    
                    // Prepare user data
                    $userData = [
                        'name' => $formData['name'],
                        'email' => $formData['email'],
                        'password_hash' => $hashedPassword,
                        'phone' => $formData['phone']
                    ];
                    
                    // Create user
                    $userId = $this->userModel->create($userData);
                    
                    // Auto-login after registration
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $userData['name'];
                    $_SESSION['user_email'] = $userData['email'];
                    $_SESSION['user_role'] = 'user';
                    
                    // Set success message
                    $_SESSION['success_message'] = 'Registration successful! Welcome to Real Estate Portal.';
                    
                    // Redirect to dashboard
                    header('Location: index.php?page=dashboard');
                    exit();
                    
                } catch (Exception $e) {
                    $errors['general'] = 'Registration failed. Please try again.';
                }
            }
        }
        
        // Return data for view rendering
        return [
            'errors' => $errors,
            'formData' => $formData
        ];
    }
    
    /**
     * Handle user login
     * Implements both client-side and server-side validation
     */
    public function login() {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $errors = [];
        $formData = [];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input data
            $formData = $this->sanitizeInput($_POST);
            
            // Server-side validation
            $errors = $this->validateLogin($formData);
            
            // If no errors, authenticate user
            if (empty($errors)) {
                try {
                    // Find user by email
                    $user = $this->userModel->findByEmail($formData['email']);
                    
                    // Verify user exists and password is correct
                    if ($user && $this->userModel->verifyPassword($formData['password'], $user['password_hash'])) {
                        // Set session data
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];
                        
                        // Set success message
                        $_SESSION['success_message'] = 'Login successful! Welcome back.';
                        
                        // Redirect based on role
                        if ($user['role'] === 'admin') {
                            header('Location: index.php?page=admin_dashboard');
                        } else {
                            header('Location: index.php?page=dashboard');
                        }
                        exit();
                    } else {
                        $errors['general'] = 'Invalid email or password';
                    }
                    
                } catch (Exception $e) {
                    $errors['general'] = 'Login failed. Please try again.';
                }
            }
        }
        
        // Return data for view rendering
        return [
            'errors' => $errors,
            'formData' => $formData
        ];
    }
    
    /**
     * Handle user logout
     */
    public function logout() {
        // Clear all session data
        $_SESSION = [];
        
        // Destroy the session
        session_destroy();
        
        // Redirect to home page
        header('Location: index.php');
        exit();
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool True if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get current user ID
     * 
     * @return int|null User ID if logged in, null otherwise
     */
    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     * 
     * @return string|null User role if logged in, null otherwise
     */
    public function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Sanitize input data
     * 
     * @param array $data Input data to sanitize
     * @return array Sanitized data
     */
    private function sanitizeInput($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = htmlspecialchars(strip_tags(trim($value)));
        }
        return $sanitized;
    }
    
    /**
     * Validate registration form data
     * 
     * @param array $data Form data to validate
     * @return array Array of validation errors
     */
    private function validateRegistration($data) {
        $errors = [];
        
        // Name validation
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        } elseif (strlen($data['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters long';
        }
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        // Phone validation (optional but if provided, should be valid)
        if (!empty($data['phone']) && !preg_match('/^[0-9\+\-\s\(\)]+$/', $data['phone'])) {
            $errors['phone'] = 'Please enter a valid phone number';
        }
        
        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
            $errors['password'] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long';
        }
        
        // Confirm password validation
        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = 'Please confirm your password';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        return $errors;
    }
    
    /**
     * Validate login form data
     * 
     * @param array $data Form data to validate
     * @return array Array of validation errors
     */
    private function validateLogin($data) {
        $errors = [];
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        }
        
        return $errors;
    }
}