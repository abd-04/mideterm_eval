<?php
/**
 * Property Controller Class - Enhanced Version
 * Software Construction & Development - Midterm Project
 * MVC Architecture - Controller Layer
 * 
 * This class handles all property-related operations including listing, searching, and CRUD operations.
 * Enhanced with advanced features like favorites, views tracking, and comprehensive search.
 */

require_once __DIR__ . '/../models/Property.php';
require_once __DIR__ . '/../models/Inquiry.php';
require_once __DIR__ . '/../models/User.php';

class PropertyController {
    private $propertyModel;
    private $inquiryModel;
    private $userModel;
    private $db;
    
    /**
     * Constructor - initializes models
     */
    public function __construct() {
        $this->propertyModel = new Property();
        $this->inquiryModel = new Inquiry();
        $this->userModel = new User();
        $this->db = Database::getInstance();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Display home page with featured properties and search
     */
    public function index() {
        try {
            // Get featured properties for homepage
            $featuredProperties = $this->propertyModel->getFeatured(6);
            
            // Get recent properties
            $recentProperties = $this->propertyModel->getRecent(8);
            
            // Get popular properties
            $popularProperties = $this->propertyModel->getPopular(4);
            
            // Get property statistics
            $propertyStats = $this->propertyModel->getStatistics();
            
            // Get search parameters if any
            $searchParams = $this->getSearchParams();
            
            // Get property types and cities for search form
            $propertyTypes = $this->propertyModel->getPropertyTypes();
            $cities = $this->propertyModel->getCities();
            
            return [
                'featuredProperties' => $featuredProperties,
                'recentProperties' => $recentProperties,
                'popularProperties' => $popularProperties,
                'propertyStats' => $propertyStats,
                'searchParams' => $searchParams,
                'propertyTypes' => $propertyTypes,
                'cities' => $cities
            ];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load homepage data';
            return [
                'featuredProperties' => [],
                'recentProperties' => [],
                'popularProperties' => [],
                'propertyStats' => [],
                'searchParams' => [],
                'propertyTypes' => [],
                'cities' => []
            ];
        }
    }
    
    /**
     * Display property listings with search and filtering
     */
    public function listings() {
        try {
            // Get search parameters
            $searchParams = $this->getSearchParams();
            
            // Get pagination parameters
            $page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
            $limit = 12;
            $offset = ($page - 1) * $limit;
            
            // Get filtered properties
            $properties = $this->propertyModel->getAll($searchParams, $offset, $limit);
            
            // Get total count for pagination
            $totalProperties = $this->getTotalPropertiesCount($searchParams);
            $totalPages = ceil($totalProperties / $limit);
            
            // Get property types and cities for filter form
            $propertyTypes = $this->propertyModel->getPropertyTypes();
            $cities = $this->propertyModel->getCities();
            
            // Get areas if city is selected
            $areas = [];
            if (!empty($searchParams['city_id'])) {
                $areas = $this->propertyModel->getAreasByCity($searchParams['city_id']);
            }
            
            // Get price range for price filter
            $priceRange = $this->propertyModel->getPriceRange($searchParams);
            
            return [
                'properties' => $properties,
                'searchParams' => $searchParams,
                'propertyTypes' => $propertyTypes,
                'cities' => $cities,
                'areas' => $areas,
                'priceRange' => $priceRange,
                'pagination' => [
                    'currentPage' => $page,
                    'totalPages' => $totalPages,
                    'totalProperties' => $totalProperties,
                    'limit' => $limit
                ]
            ];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load property listings';
            return [
                'properties' => [],
                'searchParams' => [],
                'propertyTypes' => [],
                'cities' => [],
                'areas' => [],
                'priceRange' => [],
                'pagination' => []
            ];
        }
    }
    
    /**
     * Display single property details
     */
    public function details($propertyId) {
        try {
            // Get property details
            $property = $this->propertyModel->findById($propertyId);
            
            if (!$property) {
                $_SESSION['error_message'] = 'Property not found';
                header('Location: index.php?page=properties');
                exit();
            }
            
            // Track property view
            $this->trackPropertyView($propertyId);
            
            // Handle inquiry form submission
            $inquiryResult = $this->handleInquirySubmission($propertyId);
            
            // Get similar properties
            $similarProperties = $this->propertyModel->getSimilarProperties($propertyId, 6);
            
            // Check if property is favorited by current user
            $isFavorited = false;
            if (isset($_SESSION['user_id'])) {
                $isFavorited = $this->propertyModel->isFavorited($propertyId, $_SESSION['user_id']);
            }
            
            return [
                'property' => $property,
                'similarProperties' => $similarProperties,
                'isFavorited' => $isFavorited,
                'inquiryErrors' => $inquiryResult['errors'],
                'inquirySuccess' => $inquiryResult['success']
            ];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load property details';
            header('Location: index.php?page=properties');
            exit();
        }
    }
    
    /**
     * Display user dashboard with their properties
     */
    public function dashboard() {
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        try {
            // Get user's properties
            $properties = $this->propertyModel->getUserProperties($_SESSION['user_id']);
            
            // Get inquiries for user's properties
            $inquiries = $this->inquiryModel->getByUserProperties($_SESSION['user_id']);
            
            // Get user's favorite properties
            $favorites = $this->propertyModel->getUserFavorites($_SESSION['user_id']);
            
            // Get user statistics
            $userStats = $this->getUserDashboardStats();
            
            return [
                'properties' => $properties,
                'inquiries' => $inquiries,
                'favorites' => $favorites,
                'userStats' => $userStats
            ];
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load dashboard data';
            return [
                'properties' => [],
                'inquiries' => [],
                'favorites' => [],
                'userStats' => []
            ];
        }
    }
    
    /**
     * Handle property creation
     */
    public function create() {
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        $errors = [];
        $formData = [];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input data
            $formData = $this->sanitizeInput($_POST);
            
            // Handle file upload
            $uploadResult = $this->handleImageUpload();
            if ($uploadResult['success']) {
                $formData['main_image'] = $uploadResult['filename'];
            } else {
                // If no image uploaded, use a default or allow it if not required
                // For now, let's use a default image if none provided
                // This check ensures that if no file was selected, a default is used,
                // otherwise, if an upload failed for other reasons, the error is still set.
                if (empty($_FILES['main_image']['name'])) {
                    $formData['main_image'] = 'house1.jpg'; // Default image
                } else {
                    $errors['main_image'] = $uploadResult['error'];
                }
            }
            
            // Server-side validation
            $errors = array_merge($errors, $this->validateProperty($formData));
            
            // If no errors, create property
            if (empty($errors)) {
                try {
                    // Prepare property data
                    $propertyData = [
                        'user_id' => $_SESSION['user_id'],
                        'title' => $formData['title'],
                        'description' => $formData['description'],
                        'city_id' => (int)$formData['city_id'],
                        'area_id' => (int)$formData['area_id'],
                        'property_type_id' => (int)$formData['property_type_id'],
                        'bedrooms' => (int)$formData['bedrooms'],
                        'bathrooms' => (int)$formData['bathrooms'],
                        'area_size' => (float)$formData['area_size'],
                        'area_unit' => $formData['area_unit'],
                        'price' => (float)$formData['price'],
                        'price_unit' => $formData['price_unit'],
                        'purpose' => $formData['purpose'],
                        'main_image' => $formData['main_image']
                    ];
                    
                    // Create property
                    $propertyId = $this->propertyModel->create($propertyData);
                    
                    // Set success message
                    $_SESSION['success_message'] = 'Property listed successfully! It will be reviewed and published soon.';
                    
                    // Redirect to dashboard
                    header('Location: index.php?page=dashboard');
                    exit();
                    
                } catch (Exception $e) {
                    $errors['general'] = 'Failed to create property listing. Please try again.';
                }
            }
        }
        
        // Get property types and cities for form
        $propertyTypes = $this->propertyModel->getPropertyTypes();
        $cities = $this->propertyModel->getCities();
        
        // Return data for view rendering
        return [
            'errors' => $errors,
            'formData' => $formData,
            'propertyTypes' => $propertyTypes,
            'cities' => $cities
        ];
    }

    /**
     * Get areas by city ID (API)
     */
    public function getAreas($cityId) {
        header('Content-Type: application/json');
        
        if (!$cityId) {
            echo json_encode([]);
            return;
        }
        
        try {
            $areas = $this->propertyModel->getAreasByCity($cityId);
            echo json_encode($areas);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }    
    /**
     * Handle property editing
     */
    public function edit($propertyId) {
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        // Get property details
        $property = $this->propertyModel->findById($propertyId);
        
        // Verify ownership
        if (!$property || $property['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'You are not authorized to edit this property';
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $errors = [];
        $formData = [];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input data
            $formData = $this->sanitizeInput($_POST);
            
            // Handle file upload if new image provided
            if (!empty($_FILES['main_image']['name'])) {
                $uploadResult = $this->handleImageUpload();
                if ($uploadResult['success']) {
                    $formData['main_image'] = $uploadResult['filename'];
                    // Delete old image if exists
                    if (!empty($property['main_image'])) {
                        $this->deleteImage($property['main_image']);
                    }
                } else {
                    $errors['main_image'] = $uploadResult['error'];
                }
            } else {
                $formData['main_image'] = $property['main_image'];
            }
            
            // Server-side validation
            $errors = array_merge($errors, $this->validateProperty($formData));
            
            // If no errors, update property
            if (empty($errors)) {
                try {
                    // Prepare property data
                    $propertyData = [
                        'user_id' => $_SESSION['user_id'],
                        'title' => $formData['title'],
                        'description' => $formData['description'],
                        'city_id' => (int)$formData['city_id'],
                        'area_id' => (int)$formData['area_id'],
                        'property_type_id' => (int)$formData['property_type_id'],
                        'bedrooms' => (int)$formData['bedrooms'],
                        'bathrooms' => (int)$formData['bathrooms'],
                        'area_size' => (float)$formData['area_size'],
                        'area_unit' => $formData['area_unit'],
                        'price' => (float)$formData['price'],
                        'price_unit' => $formData['price_unit'],
                        'purpose' => $formData['purpose'],
                        'main_image' => $formData['main_image']
                    ];
                    
                    // Update property
                    $success = $this->propertyModel->update($propertyId, $propertyData);
                    
                    if ($success) {
                        // Set success message
                        $_SESSION['success_message'] = 'Property updated successfully!';
                        
                        // Redirect to dashboard
                        header('Location: index.php?page=dashboard');
                        exit();
                    } else {
                        $errors['general'] = 'Failed to update property. Please try again.';
                    }
                    
                } catch (Exception $e) {
                    $errors['general'] = 'Failed to update property. Please try again.';
                }
            }
        } else {
            // Pre-fill form with existing data
            $formData = $property;
        }
        
        // Get property types and cities for form
        $propertyTypes = $this->propertyModel->getPropertyTypes();
        $cities = $this->propertyModel->getCities();
        
        // Get areas for the property's city
        $areas = $this->propertyModel->getAreasByCity($property['city_id']);
        
        // Return data for view rendering
        return [
            'property' => $property,
            'errors' => $errors,
            'formData' => $formData,
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
            'areas' => $areas
        ];
    }
    
    /**
     * Handle property deletion
     */
    public function delete($propertyId) {
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        try {
            // Get property details
            $property = $this->propertyModel->findById($propertyId);
            
            // Verify ownership
            if (!$property || $property['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error_message'] = 'You are not authorized to delete this property';
            } else {
                // Delete property
                $success = $this->propertyModel->delete($propertyId, $_SESSION['user_id']);
                
                if ($success) {
                    // Delete associated image
                    if (!empty($property['main_image'])) {
                        $this->deleteImage($property['main_image']);
                    }
                    
                    $_SESSION['success_message'] = 'Property deleted successfully!';
                } else {
                    $_SESSION['error_message'] = 'Failed to delete property';
                }
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to delete property';
        }
        
        // Redirect back to dashboard
        header('Location: index.php?page=dashboard');
        exit();
    }
    
    /**
     * Toggle property favorite
     */
    public function toggleFavorite($propertyId) {
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Please login to add favorites']);
            return;
        }
        
        try {
            $result = $this->propertyModel->toggleFavorite($propertyId, $_SESSION['user_id']);
            echo json_encode(['success' => true, 'favorited' => $result]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to toggle favorite']);
        }
    }
    
    /**
     * Search properties
     */
    public function search() {
        $searchTerm = $_GET['q'] ?? '';
        $limit = isset($_GET['limit']) ? min(20, intval($_GET['limit'])) : 10;
        
        if (empty($searchTerm)) {
            echo json_encode([]);
            return;
        }
        
        try {
            $results = $this->propertyModel->search($searchTerm, $limit);
            echo json_encode($results);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
    
    /**
     * Get areas by city (AJAX)
     */
    public function getAreasByCity() {
        $cityId = $_GET['city_id'] ?? 0;
        
        if (empty($cityId)) {
            echo json_encode([]);
            return;
        }
        
        try {
            $areas = $this->propertyModel->getAreasByCity($cityId);
            echo json_encode($areas);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
    
    /**
     * Handle inquiry form submission
     */
    private function handleInquirySubmission($propertyId) {
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry_submit'])) {
            // Sanitize input data
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            if (empty($formData['name'])) {
                $errors['name'] = 'Name is required';
            }
            
            if (empty($formData['email'])) {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Please enter a valid email address';
            }
            
            if (empty($formData['message'])) {
                $errors['message'] = 'Message is required';
            }
            
            // If no errors, create inquiry
            if (empty($errors)) {
                try {
                    $inquiryData = [
                        'property_id' => $propertyId,
                        'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
                        'name' => $formData['name'],
                        'email' => $formData['email'],
                        'phone' => $formData['phone'] ?? '',
                        'message' => $formData['message']
                    ];
                    
                    $this->inquiryModel->create($inquiryData);
                    $success = true;
                    
                } catch (Exception $e) {
                    $errors['general'] = 'Failed to send inquiry. Please try again.';
                }
            }
        }
        
        return [
            'errors' => $errors,
            'success' => $success
        ];
    }
    
    /**
     * Track property view
     */
    private function trackPropertyView($propertyId) {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $this->propertyModel->addView($propertyId, $userId, $ipAddress, $userAgent);
    }
    
    /**
     * Get total properties count for pagination
     */
    private function getTotalPropertiesCount($filters = []) {
        $where = ["status = 'approved'"];
        $params = [];
        
        // Apply filters
        if (!empty($filters['city_id'])) {
            $where[] = "city_id = ?";
            $params[] = $filters['city_id'];
        }
        
        if (!empty($filters['area_id'])) {
            $where[] = "area_id = ?";
            $params[] = $filters['area_id'];
        }
        
        if (!empty($filters['property_type_id'])) {
            $where[] = "property_type_id = ?";
            $params[] = $filters['property_type_id'];
        }
        
        if (!empty($filters['purpose'])) {
            $where[] = "purpose = ?";
            $params[] = $filters['purpose'];
        }
        
        if (!empty($filters['min_price'])) {
            $where[] = "price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $where[] = "price <= ?";
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['bedrooms'])) {
            if ($filters['bedrooms'] == '5+') {
                $where[] = "bedrooms >= 5";
            } else {
                $where[] = "bedrooms >= ?";
                $params[] = $filters['bedrooms'];
            }
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(title LIKE ? OR description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm]);
        }
        
        $whereClause = implode(' AND ', $where);
        $sql = "SELECT COUNT(*) FROM properties WHERE $whereClause";
        
        try {
            $stmt = $this->db->executeQuery($sql, $params);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get user dashboard statistics
     */
    private function getUserDashboardStats() {
        $userId = $_SESSION['user_id'];
        
        $stats = [];
        
        try {
            // Property counts
            $properties = $this->propertyModel->getUserProperties($userId);
            $stats['total_properties'] = count($properties);
            $stats['active_properties'] = count(array_filter($properties, function($p) { return $p['status'] === 'approved'; }));
            $stats['pending_properties'] = count(array_filter($properties, function($p) { return $p['status'] === 'pending'; }));
            $stats['sold_properties'] = count(array_filter($properties, function($p) { return $p['status'] === 'sold'; }));
            
            // Inquiry counts
            $inquiries = $this->inquiryModel->getByUserProperties($userId);
            $stats['total_inquiries'] = count($inquiries);
            $stats['new_inquiries'] = count(array_filter($inquiries, function($i) { return $i['status'] === 'new'; }));
            
            // Favorite counts
            $favorites = $this->propertyModel->getUserFavorites($userId);
            $stats['total_favorites'] = count($favorites);
            
            return $stats;
        } catch (Exception $e) {
            return [
                'total_properties' => 0,
                'active_properties' => 0,
                'pending_properties' => 0,
                'sold_properties' => 0,
                'total_inquiries' => 0,
                'new_inquiries' => 0,
                'total_favorites' => 0
            ];
        }
    }
    
    /**
     * Get search parameters from request
     */
    private function getSearchParams() {
        return [
            'city_id' => isset($_GET['city_id']) && !empty($_GET['city_id']) ? (int)$_GET['city_id'] : null,
            'area_id' => isset($_GET['area_id']) && !empty($_GET['area_id']) ? (int)$_GET['area_id'] : null,
            'property_type_id' => isset($_GET['property_type_id']) && !empty($_GET['property_type_id']) ? (int)$_GET['property_type_id'] : null,
            'purpose' => $_GET['purpose'] ?? '',
            'min_price' => isset($_GET['min_price']) && !empty($_GET['min_price']) ? (float)$_GET['min_price'] : null,
            'max_price' => isset($_GET['max_price']) && !empty($_GET['max_price']) ? (float)$_GET['max_price'] : null,
            'bedrooms' => $_GET['bedrooms'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
    }
    
    /**
     * Sanitize input data
     */
    private function sanitizeInput($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = htmlspecialchars(strip_tags(trim($value)));
        }
        return $sanitized;
    }
    
    /**
     * Validate property data
     */
    private function validateProperty($data) {
        $errors = [];
        
        // Title validation
        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        } elseif (strlen($data['title']) < 5) {
            $errors['title'] = 'Title must be at least 5 characters long';
        }
        
        // Description validation
        if (empty($data['description'])) {
            $errors['description'] = 'Description is required';
        } elseif (strlen($data['description']) < 20) {
            $errors['description'] = 'Description must be at least 20 characters long';
        }
        
        // City validation
        if (empty($data['city_id'])) {
            $errors['city_id'] = 'City is required';
        }
        
        // Area validation
        if (empty($data['area_id'])) {
            $errors['area_id'] = 'Area is required';
        }
        
        // Property type validation
        if (empty($data['property_type_id'])) {
            $errors['property_type_id'] = 'Property type is required';
        }
        
        // Area size validation
        if (empty($data['area_size'])) {
            $errors['area_size'] = 'Area size is required';
        } elseif (!is_numeric($data['area_size']) || $data['area_size'] <= 0) {
            $errors['area_size'] = 'Area size must be a positive number';
        }
        
        // Price validation
        if (empty($data['price'])) {
            $errors['price'] = 'Price is required';
        } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Price must be a positive number';
        }
        
        // Bedrooms validation (optional but must be non-negative if provided)
        if (isset($data['bedrooms']) && $data['bedrooms'] !== '' && (!is_numeric($data['bedrooms']) || $data['bedrooms'] < 0)) {
            $errors['bedrooms'] = 'Bedrooms must be a non-negative number';
        }
        
        // Bathrooms validation (optional but must be non-negative if provided)
        if (isset($data['bathrooms']) && $data['bathrooms'] !== '' && (!is_numeric($data['bathrooms']) || $data['bathrooms'] < 0)) {
            $errors['bathrooms'] = 'Bathrooms must be a non-negative number';
        }
        
        return $errors;
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload() {
        if (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => false, 'error' => 'Main image is required'];
        }
        
        $file = $_FILES['main_image'];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'File upload failed'];
        }
        
        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'error' => 'File size must be less than 5MB'];
        }
        
        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'error' => 'Only JPG, PNG, and GIF files are allowed'];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('property_') . '.' . $extension;
        $uploadPath = UPLOAD_PATH . $filename;
        
        // Create upload directory if it doesn't exist
        if (!file_exists(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'Failed to save uploaded file'];
        }
    }
    
    /**
     * Delete image file
     */
    private function deleteImage($filename) {
        $filepath = UPLOAD_PATH . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}