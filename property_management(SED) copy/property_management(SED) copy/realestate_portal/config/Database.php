<?php
/**
 * Singleton Database Connection Class
 * Software Construction & Development - Midterm Project
 * Design Pattern Implementation: Singleton Pattern
 * 
 * This class implements the Singleton pattern to ensure only one database connection
 * exists throughout the application lifecycle, improving performance and resource management.
 */

require_once __DIR__ . '/config.php';

class Database {
    // Static instance to hold the single instance of the class
    private static $instance = null;
    
    // Database connection object
    private $connection;
    
    /**
     * Private constructor to prevent direct instantiation
     * Implements the Singleton pattern by making constructor inaccessible
     */
    private function __construct() {
        try {
            // Create DSN string based on connection type
            if (defined('DB_CONNECTION') && DB_CONNECTION === 'sqlite') {
                $dsn = 'sqlite:' . DB_DATABASE;
                
                // Set PDO options for SQLite
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                // Create the PDO connection for SQLite
                $this->connection = new PDO($dsn, null, null, $options);
                
                // Enable foreign keys for SQLite
                $this->connection->exec("PRAGMA foreign_keys = ON;");
            } else {
                // Default to MySQL
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
                
                // Set PDO options for MySQL
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true // Enable persistent connections
                ];
                
                // Create the PDO connection for MySQL
                $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            }
            
        } catch (PDOException $e) {
            // Log the actual error internally (in production, log to file)
            error_log('Database connection failed: ' . $e->getMessage());
            
            // Show user-friendly error message
            die('Connection failed. Please try again later.');
        }
    }
    
    /**
     * Get the single instance of the Database class
     * Implements Singleton pattern by providing controlled access to the instance
     * 
     * @return Database The single instance of the Database class
     */
    public static function getInstance() {
        // Check if instance doesn't exist yet
        if (self::$instance === null) {
            // Create the single instance
            self::$instance = new self();
        }
        
        // Return the single instance
        return self::$instance;
    }
    
    /**
     * Get the PDO connection object
     * 
     * @return PDO The PDO connection object
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Prevent cloning of the instance (maintains singleton integrity)
     */
    private function __clone() {
        // Throw exception to prevent cloning
        throw new Exception('Cannot clone a singleton object');
    }
    
    /**
     * Prevent unserialization of the instance (maintains singleton integrity)
     */
    public function __wakeup() {
        // Throw exception to prevent unserialization
        throw new Exception('Cannot unserialize a singleton object');
    }
    
    /**
     * Execute a prepared statement with parameters
     * Helper method for secure database queries
     * 
     * @param string $sql The SQL query with placeholders
     * @param array $params Array of parameters to bind
     * @return PDOStatement The executed statement
     */
    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // Log error internally
            error_log('Query execution failed: ' . $e->getMessage());
            throw new Exception('Database query failed. Please try again later.');
        }
    }
    
    /**
     * Get the last inserted ID
     * 
     * @return string The last inserted ID
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}