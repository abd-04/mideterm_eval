<?php
// Database configuration constants
// Software Construction & Development - Midterm Project

// Database connection settings
// Database connection settings
// Switched to SQLite for easier local setup (No XAMPP MySQL required)
define('DB_CONNECTION', 'sqlite');
define('DB_DATABASE', __DIR__ . '/../database.sqlite');

// MySQL settings - INACTIVE
// define('DB_CONNECTION', 'mysql');
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'realestate_portal');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_CHARSET', 'utf8mb4');

// Application settings
define('APP_NAME', 'Real Estate Portal');
define('APP_URL', 'http://localhost/realestate_portal/public');
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Security settings
define('PASSWORD_MIN_LENGTH', 8);
define('SESSION_LIFETIME', 3600); // 1 hour

// Error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);