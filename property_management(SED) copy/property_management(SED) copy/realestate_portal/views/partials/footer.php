    <!-- Footer -->
    <footer class="text-white py-5 mt-5" style="background-color: #1e1e1e; border-top: 5px solid var(--primary-color);">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">
                        <i class="bi bi-house-door-fill me-2"></i>
                        <?php echo APP_NAME; ?>
                    </h5>
                    <p class="mb-3">
                        Your trusted partner in finding the perfect property. 
                        Buy, sell, or rent properties with confidence.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3" aria-label="Facebook">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                        <a href="#" class="text-light me-3" aria-label="Twitter">
                            <i class="bi bi-twitter fs-5"></i>
                        </a>
                        <a href="#" class="text-light me-3" aria-label="Instagram">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                        <a href="#" class="text-light" aria-label="LinkedIn">
                            <i class="bi bi-linkedin fs-5"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="index.php" class="text-light text-decoration-none">Home</a>
                        </li>
                        <li class="mb-2">
                            <a href="index.php?page=properties" class="text-light text-decoration-none">Properties</a>
                        </li>
                        <li class="mb-2">
                            <a href="index.php?page=login" class="text-light text-decoration-none">Login</a>
                        </li>
                        <li class="mb-2">
                            <a href="index.php?page=register" class="text-light text-decoration-none">Register</a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-3">
                    <h6 class="mb-3">Contact Info</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            123 Real Estate Ave, City
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            +1 (555) 123-4567
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            info@realestateportal.com
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <small>
                            Software Construction & Development Midterm Project
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/main.js"></script>
    
    <!-- Initialize tooltips and other Bootstrap components -->
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    
</body>
</html>