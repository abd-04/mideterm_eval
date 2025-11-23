<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

echo "<h1>System Check</h1>";

// 1. Check PHP Version
echo "PHP Version: " . phpversion() . "<br>";

// 2. Check Database Connection
try {
    $db = Database::getInstance()->getConnection();
    echo "Database Connection: <span style='color:green'><strong>SUCCESS</strong></span> (SQLite)<br>";
    
    // 3. Check Data
    $stmt = $db->query("SELECT COUNT(*) FROM properties");
    $count = $stmt->fetchColumn();
    echo "Properties Found: " . $count . "<br>";
    
} catch (Exception $e) {
    echo "Database Connection: <span style='color:red'><strong>FAILED</strong></span><br>";
    echo "Error: " . $e->getMessage();
}

echo "<br><a href='index.php'>Go to Homepage</a>";
