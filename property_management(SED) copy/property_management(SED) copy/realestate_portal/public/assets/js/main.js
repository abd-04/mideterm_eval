/**
 * Main JavaScript file for Real Estate Portal
 * Software Construction & Development - Midterm Project
 * 
 * This file contains client-side validation, form handling, and UI interactions.
 * Implements client-side validation as required by the marking scheme.
 */

// Global application object
const RealEstateApp = {
    // Initialize the application
    init: function() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupValidation();
    },

    // Set up global event listeners
    setupEventListeners: function() {
        // Handle form submissions
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
        
        // Handle input changes for real-time validation
        document.addEventListener('input', this.handleInputChange.bind(this));
        
        // Handle button clicks
        document.addEventListener('click', this.handleButtonClick.bind(this));
        
        // Handle page visibility changes
        document.addEventListener('visibilitychange', this.handleVisibilityChange.bind(this));
        
        // Handle keyboard navigation
        document.addEventListener('keydown', this.handleKeyboardNavigation.bind(this));
    },

    // Initialize UI components
    initializeComponents: function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Auto-hide alerts after 5 seconds
        this.setupAutoHideAlerts();

        // Setup smooth scrolling
        this.setupSmoothScrolling();

        // Setup form validation
        this.setupFormValidation();
    },

    // Setup form validation
    setupFormValidation: function() {
        // Get all forms that need validation
        const forms = document.querySelectorAll('.needs-validation');
        
        forms.forEach(form => {
            form.addEventListener('submit', this.validateForm.bind(this));
        });
    },

    // Validate form on submit
    validateForm: function(event) {
        const form = event.target;
        
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
        
        // Custom validation for specific fields
        this.performCustomValidation(form);
    },

    // Perform custom validation
    performCustomValidation: function(form) {
        // Password validation
        const passwordFields = form.querySelectorAll('input[type="password"]');
        passwordFields.forEach(field => {
            if (field.name === 'password' || field.name === 'confirm_password') {
                this.validatePassword(field);
            }
        });

        // Email validation
        const emailFields = form.querySelectorAll('input[type="email"]');
        emailFields.forEach(field => {
            this.validateEmail(field);
        });

        // Phone validation
        const phoneFields = form.querySelectorAll('input[name="phone"]');
        phoneFields.forEach(field => {
            this.validatePhone(field);
        });

        // Price validation
        const priceFields = form.querySelectorAll('input[name="price"], input[name="min_price"], input[name="max_price"]');
        priceFields.forEach(field => {
            this.validatePrice(field);
        });

        // Area size validation
        const areaFields = form.querySelectorAll('input[name="area_size"]');
        areaFields.forEach(field => {
            this.validateAreaSize(field);
        });
    },

    // Validate password
    validatePassword: function(field) {
        const password = field.value;
        const minLength = 8;
        
        if (password.length > 0 && password.length < minLength) {
            this.showFieldError(field, `Password must be at least ${minLength} characters long`);
        } else {
            this.clearFieldError(field);
        }

        // Check password confirmation
        if (field.name === 'confirm_password') {
            const passwordField = field.form.querySelector('input[name="password"]');
            if (passwordField && password !== passwordField.value) {
                this.showFieldError(field, 'Passwords do not match');
            }
        }
    },

    // Validate email
    validateEmail: function(field) {
        const email = field.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email.length > 0 && !emailRegex.test(email)) {
            this.showFieldError(field, 'Please enter a valid email address');
        } else {
            this.clearFieldError(field);
        }
    },

    // Validate phone number
    validatePhone: function(field) {
        const phone = field.value;
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        
        if (phone.length > 0 && !phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''))) {
            this.showFieldError(field, 'Please enter a valid phone number');
        } else {
            this.clearFieldError(field);
        }
    },

    // Validate price
    validatePrice: function(field) {
        const price = parseFloat(field.value);
        
        if (field.value.length > 0 && (isNaN(price) || price <= 0)) {
            this.showFieldError(field, 'Price must be a positive number');
        } else {
            this.clearFieldError(field);
        }
    },

    // Validate area size
    validateAreaSize: function(field) {
        const area = parseFloat(field.value);
        
        if (field.value.length > 0 && (isNaN(area) || area <= 0)) {
            this.showFieldError(field, 'Area size must be a positive number');
        } else {
            this.clearFieldError(field);
        }
    },

    // Show field error
    showFieldError: function(field, message) {
        // Remove existing error
        this.clearFieldError(field);
        
        // Add error class
        field.classList.add('is-invalid');
        
        // Create or update error message
        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    },

    // Clear field error
    clearFieldError: function(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    },

    // Setup validation rules
    setupValidation: function() {
        // Add validation attributes to forms
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.setAttribute('novalidate', 'true');
        });
    },

    // Handle form submission
    handleFormSubmit: function(event) {
        const form = event.target;
        
        // Check if it's a form we should handle
        if (!form.classList.contains('needs-validation')) {
            return;
        }
        
        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            this.showLoading(submitButton);
        }
        
        // Perform custom validation
        this.performCustomValidation(form);
        
        // Check if form is valid
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            if (submitButton) {
                this.hideLoading(submitButton);
            }
        }
        
        form.classList.add('was-validated');
    },

    // Handle input changes for real-time validation
    handleInputChange: function(event) {
        const field = event.target;
        
        // Only validate fields that are part of a form with validation
        const form = field.closest('.needs-validation');
        if (!form) return;
        
        // Debounce validation to avoid excessive checks
        clearTimeout(field.validationTimeout);
        field.validationTimeout = setTimeout(() => {
            this.validateField(field);
        }, 300);
    },

    // Validate individual field
    validateField: function(field) {
        // Basic HTML5 validation
        if (!field.checkValidity()) {
            this.showFieldError(field, field.validationMessage);
            return false;
        }
        
        // Custom validation based on field type
        switch (field.type) {
            case 'email':
                this.validateEmail(field);
                break;
            case 'password':
                this.validatePassword(field);
                break;
            case 'tel':
                this.validatePhone(field);
                break;
            default:
                // Check for specific field names
                if (field.name === 'price' || field.name === 'min_price' || field.name === 'max_price') {
                    this.validatePrice(field);
                } else if (field.name === 'area_size') {
                    this.validateAreaSize(field);
                } else {
                    this.clearFieldError(field);
                }
        }
        
        return true;
    },

    // Handle button clicks
    handleButtonClick: function(event) {
        const button = event.target.closest('button');
        if (!button) return;
        
        // Handle delete buttons
        if (button.classList.contains('btn-delete')) {
            event.preventDefault();
            this.handleDelete(button);
        }
        
        // Handle logout buttons
        if (button.classList.contains('btn-logout')) {
            event.preventDefault();
            this.handleLogout(button);
        }
        
        // Handle property status change buttons
        if (button.classList.contains('btn-status-change')) {
            event.preventDefault();
            this.handleStatusChange(button);
        }
    },

    // Handle delete action
    handleDelete: function(button) {
        const message = button.getAttribute('data-confirm-message') || 'Are you sure you want to delete this item?';
        
        if (confirm(message)) {
            // Show loading state
            this.showLoading(button);
            
            // Get the URL from data attribute or form action
            const url = button.getAttribute('data-url') || button.form?.action;
            
            if (url) {
                window.location.href = url;
            } else if (button.form) {
                button.form.submit();
            }
        }
    },

    // Handle logout
    handleLogout: function(button) {
        if (confirm('Are you sure you want to logout?')) {
            this.showLoading(button);
            window.location.href = button.getAttribute('data-url') || 'index.php?page=logout';
        }
    },

    // Handle status change
    handleStatusChange: function(button) {
        const message = button.getAttribute('data-confirm-message') || 'Are you sure you want to change the status?';
        
        if (confirm(message)) {
            this.showLoading(button);
            window.location.href = button.getAttribute('data-url');
        }
    },

    // Show loading state
    showLoading: function(element) {
        element.disabled = true;
        element.originalText = element.innerHTML;
        element.innerHTML = '<span class="loading"></span> Loading...';
    },

    // Hide loading state
    hideLoading: function(element) {
        element.disabled = false;
        element.innerHTML = element.originalText;
    },

    // Setup auto-hide alerts
    setupAutoHideAlerts: function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    },

    // Setup smooth scrolling
    setupSmoothScrolling: function() {
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    },

    // Handle page visibility change
    handleVisibilityChange: function() {
        if (document.hidden) {
            // Page is hidden, pause any animations or updates
            console.log('Page hidden - pausing updates');
        } else {
            // Page is visible, resume updates
            console.log('Page visible - resuming updates');
        }
    },

    // Handle keyboard navigation
    handleKeyboardNavigation: function(event) {
        // Handle Escape key to close modals/dropdowns
        if (event.key === 'Escape') {
            // Close any open modals
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            });
        }
        
        // Handle Enter key on buttons
        if (event.key === 'Enter' && event.target.tagName === 'BUTTON') {
            event.target.click();
        }
    },

    // Utility function to format numbers
    formatNumber: function(number) {
        return new Intl.NumberFormat('en-US').format(number);
    },

    // Utility function to format currency
    formatCurrency: function(amount, currency = 'PKR') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    },

    // Utility function to debounce function calls
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Utility function to throttle function calls
    throttle: function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
};

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    RealEstateApp.init();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealEstateApp;
}