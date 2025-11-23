<!-- 404 Error Page View -->
<!-- Software Construction & Development - Midterm Project -->
<!-- MVC Architecture - View Layer -->

<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'Page Not Found';
http_response_code(404);
?>

<main id="main-content" class="main-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="error-page">
                    <!-- Error Icon -->
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle-fill display-1 text-warning"></i>
                    </div>
                    
                    <!-- Error Title -->
                    <h1 class="display-4 fw-bold text-muted mb-3">404</h1>
                    <h2 class="h3 mb-4">Page Not Found</h2>
                    
                    <!-- Error Description -->
                    <p class="lead text-muted mb-4">
                        Sorry, the page you're looking for doesn't exist. 
                        It might have been moved, deleted, or you entered the wrong URL.
                    </p>
                    
                    <!-- Display any error messages -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="index.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-house me-2"></i>Go to Home
                        </a>
                        <a href="index.php?page=properties" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-building me-2"></i>Browse Properties
                        </a>
                    </div>
                    
                    <!-- Search Suggestion -->
                    <div class="mt-5">
                        <h4 class="h5 mb-3">Looking for properties?</h4>
                        <form action="index.php?page=properties" method="GET" class="row g-3 justify-content-center">
                            <input type="hidden" name="page" value="properties">
                            <div class="col-12 col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="city" placeholder="Search by city...">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="mt-5">
                        <p class="text-muted mb-3">Still need help?</p>
                        <a href="mailto:support@realestateportal.com" class="text-decoration-none">
                            <i class="bi bi-envelope me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.error-page {
    padding: 2rem 0;
}

.error-page i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>