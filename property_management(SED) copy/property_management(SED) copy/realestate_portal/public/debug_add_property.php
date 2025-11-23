<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Property.php';
require_once __DIR__ . '/../models/User.php';

// Simulate session
session_start();
$_SESSION['user_id'] = 1; // Assuming admin user exists

echo "<h1>Debug Property Addition</h1>";

try {
    $propertyModel = new Property();
    
    $data = [
        'user_id' => 1,
        'title' => 'Debug Property ' . time(),
        'description' => 'This is a debug property created via script.',
        'city_id' => 1,
        'area_id' => 1, // Assuming area 1 exists
        'property_type_id' => 1,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'area_size' => 10,
        'area_unit' => 'Marla',
        'price' => 5000000,
        'price_unit' => 'PKR',
        'purpose' => 'sale',
        'main_image' => 'house1.jpg'
    ];

    echo "Attempting to create property...<br>";
    $id = $propertyModel->create($data);
    echo "Property created successfully! ID: " . $id . "<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
