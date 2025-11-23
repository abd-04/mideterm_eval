-- Real Estate Portal Database Schema - Enhanced Version
-- Software Construction & Development Midterm Project
-- Design Patterns: Singleton (Database), MVC Architecture
-- Enhanced with comprehensive sample data and advanced features

CREATE DATABASE IF NOT EXISTS realestate_portal;
USE realestate_portal;

-- Users table for authentication and role management
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    profile_image VARCHAR(255) DEFAULT 'default-avatar.jpg',
    role ENUM('user', 'admin', 'agent') DEFAULT 'user',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Property types for better categorization
CREATE TABLE IF NOT EXISTS property_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cities for property locations
CREATE TABLE IF NOT EXISTS cities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    country VARCHAR(50) DEFAULT 'Pakistan',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Areas within cities
CREATE TABLE IF NOT EXISTS areas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    city_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
    INDEX idx_city_id (city_id),
    UNIQUE KEY unique_city_area (city_id, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Properties table for property listings
CREATE TABLE IF NOT EXISTS properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    city_id INT NOT NULL,
    area_id INT NOT NULL,
    property_type_id INT NOT NULL,
    bedrooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    area_size DECIMAL(10,2) NOT NULL,
    area_unit ENUM('sqft', 'sqyd', 'marla', 'kanal') DEFAULT 'sqft',
    price DECIMAL(15,2) NOT NULL,
    price_unit ENUM('total', 'per_sqft') DEFAULT 'total',
    status ENUM('pending', 'approved', 'rejected', 'inactive', 'sold', 'rented') DEFAULT 'pending',
    purpose ENUM('sale', 'rent') DEFAULT 'sale',
    featured BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    main_image VARCHAR(255),
    gallery_images JSON,
    amenities JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
    FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE CASCADE,
    FOREIGN KEY (property_type_id) REFERENCES property_types(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_city_id (city_id),
    INDEX idx_property_type_id (property_type_id),
    INDEX idx_status (status),
    INDEX idx_price (price),
    INDEX idx_featured (featured),
    INDEX idx_purpose (purpose),
    FULLTEXT idx_title_description (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Property images table for multiple images
CREATE TABLE IF NOT EXISTS property_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    is_main BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    INDEX idx_property_id (property_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Amenities table
CREATE TABLE IF NOT EXISTS amenities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    category VARCHAR(50),
    icon VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Property amenities junction table
CREATE TABLE IF NOT EXISTS property_amenities (
    property_id INT NOT NULL,
    amenity_id INT NOT NULL,
    PRIMARY KEY (property_id, amenity_id),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inquiries table for contact forms
CREATE TABLE IF NOT EXISTS inquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    user_id INT, -- nullable for guest inquiries
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied', 'closed') DEFAULT 'new',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_property_id (property_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Favorites table for user bookmarked properties
CREATE TABLE IF NOT EXISTS favorites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_property (user_id, property_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Property views tracking
CREATE TABLE IF NOT EXISTS property_views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    user_id INT, -- nullable for guest views
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_property_id (property_id),
    INDEX idx_user_id (user_id),
    INDEX idx_ip_address (ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Blog/News table for content marketing
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255),
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    view_count INT DEFAULT 0,
    tags JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_user_id (user_id),
    FULLTEXT idx_title_content (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Site settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert property types
INSERT INTO property_types (name, description, icon) VALUES
('House', 'Independent residential houses, villas, and bungalows', 'bi-house'),
('Flat', 'Apartments and flats in multi-story buildings', 'bi-building'),
('Plot', 'Residential and commercial plots of land', 'bi-map'),
('Commercial', 'Office spaces, shops, and commercial buildings', 'bi-shop'),
('Farm House', 'Country houses and farm properties', 'bi-tree'),
('Penthouse', 'Luxury top-floor apartments', 'bi-building-upper');

-- Insert cities
INSERT INTO cities (name) VALUES
('Karachi'),
('Lahore'),
('Islamabad'),
('Rawalpindi'),
('Faisalabad'),
('Multan'),
('Peshawar'),
('Quetta'),
('Hyderabad'),
('Gujranwala');

-- Insert areas for Karachi
INSERT INTO areas (city_id, name) VALUES
(1, 'DHA Phase 1'), (1, 'DHA Phase 2'), (1, 'DHA Phase 3'), (1, 'DHA Phase 4'),
(1, 'DHA Phase 5'), (1, 'DHA Phase 6'), (1, 'DHA Phase 7'), (1, 'DHA Phase 8'),
(1, 'Clifton Block 1'), (1, 'Clifton Block 2'), (1, 'Clifton Block 3'), (1, 'Clifton Block 4'),
(1, 'Gulshan-e-Iqbal'), (1, 'Gulistan-e-Johar'), (1, 'North Nazimabad'), (1, 'North Karachi'),
(1, 'Bahria Town Karachi'), (1, 'Scheme 33'), (1, 'PECHS'), (1, 'Tariq Road');

-- Insert areas for Lahore
INSERT INTO areas (city_id, name) VALUES
(2, 'DHA Lahore Phase 1'), (2, 'DHA Lahore Phase 2'), (2, 'DHA Lahore Phase 3'), (2, 'DHA Lahore Phase 4'),
(2, 'DHA Lahore Phase 5'), (2, 'DHA Lahore Phase 6'), (2, 'DHA Lahore Phase 7'), (2, 'DHA Lahore Phase 8'),
(2, 'Gulberg'), (2, 'Model Town'), (2, 'Johar Town'), (2, 'Wapda Town'),
(2, 'Bahria Town Lahore'), (2, 'Valencia Town'), (2, 'Faisal Town'), (2, 'Garden Town'),
(2, 'Iqbal Town'), (2, 'Canal Bank Road'), (2, 'Raiwind Road'), (2, 'Bedian Road');

-- Insert areas for Islamabad
INSERT INTO areas (city_id, name) VALUES
(3, 'DHA Islamabad'), (3, 'Bahria Town Islamabad'), (3, 'Gulberg Islamabad'), (3, 'F-6'),
(3, 'F-7'), (3, 'F-8'), (3, 'F-10'), (3, 'F-11'),
(3, 'G-6'), (3, 'G-7'), (3, 'G-8'), (3, 'G-9'),
(3, 'H-8'), (3, 'H-9'), (3, 'H-10'), (3, 'H-11'),
(3, 'I-8'), (3, 'I-9'), (3, 'I-10'), (3, 'Blue Area');

-- Insert amenities
INSERT INTO amenities (name, category, icon) VALUES
('Parking', 'Basic', 'bi-p-square'),
('Electricity', 'Basic', 'bi-lightning'),
('Water Supply', 'Basic', 'bi-water'),
('Gas', 'Basic', 'bi-fire'),
('Internet', 'Basic', 'bi-wifi'),
('Security', 'Safety', 'bi-shield-check'),
('CCTV Cameras', 'Safety', 'bi-camera-video'),
('Boundary Wall', 'Safety', 'bi-fence'),
('Maintenance Staff', 'Services', 'bi-people'),
('Cleaning Services', 'Services', 'bi-broom'),
('Waste Disposal', 'Services', 'bi-trash'),
('Laundry', 'Services', 'bi-basket'),
('Gym', 'Recreation', 'bi-bicycle'),
('Swimming Pool', 'Recreation', 'bi-water'),
('Playground', 'Recreation', 'bi-tree'),
('Garden', 'Recreation', 'bi-flower1'),
('Community Center', 'Recreation', 'bi-building'),
('Mosque', 'Religious', 'bi-moon'),
('School', 'Education', 'bi-book'),
('Hospital', 'Healthcare', 'bi-hospital');

-- Insert site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'Real Estate Portal', 'string', 'Name of the website'),
('site_description', 'Find your dream property with Pakistan\'s most trusted real estate portal', 'string', 'Site description for SEO'),
('site_keywords', 'real estate, property, buy, sell, rent, house, apartment', 'string', 'SEO keywords'),
('site_logo', 'logo.png', 'string', 'Site logo filename'),
('contact_email', 'info@realestateportal.com', 'string', 'Contact email address'),
('contact_phone', '+92 300 1234567', 'string', 'Contact phone number'),
('contact_address', '123 Main Street, Karachi, Pakistan', 'string', 'Physical address'),
('currency_symbol', 'PKR', 'string', 'Currency symbol'),
('currency_position', 'before', 'string', 'Currency position (before/after)'),
('items_per_page', '12', 'number', 'Number of items to show per page'),
('enable_registration', 'true', 'boolean', 'Allow new user registration'),
('require_email_verification', 'false', 'boolean', 'Require email verification'),
('enable_property_submission', 'true', 'boolean', 'Allow users to submit properties'),
('auto_approve_properties', 'false', 'boolean', 'Automatically approve new properties'),
('enable_favorites', 'true', 'boolean', 'Enable favorites feature'),
('enable_inquiries', 'true', 'boolean', 'Enable property inquiry system'),
('enable_blog', 'true', 'boolean', 'Enable blog/news section'),
('maintenance_mode', 'false', 'boolean', 'Put site in maintenance mode'),
('analytics_tracking_id', '', 'string', 'Google Analytics tracking ID'),
('facebook_url', 'https://facebook.com', 'string', 'Facebook page URL'),
('twitter_url', 'https://twitter.com', 'string', 'Twitter profile URL'),
('instagram_url', 'https://instagram.com', 'string', 'Instagram profile URL'),
('linkedin_url', 'https://linkedin.com', 'string', 'LinkedIn profile URL');

-- Insert admin user (password: admin123)
INSERT INTO users (name, email, password_hash, phone, role, status) VALUES 
('Admin User', 'admin@realestate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03001234567', 'admin', 'active');

-- Insert sample users with more variety
INSERT INTO users (name, email, password_hash, phone, role, status) VALUES 
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03011111111', 'user', 'active'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03022222222', 'user', 'active'),
('Bob Johnson', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03033333333', 'user', 'active'),
('Alice Williams', 'alice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03044444444', 'agent', 'active'),
('Charlie Brown', 'charlie@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03055555555', 'agent', 'active'),
('Diana Prince', 'diana@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03066666666', 'user', 'inactive'),
('Eva Green', 'eva@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03077777777', 'user', 'active'),
('Frank Miller', 'frank@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03088888888', 'agent', 'active'),
('Grace Lee', 'grace@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03099999999', 'user', 'active'),
('Henry Wilson', 'henry@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03100000000', 'user', 'active');

-- Insert sample properties with comprehensive data
INSERT INTO properties (user_id, title, description, city_id, area_id, property_type_id, bedrooms, bathrooms, area_size, area_unit, price, price_unit, status, purpose, featured, main_image) VALUES
-- Karachi Properties
(2, 'Luxury Villa in DHA Phase 5', 'Stunning luxury villa with modern architecture, featuring 5 bedrooms, private pool, and landscaped garden. Located in the most prestigious area of DHA with 24/7 security and all amenities.', 1, 5, 1, 5, 6, 500.00, 'sqyd', 45000000, 'total', 'approved', 'sale', true, 'villa-dha-1.jpg'),
(2, 'Modern Apartment in Clifton', 'Beautiful 3-bedroom apartment with sea view, located in the heart of Clifton. Features modern kitchen, marble flooring, and access to building amenities including gym and swimming pool.', 1, 9, 2, 3, 4, 180.00, 'sqft', 25000000, 'total', 'approved', 'sale', false, 'apartment-clifton-1.jpg'),
(3, 'Commercial Plaza in Gulshan', 'Prime location commercial building suitable for offices or retail businesses. High traffic area with excellent investment potential. Ground plus 3 floors with basement parking.', 1, 13, 4, 0, 4, 2000.00, 'sqft', 150000000, 'total', 'approved', 'sale', true, 'commercial-gulshan-1.jpg'),
(4, 'Residential Plot in Bahria Town', 'Corner plot in prime location of Bahria Town Karachi. Ideal for building your dream home. All utilities available including gas, electricity, and water connections.', 1, 17, 3, 0, 0, 500.00, 'sqyd', 15000000, 'total', 'approved', 'sale', false, 'plot-bahria-1.jpg'),
(5, 'Cozy Studio in PECHS', 'Perfect studio apartment for students or young professionals. Compact design with modern fittings, located near major commercial areas and transport links.', 1, 19, 2, 1, 1, 45.00, 'sqft', 1800000, 'total', 'approved', 'sale', false, 'studio-pechs-1.jpg'),

-- Lahore Properties
(3, 'Executive House in DHA Lahore', 'Executive style house in DHA Lahore Phase 6. Features 6 bedrooms, modern kitchen, servant quarters, and beautiful lawn. Perfect for large families.', 2, 6, 1, 6, 7, 1000.00, 'sqft', 75000000, 'total', 'approved', 'sale', true, 'house-dha-lahore-1.jpg'),
(4, 'Penthouse in Gulberg', 'Luxury penthouse with private terrace and panoramic city views. Features 4 bedrooms, modern fittings, and exclusive access to building amenities.', 2, 9, 6, 4, 5, 300.00, 'sqft', 45000000, 'total', 'approved', 'sale', true, 'penthouse-gulberg-1.jpg'),
(5, 'Office Space in Model Town', 'Commercial office space in the business district of Model Town. Suitable for corporate offices, clinics, or consultancy services. Prime location with parking.', 2, 10, 4, 0, 2, 150.00, 'sqft', 800000, 'total', 'approved', 'rent', false, 'office-model-town-1.jpg'),
(2, 'Apartment in Johar Town', 'Modern 2-bedroom apartment in Johar Town. Features include central air conditioning, modern kitchen, and access to community facilities.', 2, 11, 2, 2, 2, 120.00, 'sqft', 4200000, 'total', 'approved', 'sale', false, 'apartment-johar-1.jpg'),
(3, 'Farm House on Raiwind Road', 'Beautiful farm house on Raiwind Road with 2 acres of land. Features traditional architecture, fruit trees, and peaceful surroundings. Perfect for weekend retreats.', 2, 20, 5, 4, 3, 2000.00, 'sqft', 35000000, 'total', 'approved', 'sale', true, 'farmhouse-raiwind-1.jpg'),

-- Islamabad Properties
(4, 'Modern Villa in F-8', 'Contemporary villa in F-8 Islamabad with 5 bedrooms, modern architecture, and smart home features. Located in the most prestigious sector of Islamabad.', 3, 7, 1, 5, 6, 400.00, 'sqyd', 85000000, 'total', 'approved', 'sale', true, 'villa-f8-1.jpg'),
(5, 'Flat in F-10 Markaz', 'Well-maintained 3-bedroom flat in F-10 Markaz. Close to commercial areas, schools, and hospitals. Ideal for families looking for convenient city living.', 3, 8, 2, 3, 3, 150.00, 'sqft', 18000000, 'total', 'approved', 'sale', false, 'flat-f10-1.jpg'),
(2, 'Commercial Shop in Blue Area', 'Prime location commercial shop in Blue Area, Islamabad\'s main business district. High foot traffic area suitable for retail businesses, banks, or offices.', 3, 20, 4, 0, 1, 80.00, 'sqft', 25000000, 'total', 'approved', 'sale', false, 'shop-blue-area-1.jpg'),
(4, 'Residential Plot in DHA Islamabad', '500 sqyd residential plot in DHA Islamabad Phase 2. Ready for construction with all utilities available. Corner plot with park-facing location.', 3, 1, 3, 0, 0, 500.00, 'sqyd', 25000000, 'total', 'approved', 'sale', false, 'plot-dha-isb-1.jpg'),
(3, 'Penthouse in Bahria Town', 'Luxury penthouse in Bahria Town Islamabad with private terrace, jacuzzi, and BBQ area. Features 4 bedrooms and modern amenities.', 3, 2, 6, 4, 5, 350.00, 'sqft', 55000000, 'total', 'approved', 'sale', true, 'penthouse-bahria-1.jpg'),

-- Additional diverse properties
(6, 'Studio Apartment for Rent', 'Compact studio apartment available for rent in Gulshan-e-Iqbal. Perfect for students or working professionals. All utilities included.', 1, 13, 2, 1, 1, 35.00, 'sqft', 25000, 'total', 'approved', 'rent', false, 'studio-gulshan-1.jpg'),
(7, 'House for Rent in North Nazimabad', '3-bedroom house available for rent in North Nazimabad. Family-friendly neighborhood with schools and markets nearby. Parking available.', 1, 15, 1, 3, 4, 240.00, 'sqyd', 65000, 'total', 'approved', 'rent', false, 'house-north-nazimabad-1.jpg'),
(8, 'Commercial Office in Tariq Road', 'Prime commercial office space on Tariq Road main commercial area. Suitable for corporate office, showroom, or retail outlet.', 1, 20, 4, 0, 2, 100.00, 'sqft', 150000, 'total', 'approved', 'rent', false, 'office-tariq-road-1.jpg'),
(9, 'Luxury Apartment in Bahria Town Lahore', 'Brand new luxury apartment in Bahria Town Lahore with 3 bedrooms, modern kitchen, and community facilities including pool and gym.', 2, 13, 2, 3, 3, 200.00, 'sqft', 12000000, 'total', 'approved', 'sale', false, 'apartment-bahria-lahore-1.jpg'),
(10, 'Office in F-11 Markaz Islamabad', 'Prime office space in F-11 Markaz Islamabad. Ideal for IT companies, consultants, or corporate offices. Modern building with parking.', 3, 9, 4, 0, 2, 120.00, 'sqft', 180000, 'total', 'approved', 'rent', false, 'office-f11-1.jpg');

-- Insert property images for gallery
INSERT INTO property_images (property_id, image_path, caption, is_main, sort_order) VALUES
(1, 'villa-dha-1.jpg', 'Main Entrance', true, 1),
(1, 'villa-dha-2.jpg', 'Living Room', false, 2),
(1, 'villa-dha-3.jpg', 'Master Bedroom', false, 3),
(1, 'villa-dha-4.jpg', 'Swimming Pool', false, 4),
(2, 'apartment-clifton-1.jpg', 'Building Exterior', true, 1),
(2, 'apartment-clifton-2.jpg', 'Living Area', false, 2),
(2, 'apartment-clifton-3.jpg', 'Kitchen', false, 3),
(3, 'commercial-gulshan-1.jpg', 'Building Front', true, 1),
(3, 'commercial-gulshan-2.jpg', 'Interior Space', false, 2),
(6, 'house-dha-lahore-1.jpg', 'House Front', true, 1),
(6, 'house-dha-lahore-2.jpg', 'Garden View', false, 2),
(6, 'house-dha-lahore-3.jpg', 'Interior', false, 3);

-- Insert property amenities
INSERT INTO property_amenities (property_id, amenity_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 14), (1, 15), (1, 16),
(2, 1), (2, 2), (2, 3), (2, 5), (2, 6), (2, 7), (2, 14),
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5), (3, 6), (3, 7), (3, 19),
(6, 1), (6, 2), (6, 3), (6, 4), (6, 5), (6, 6), (6, 7), (6, 14), (6, 15), (6, 16), (6, 18),
(11, 1), (11, 2), (11, 3), (11, 4), (11, 5), (11, 6), (11, 7), (11, 14), (11, 15), (11, 16), (11, 18), (11, 19);

-- Insert sample inquiries
INSERT INTO inquiries (property_id, user_id, name, email, phone, message, status) VALUES
(1, NULL, 'Ali Khan', 'ali@example.com', '03044444444', 'I am interested in viewing this property. Is it available for viewing this weekend? Also, what are the maintenance charges?', 'new'),
(2, NULL, 'Sara Ahmed', 'sara@example.com', '03055555555', 'Please provide more details about the monthly maintenance charges and facilities. Is the price negotiable?', 'read'),
(3, NULL, 'Business Owner', 'owner@business.com', '03066666666', 'I would like to schedule a visit to discuss commercial rental possibilities. What are the lease terms?', 'replied'),
(6, 2, 'John Doe', 'john@example.com', '03011111111', 'I am interested in this property. Can we schedule a viewing?', 'new'),
(11, 3, 'Jane Smith', 'jane@example.com', '03022222222', 'This looks perfect for our family. What is the earliest we can move in?', 'new'),
(16, NULL, 'Ahmed Hassan', 'ahmed@example.com', '03077777777', 'Interested in this penthouse. Can you provide more photos and floor plan?', 'new'),
(21, NULL, 'Fatima Ali', 'fatima@example.com', '03088888888', 'Looking for a rental property in this area. Is this still available?', 'new'),
(26, NULL, 'Omar Khan', 'omar@example.com', '03099999999', 'Perfect location for our office. What are the lease terms and deposit requirements?', 'new');

-- Insert sample blog posts
INSERT INTO blog_posts (user_id, title, slug, content, excerpt, featured_image, status, tags) VALUES
(1, 'Top 10 Tips for First-Time Home Buyers', 'top-10-tips-first-time-home-buyers', 'Buying your first home is one of the most significant financial decisions you\'ll ever make. It\'s an exciting milestone, but it can also be overwhelming. Here are our top 10 tips to help first-time home buyers navigate the process successfully...

1. **Determine Your Budget**: Before you start looking at properties, get pre-approved for a mortgage. This will give you a clear idea of what you can afford and show sellers you\'re serious.

2. **Research Neighborhoods**: Location is everything in real estate. Research crime rates, school districts, amenities, and future development plans for areas you\'re considering.

3. **Work with a Reputable Agent**: A good real estate agent can guide you through the process, help you avoid pitfalls, and negotiate on your behalf.

4. **Get a Home Inspection**: Never skip the home inspection. It can reveal hidden problems that could cost you thousands in repairs.

5. **Consider Additional Costs**: Remember to budget for property taxes, insurance, maintenance, and potential HOA fees.

6. **Think Long-Term**: Consider your future needs. Will this home still work for you in 5-10 years?

7. **Don\'t Rush**: Take your time to find the right property. Don\'t let pressure force you into a decision you\'ll regret.

8. **Negotiate Wisely**: Your agent can help you make competitive offers based on market analysis.

9. **Understand the Contract**: Read all documents carefully and ask questions about anything you don\'t understand.

10. **Plan for Closing Costs**: Budget 2-5% of the home price for closing costs, inspections, and other fees.', 'Essential tips for first-time home buyers including budgeting, research, and working with professionals.', 'blog-first-time-buyer.jpg', 'published', '["home buying", "first-time buyer", "real estate tips", "property investment"]'),

(1, 'Real Estate Investment Strategies for 2024', 'real-estate-investment-strategies-2024', 'Real estate remains one of the most reliable investment options, but strategies need to evolve with changing market conditions. Here are the top investment strategies for 2024...

## Buy and Hold Strategy
This classic strategy involves purchasing properties and holding them for long-term appreciation. With Pakistan\'s growing population and urbanization, property values tend to increase over time.

## Rental Properties
Investing in rental properties can provide steady monthly income. Focus on areas with high rental demand like near universities, business districts, or hospitals.

## Fix and Flip
Buying undervalued properties, renovating them, and selling for profit. This strategy requires good market knowledge and renovation skills.

## Commercial Real Estate
Investing in office spaces, retail shops, or warehouses can yield higher returns than residential properties, though it requires more capital.

## Real Estate Investment Trusts (REITs)
For those who want real estate exposure without direct property ownership, REITs offer a way to invest in real estate through the stock market.

## Key Factors to Consider:
- Location analysis and market research
- Financing options and interest rates
- Property condition and renovation needs
- Local rental laws and regulations
- Market timing and economic indicators', 'Learn the best real estate investment strategies for 2024 including buy-and-hold, rentals, and commercial properties.', 'blog-investment-strategies.jpg', 'published', '["investment", "real estate", "2024 trends", "rental properties", "commercial real estate"]'),

(1, 'How to Stage Your Home for Quick Sale', 'how-stage-home-quick-sale', 'Home staging is a crucial step in selling your property quickly and at the best price. Here\'s a comprehensive guide to staging your home effectively...

## Why Home Staging Matters
Studies show that staged homes sell 73% faster than non-staged homes and often for higher prices. Staging helps buyers visualize themselves living in the space.

## Essential Staging Tips:

### 1. Declutter and Depersonalize
Remove personal photos, collections, and excess furniture. Create a clean, neutral canvas that appeals to a wide range of buyers.

### 2. Deep Clean Everything
Professional cleaning can make a huge difference. Pay attention to kitchens, bathrooms, windows, and floors.

### 3. Neutralize Colors
Paint walls in neutral colors like beige, gray, or white. This helps buyers focus on the space rather than bold color choices.

### 4. Maximize Lighting
Open all curtains and blinds, add lamps where needed, and ensure all light fixtures have bright, working bulbs.

### 5. Arrange Furniture Strategically
Create clear pathways and showcase the functionality of each room. Remove oversized furniture that makes spaces feel small.

### 6. Add Curb Appeal
First impressions matter. Maintain landscaping, paint the front door, and ensure the entrance is welcoming.

### 7. Create Inviting Spaces
Set the dining table, add fresh flowers, and create cozy seating areas that help buyers imagine living in the home.

## Room-by-Room Staging Guide:

**Living Room**: Create conversation areas, add throw pillows and blankets for warmth.

**Kitchen**: Clear countertops except for a few decorative items, add fresh fruit or flowers.

**Bedrooms**: Use neutral bedding, remove personal items, and ensure good lighting.

**Bathrooms**: Use white towels, add fresh soap, and ensure everything sparkles.

## Professional vs. DIY Staging
While professional staging can be expensive, it often pays for itself through faster sales and higher prices. For budget-conscious sellers, focus on the most important areas: living room, kitchen, and master bedroom.', 'Complete guide to home staging including tips, room-by-room advice, and professional vs DIY options.', 'blog-home-staging.jpg', 'published', '["home staging", "selling property", "real estate tips", "home improvement"]');

-- Insert sample property views
INSERT INTO property_views (property_id, user_id, ip_address, user_agent) VALUES
(1, NULL, '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(1, NULL, '192.168.1.2', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'),
(2, 2, '192.168.1.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(3, NULL, '192.168.1.4', 'Mozilla/5.0 (Linux; Android 10) AppleWebKit/537.36'),
(6, 3, '192.168.1.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(11, NULL, '192.168.1.6', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'),
(16, 4, '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(21, NULL, '192.168.1.8', 'Mozilla/5.0 (Linux; Android 11) AppleWebKit/537.36'),
(26, 5, '192.168.1.9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(1, NULL, '192.168.1.10', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36');

-- Update property view counts
UPDATE properties p 
SET view_count = (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id);

-- Create indexes for better performance
CREATE INDEX idx_properties_composite ON properties(city_id, property_type_id, status, price, purpose);
CREATE INDEX idx_inquiries_created ON inquiries(created_at);
CREATE INDEX idx_favorites_user ON favorites(user_id);
CREATE INDEX idx_property_views_created ON property_views(created_at);

-- Create views for statistics
CREATE VIEW property_statistics AS
SELECT 
    COUNT(*) as total_properties,
    COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_properties,
    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_properties,
    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_properties,
    COUNT(CASE WHEN purpose = 'sale' THEN 1 END) as sale_properties,
    COUNT(CASE WHEN purpose = 'rent' THEN 1 END) as rent_properties,
    COUNT(CASE WHEN featured = true THEN 1 END) as featured_properties,
    MIN(price) as min_price,
    MAX(price) as max_price,
    AVG(price) as avg_price
FROM properties;

CREATE VIEW user_statistics AS
SELECT 
    COUNT(*) as total_users,
    COUNT(CASE WHEN role = 'admin' THEN 1 END) as admin_users,
    COUNT(CASE WHEN role = 'agent' THEN 1 END) as agent_users,
    COUNT(CASE WHEN role = 'user' THEN 1 END) as regular_users,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_users,
    COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_users,
    COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN 1 END) as users_today,
    COUNT(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE) AND YEAR(created_at) = YEAR(CURRENT_DATE) THEN 1 END) as users_this_month
FROM users;

CREATE VIEW inquiry_statistics AS
SELECT 
    COUNT(*) as total_inquiries,
    COUNT(CASE WHEN status = 'new' THEN 1 END) as new_inquiries,
    COUNT(CASE WHEN status = 'read' THEN 1 END) as read_inquiries,
    COUNT(CASE WHEN status = 'replied' THEN 1 END) as replied_inquiries,
    COUNT(CASE WHEN status = 'closed' THEN 1 END) as closed_inquiries,
    COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN 1 END) as inquiries_today,
    COUNT(CASE WHEN DATE(created_at) >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) THEN 1 END) as inquiries_last_7_days
FROM inquiries;

-- Stored procedures for common operations
DELIMITER //

CREATE PROCEDURE GetPropertiesByFilter(
    IN city_id INT,
    IN property_type_id INT,
    IN min_price DECIMAL(15,2),
    IN max_price DECIMAL(15,2),
    IN bedrooms INT,
    IN purpose VARCHAR(10),
    IN limit_offset INT,
    IN limit_count INT
)
BEGIN
    SELECT 
        p.*,
        pt.name as property_type_name,
        pt.icon as property_type_icon,
        c.name as city_name,
        a.name as area_name,
        u.name as owner_name,
        u.phone as owner_phone,
        u.email as owner_email,
        (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count
    FROM properties p
    JOIN property_types pt ON p.property_type_id = pt.id
    JOIN cities c ON p.city_id = c.id
    JOIN areas a ON p.area_id = a.id
    JOIN users u ON p.user_id = u.id
    WHERE p.status = 'approved'
        AND (city_id IS NULL OR p.city_id = city_id)
        AND (property_type_id IS NULL OR p.property_type_id = property_type_id)
        AND (min_price IS NULL OR p.price >= min_price)
        AND (max_price IS NULL OR p.price <= max_price)
        AND (bedrooms IS NULL OR p.bedrooms >= bedrooms)
        AND (purpose IS NULL OR p.purpose = purpose)
    ORDER BY p.featured DESC, p.created_at DESC
    LIMIT limit_offset, limit_count;
END//

CREATE PROCEDURE GetFeaturedProperties(IN limit_count INT)
BEGIN
    SELECT 
        p.*,
        pt.name as property_type_name,
        pt.icon as property_type_icon,
        c.name as city_name,
        a.name as area_name,
        u.name as owner_name,
        (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as view_count
    FROM properties p
    JOIN property_types pt ON p.property_type_id = pt.id
    JOIN cities c ON p.city_id = c.id
    JOIN areas a ON p.area_id = a.id
    JOIN users u ON p.user_id = u.id
    WHERE p.status = 'approved' AND p.featured = true
    ORDER BY p.created_at DESC
    LIMIT limit_count;
END//

CREATE PROCEDURE GetPropertyStatistics()
BEGIN
    SELECT * FROM property_statistics;
END//

CREATE PROCEDURE GetUserStatistics()
BEGIN
    SELECT * FROM user_statistics;
END//

CREATE PROCEDURE GetInquiryStatistics()
BEGIN
    SELECT * FROM inquiry_statistics;
END//

DELIMITER ;

-- Grant permissions (adjust as needed for your setup)
-- GRANT SELECT, INSERT, UPDATE, DELETE ON realestate_portal.* TO 'realestate_user'@'localhost';
-- GRANT EXECUTE ON realestate_portal.* TO 'realestate_user'@'localhost';

-- Final database integrity check
SELECT 'Database setup complete!' as status,
       (SELECT COUNT(*) FROM users) as total_users,
       (SELECT COUNT(*) FROM properties) as total_properties,
       (SELECT COUNT(*) FROM cities) as total_cities,
       (SELECT COUNT(*) FROM areas) as total_areas,
       (SELECT COUNT(*) FROM inquiries) as total_inquiries,
       (SELECT COUNT(*) FROM blog_posts) as total_blog_posts;