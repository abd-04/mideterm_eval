<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

echo "Setting up SQLite database...\n";

try {
    $db = Database::getInstance()->getConnection();
    
    // Enable foreign keys
    $db->exec("PRAGMA foreign_keys = ON");

    // 1. Create Tables
    // Users
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        phone TEXT,
        role TEXT NOT NULL DEFAULT 'user',
        profile_image TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Users table created.\n";

    // Cities
    $db->exec("CREATE TABLE IF NOT EXISTS cities (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        status TEXT DEFAULT 'active'
    )");
    echo "Cities table created.\n";

    // Areas
    $db->exec("CREATE TABLE IF NOT EXISTS areas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        city_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        status TEXT DEFAULT 'active',
        FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE
    )");
    echo "Areas table created.\n";

    // Property Types
    $db->exec("CREATE TABLE IF NOT EXISTS property_types (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        icon TEXT,
        status TEXT DEFAULT 'active'
    )");
    echo "Property Types table created.\n";

    // Properties
    $db->exec("CREATE TABLE IF NOT EXISTS properties (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        city_id INTEGER NOT NULL,
        area_id INTEGER NOT NULL,
        property_type_id INTEGER NOT NULL,
        bedrooms INTEGER DEFAULT 0,
        bathrooms INTEGER DEFAULT 0,
        area_size REAL NOT NULL,
        area_unit TEXT DEFAULT 'Marla',
        price REAL NOT NULL,
        price_unit TEXT DEFAULT 'PKR',
        purpose TEXT DEFAULT 'sale',
        main_image TEXT,
        status TEXT DEFAULT 'approved',
        featured BOOLEAN DEFAULT 0,
        view_count INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (city_id) REFERENCES cities(id),
        FOREIGN KEY (area_id) REFERENCES areas(id),
        FOREIGN KEY (property_type_id) REFERENCES property_types(id)
    )");
    echo "Properties table created.\n";

    // Inquiries
    $db->exec("CREATE TABLE IF NOT EXISTS inquiries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        property_id INTEGER NOT NULL,
        user_id INTEGER,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT,
        message TEXT NOT NULL,
        status TEXT DEFAULT 'new',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");
    echo "Inquiries table created.\n";

    // Property Images
    $db->exec("CREATE TABLE IF NOT EXISTS property_images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        property_id INTEGER NOT NULL,
        image_url TEXT NOT NULL,
        sort_order INTEGER DEFAULT 0,
        FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
    )");
    echo "Property Images table created.\n";

    // Favorites
    $db->exec("CREATE TABLE IF NOT EXISTS favorites (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        property_id INTEGER NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, property_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
    )");
    echo "Favorites table created.\n";

    // Property Views
    $db->exec("CREATE TABLE IF NOT EXISTS property_views (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        property_id INTEGER NOT NULL,
        user_id INTEGER,
        ip_address TEXT,
        user_agent TEXT,
        viewed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
    )");
    echo "Property Views table created.\n";

    // Create Amenities table
    $db->exec("CREATE TABLE IF NOT EXISTS amenities (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        icon TEXT,
        status TEXT DEFAULT 'active'
    )");
    echo "Amenities table created.\n";

    // Create Property Amenities table
    $db->exec("CREATE TABLE IF NOT EXISTS property_amenities (
        property_id INTEGER NOT NULL,
        amenity_id INTEGER NOT NULL,
        PRIMARY KEY (property_id, amenity_id),
        FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
        FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
    )");
    echo "Property Amenities table created.\n";

    // 2. Seed Static Data
    // Seed Cities
    $stmt = $db->query("SELECT COUNT(*) FROM cities");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO cities (name) VALUES ('Lahore'), ('Karachi'), ('Islamabad')");
        echo "Cities seeded.\n";
    }

    // Seed Areas
    $stmt = $db->query("SELECT COUNT(*) FROM areas");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO areas (city_id, name) VALUES 
            (1, 'DHA Phase 6'), (1, 'Gulberg III'), (1, 'Bahria Town'), (1, 'Johar Town'),
            (2, 'Clifton'), (3, 'F-7')");
        echo "Areas seeded.\n";
    }

    // Seed Property Types
    $stmt = $db->query("SELECT COUNT(*) FROM property_types");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO property_types (name, icon) VALUES 
            ('House', 'bi-house'), ('Apartment', 'bi-building'), ('Plot', 'bi-layers'), ('Commercial', 'bi-shop')");
        echo "Property Types seeded.\n";
    }

    // Seed Amenities
    $stmt = $db->query("SELECT COUNT(*) FROM amenities");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO amenities (name, icon) VALUES 
            ('Garage', 'bi-car-front'), ('Swimming Pool', 'bi-water'), ('Garden', 'bi-flower1'), 
            ('Security', 'bi-shield-check'), ('Gym', 'bi-bicycle'), ('Internet', 'bi-wifi')");
        echo "Amenities seeded.\n";
    }


    // 3. Seed Users
    // Seed Admin User
    $adminEmail = 'admin@realestate.com';
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);
    
    $adminId = 0;
    if (!$stmt->fetch()) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Admin User', $adminEmail, $password, 'admin', '1234567890']);
        $adminId = $db->lastInsertId();
        echo "Admin user created.\n";
    } else {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$adminEmail]);
        $adminId = $stmt->fetchColumn();
    }

    // Seed Regular User
    $userEmail = 'john@example.com';
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$userEmail]);
    
    if (!$stmt->fetch()) {
        $password = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['John Doe', $userEmail, $password, 'user', '0987654321']);
        echo "Regular user created.\n";
    }


    // 4. Seed Properties
    $stmt = $db->query("SELECT COUNT(*) FROM properties");
    if ($stmt->fetchColumn() == 0 && $adminId > 0) {
        // Map: user_id, title, description, city_id, area_id, property_type_id, bedrooms, bathrooms, area_size, price, main_image, featured
        $properties = [
            [
                $adminId, 'Luxury Villa in DHA', 'Beautiful 1 kanal villa with modern amenities, swimming pool, and lush green lawn.', 
                1, 1, 1, 5, 6, 20, 45000000, 'house1.jpg', 1
            ],
            [
                $adminId, 'Modern Apartment in Gulberg', '3 bedroom luxury apartment with city view, gym access, and dedicated parking.', 
                1, 2, 2, 3, 3, 1800, 25000000, 'apt1.jpg', 1
            ],
            [
                $adminId, 'Commercial Plot in Bahria Town', 'Prime location 10 marla commercial plot suitable for plaza or office building.', 
                1, 3, 3, 0, 0, 10, 35000000, 'plot1.jpg', 0
            ],
            [
                $adminId, 'Cozy House in Johar Town', 'Newly constructed 10 marla house near Emporium Mall. Excellent finishing.', 
                1, 4, 1, 4, 4, 10, 28000000, 'house2.jpg', 0
            ]
        ];

        $stmt = $db->prepare("INSERT INTO properties (user_id, title, description, city_id, area_id, property_type_id, bedrooms, bathrooms, area_size, price, main_image, featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved')");
        
        foreach ($properties as $prop) {
            $stmt->execute($prop);
        }
        echo "Sample properties created.\n";
    }

    echo "Database setup completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
