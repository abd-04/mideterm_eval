<!-- General Error Page View -->
<!-- Software Construction & Development - Midterm Project -->
<!-- MVC Architecture - View Layer -->

<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'Error';
?>

<main id="main-content" class="main-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="error-page">
                    <!-- Error Icon -->
                    <div class="mb-4">
                        <i class="bi bi-exclamation-circle-fill display-1 text-danger"></i>
                    </div>
                    
                    <!-- Error Title -->
                    <h1 class="display-4 fw-bold text-muted mb-3">Oops!</h1>
                    <h2 class="h3 mb-4">Something went wrong</h2>
                    
                    <!-- Error Description -->
                    <p class="lead text-muted mb-4">
                        We encountered an unexpected error while processing your request.
                        Please try again later or contact support if the problem persists.
                    </p>
                    
                    <!-- Display any error messages -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php else: ?>
                        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            The system has logged this error for investigation.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="index.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-house me-2"></i>Go to Home
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Go Back
                        </button>
                    </div>
                    
                    <!-- Support Information -->
                    <div class="mt-5">
                        <h4 class="h5 mb-3">Need immediate help?</h4>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-envelope-fill text-primary display-6 mb-3"></i>
                                        <h6 class="card-title">Email Support</h6>
                                        <a href="mailto:support@realestateportal.com" class="text-decoration-none">
                                            support@realestateportal.com
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-telephone-fill text-success display-6 mb-3"></i>
                                        <h6 class="card-title">Phone Support</h6>
                                        <a href="tel:+15551234567" class="text-decoration-none">
                                            +1 (555) 123-4567
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Error Reference -->
                    <div class="mt-5">
                        <small class="text-muted">
                            Error Reference: <code><?php echo uniqid('ERR-'); ?></code>
                        </small>
                        <p class="text-muted mt-2">
                            <small>Please include this reference when contacting support</small>
                        </p>
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
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>