# Real Estate Portal

## Software Construction & Development - Midterm Project

A comprehensive Property/Real Estate Management Portal built with modern web technologies, implementing industry-standard design patterns and best practices.

## 🏠 Project Overview

This Real Estate Portal is a full-featured web application that allows users to:
- **Browse and search** for properties
- **List properties** for sale or rent
- **Manage property listings** through a user dashboard
- **Contact property owners** through inquiry forms
- **Admin panel** for user and property management

## 🛠️ Tech Stack

### Frontend
- **HTML5** - Semantic markup with accessibility features
- **CSS3** - Custom styling with CSS variables
- **Bootstrap 5** - Responsive design framework
- **JavaScript ES6** - Client-side validation and interactivity

### Backend
- **PHP 8** - Server-side processing
- **MySQL** - Database management
- **XAMPP** - Local development environment

### Design Patterns Implemented
1. **Singleton Pattern** - Database connection management
2. **MVC Architecture** - Model-View-Controller pattern
3. **Front Controller** - Single entry point for all requests

## 📁 Project Structure

```
realestate_portal/
├── config/                 # Configuration files
│   ├── config.php         # Application constants
│   └── Database.php       # Singleton Database class
├── controllers/           # MVC Controllers
│   ├── AuthController.php    # Authentication logic
│   ├── PropertyController.php # Property management
│   └── AdminController.php   # Admin functionality
├── models/               # Data models
│   ├── User.php         # User model
│   ├── Property.php     # Property model
│   └── Inquiry.php      # Inquiry model
├── views/               # View templates
│   ├── partials/        # Reusable components
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── navbar.php
│   ├── auth/           # Authentication views
│   ├── property/       # Property views
│   └── admin/          # Admin views
├── public/             # Public assets
│   ├── assets/
│   │   ├── css/
│   │   │   └── styles.css
│   │   └── js/
│   │       └── main.js
│   ├── uploads/        # File uploads
│   └── index.php       # Front controller
├── realestate_portal.sql # Database schema
└── README.md           # This file
```

## 🚀 Features

### User Features
- **User Registration & Login** - Secure authentication system
- **Property Search** - Advanced filtering by location, price, type
- **Property Listings** - Browse all available properties
- **Property Details** - Detailed property information with images
- **User Dashboard** - Manage personal property listings
- **Contact Forms** - Inquire about properties
- **Responsive Design** - Works on all devices

### Admin Features
- **Admin Dashboard** - Overview of system statistics
- **User Management** - View and manage user accounts
- **Property Moderation** - Approve/reject property listings
- **Inquiry Management** - View and respond to inquiries

## 🔧 Installation & Setup

### Prerequisites
- XAMPP installed on your system (Windows)
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Safari, Edge)

### Automated Installation (Windows)

1. **Run the Installer**
   Open PowerShell in the project directory and run:
   ```powershell
   .\install_windows.ps1
   ```
   This script will:
   - Create the database
   - Import the schema and data
   - Copy the project to your XAMPP htdocs folder

2. **Access the Application**
   - Open browser and navigate to `http://localhost/realestate_portal/public`

### Manual Installation

1. **Start XAMPP Services**
   - Start Apache web server
   - Start MySQL database server

2. **Create Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `realestate_portal`
   - Import the `realestate_portal.sql` file

3. **Deploy Project**
   - Copy the `realestate_portal` folder to `C:\xampp\htdocs\`

4. **Access the Application**
   - Open browser and navigate to `http://localhost/realestate_portal/public`

### Default Login Credentials

**Admin Account:**
- Email: `admin@realestate.com`
- Password: `admin123`

**User Account:**
- Email: `john@example.com`
- Password: `password`

## 🎯 Design Patterns Implementation

### 1. Singleton Pattern (Database Connection)
```php
// Located in config/Database.php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Private constructor prevents direct instantiation
        $this->connection = new PDO($dsn, $user, $pass, $options);
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
```

### 2. MVC Architecture
- **Models**: Handle data and business logic
- **Views**: Present data to users
- **Controllers**: Process user input and update models

### 3. Front Controller Pattern
All requests are routed through `public/index.php` which acts as a single entry point.

## 🔒 Security Features

### Input Validation
- **Client-side validation** using HTML5 and JavaScript
- **Server-side validation** with PHP
- **Data sanitization** to prevent XSS attacks
- **Prepared statements** to prevent SQL injection

### Password Security
- **Password hashing** using PHP's password_hash()
- **Minimum password length** enforcement
- **Secure password storage** (never in plain text)

### Access Control
- **Role-based access** (User, Admin)
- **Session management** for user authentication
- **Authorization checks** on all protected pages

## ♿ Accessibility Features

- **Semantic HTML** structure
- **ARIA labels** and attributes
- **Keyboard navigation** support
- **High contrast** color schemes
- **Screen reader** compatibility
- **Skip to content** links
- **Alt text** for all images

## 📱 Responsive Design

The application is fully responsive and works on:
- **Desktop computers** (1200px+)
- **Tablets** (768px - 1199px)
- **Mobile phones** (320px - 767px)

Features Bootstrap's responsive grid system and mobile-first design approach.

## 🧪 Testing

### Manual Testing Checklist
- [ ] User registration and login
- [ ] Property creation and editing
- [ ] Image upload functionality
- [ ] Search and filtering
- [ ] Contact form submissions
- [ ] Admin panel access
- [ ] Responsive design on different devices
- [ ] Form validation (client and server-side)
- [ ] Error handling and user feedback

### Browser Compatibility
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)

## 📝 Code Quality

### Coding Standards
- **PSR-12** compliant PHP code
- **Semantic HTML5** markup
- **Consistent naming conventions**
- **Comprehensive comments**
- **Error handling** throughout

### Performance Optimizations
- **Database indexing** for faster queries
- **Image optimization** for web delivery
- **Minimal external dependencies**
- **Efficient CSS and JavaScript**

## 🎨 UI/UX Design

### Design Principles
- **Clean and minimal** interface
- **Consistent color scheme** (primary blue)
- **Intuitive navigation** structure
- **Clear visual hierarchy**
- **Accessible color contrast** (WCAG 2.1 AA compliant)

### User Experience
- **Progressive disclosure** of information
- **Clear call-to-action** buttons
- **Informative error messages**
- **Success confirmations**
- **Loading states** for better feedback

## 🚀 Deployment

### Production Deployment
1. **Update configuration** for production environment
2. **Enable error logging** instead of display
3. **Set up SSL certificate** for HTTPS
4. **Configure web server** (Apache/Nginx)
5. **Set up database** with production credentials
6. **Test all functionality** in production environment

### Environment Variables
```php
// Production configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'realestate_portal');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('APP_URL', 'https://yourdomain.com');
```

## 🤝 Contributing

This is a educational project for Software Construction & Development course. Contributions are welcome for educational purposes.

## 📄 License

This project is created for educational purposes as part of Software Construction & Development midterm project.

## 👨‍💻 Author

Created for Software Construction & Development Course
- **Institution**: [Your Institution Name]
- **Course**: Software Construction & Development
- **Project**: Midterm Examination

## 📞 Support

For technical support or questions about the project:
1. Check the documentation above
2. Review the code comments
3. Test with the provided demo accounts
4. Ensure all requirements are met

---

**Note**: This project demonstrates software engineering principles including design patterns, MVC architecture, security best practices, and modern web development techniques.