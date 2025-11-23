<?php
/**
 * Front Controller - Main Entry Point
 * Software Construction & Development - Midterm Project
 * MVC Architecture - Front Controller Pattern
 * 
 * This file acts as the front controller, routing all requests to appropriate controllers.
 * Implements the Front Controller pattern as part of the MVC architecture.
 */

// Start session
session_start();

// Define base paths
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/config.php';

// Get the requested page/action
$page = $_GET['page'] ?? 'home';

// Initialize variables for view data
$viewData = [];
$viewFile = '';

// Route to appropriate controller based on page parameter
try {
    switch ($page) {
        // Home page
        case 'home':
        case '':
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $viewData = $controller->index();
            $viewFile = 'home.php';
            break;

        // Property listings
        case 'properties':
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $viewData = $controller->listings();
            $viewFile = 'properties_list.php';
            break;

        // Property details
        case 'property_details':
            $propertyId = $_GET['id'] ?? 0;
            if (!$propertyId) {
                $_SESSION['error_message'] = 'Property ID is required';
                header('Location: index.php?page=properties');
                exit();
            }
            
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $viewData = $controller->details($propertyId);
            $viewFile = 'property_detail.php';
            break;

        // User authentication
        case 'login':
            require_once ROOT_PATH . '/controllers/AuthController.php';
            $controller = new AuthController();
            $viewData = $controller->login();
            $viewFile = 'auth/login.php';
            break;

        case 'register':
            require_once ROOT_PATH . '/controllers/AuthController.php';
            $controller = new AuthController();
            $viewData = $controller->register();
            $viewFile = 'auth/register.php';
            break;

        case 'logout':
            require_once ROOT_PATH . '/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->logout();
            break;

        // User dashboard
        case 'dashboard':
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $viewData = $controller->dashboard();
            $viewFile = 'dashboard.php';
            break;

        // Property management
        case 'add_property':
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $viewData = $controller->create();
            $viewFile = 'property/add.php';
            break;

        case 'edit_property':
            $propertyId = $_GET['id'] ?? 0;
            if (!$propertyId) {
                $_SESSION['error_message'] = 'Property ID is required';
                header('Location: index.php?page=dashboard');
                exit();
            }
            
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $viewData = $controller->edit($propertyId);
            $viewFile = 'property/edit.php';
            break;

        case 'delete_property':
            $propertyId = $_GET['id'] ?? 0;
            if (!$propertyId) {
                $_SESSION['error_message'] = 'Property ID is required';
                header('Location: index.php?page=dashboard');
                exit();
            }
            
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $controller->delete($propertyId);
            break;

        // Admin routes
        case 'admin_dashboard':
            require_once ROOT_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $viewData = $controller->dashboard();
            $viewFile = 'admin/dashboard.php';
            break;

        case 'admin_users':
            require_once ROOT_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $viewData = $controller->users();
            $viewFile = 'admin/users.php';
            break;

        case 'admin_properties':
            require_once ROOT_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $viewData = $controller->properties();
            $viewFile = 'admin/properties.php';
            break;

        case 'admin_inquiries':
            require_once ROOT_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $viewData = $controller->inquiries();
            $viewFile = 'admin/inquiries.php';
            break;

        // Admin actions
        case 'admin_update_property_status':
            $propertyId = $_GET['id'] ?? 0;
            $status = $_GET['status'] ?? '';
            
            if (!$propertyId || !$status) {
                $_SESSION['error_message'] = 'Missing parameters';
                header('Location: index.php?page=admin_properties');
                exit();
            }
            
            require_once ROOT_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->updatePropertyStatus($propertyId, $status);
            break;

        case 'admin_update_user_status':
            $userId = $_GET['id'] ?? 0;
            $action = $_GET['action'] ?? '';
            
            if (!$userId || !$action) {
                $_SESSION['error_message'] = 'Missing parameters';
                header('Location: index.php?page=admin_users');
                exit();
            }
            
            require_once ROOT_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->updateUserStatus($userId, $action);
            break;

        case 'admin_delete_inquiry':
            $inquiryId = $_GET['id'] ?? 0;
            
            if (!$inquiryId) {
                $_SESSION['error_message'] = 'Inquiry ID is required';
                header('Location: index.php?page=admin_inquiries');
                exit();
            }
            
            require_once ROOT_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->deleteInquiry($inquiryId);
            break;

        // API Routes
        case 'api_get_areas':
            $cityId = $_GET['city_id'] ?? 0;
            require_once ROOT_PATH . '/controllers/PropertyController.php';
            $controller = new PropertyController();
            $controller->getAreas($cityId);
            exit(); // Stop execution after returning JSON
            break;

        // Default - 404 page
        default:
            http_response_code(404);
            $_SESSION['error_message'] = 'Page not found';
            $viewFile = '404.php';
            break;
    }
} catch (Exception $e) {
    // Log the error (in production, log to file)
    error_log('Controller error: ' . $e->getMessage());
    
    // Show user-friendly error message
    $_SESSION['error_message'] = 'An error occurred. Please try again later.';
    $viewFile = 'error.php';
}

// Load the appropriate view
if ($viewFile) {
    $viewPath = ROOT_PATH . '/views/' . $viewFile;
    
    if (file_exists($viewPath)) {
        // Extract view data for use in the view file
        if (!empty($viewData)) {
            extract($viewData);
        }
        
        // Set the current page for navigation highlighting
        $currentPage = $page;
        
        // Include the view file
        require_once $viewPath;
    } else {
        // View file not found
        http_response_code(404);
        $_SESSION['error_message'] = 'View not found';
        require_once ROOT_PATH . '/views/404.php';
    }
}