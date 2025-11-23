<!-- Login Page View -->
<!-- Software Construction & Development - Midterm Project -->
<!-- MVC Architecture - View Layer -->

<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'Login';
?>

<main id="main-content" class="main-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <!-- Login Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Logo/Brand -->
                        <div class="text-center mb-4">
                            <i class="bi bi-house-door-fill display-1 text-primary"></i>
                            <h2 class="mt-3 mb-2">Welcome Back</h2>
                            <p class="text-muted">Sign in to your account</p>
                        </div>

                        <!-- Display messages -->
                        <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($errors['general']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form action="index.php?page=login" method="POST" class="needs-validation" novalidate>
                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                           id="email" 
                                           name="email" 
                                           placeholder="Enter your email" 
                                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                                           required
                                           aria-describedby="emailHelp">
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['email']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">
                                    We'll never share your email with anyone else.
                                </small>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required
                                           minlength="8"
                                           aria-describedby="passwordHelp">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['password']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <small id="passwordHelp" class="form-text text-muted">
                                    Password must be at least 8 characters long.
                                </small>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary">
                                    Forgot password?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Sign In
                                </button>
                            </div>

                            <!-- Demo Credentials for Testing -->
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h6 class="card-title text-center mb-3">Demo Accounts</h6>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <small><strong>Admin:</strong> admin@realestate.com / admin123</small>
                                        </div>
                                        <div class="col-12">
                                            <small><strong>User:</strong> john@example.com / password</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="text-center mb-4">
                            <hr class="my-4">
                            <span class="text-muted">or</span>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-0">Don't have an account?</p>
                            <a href="index.php?page=register" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Security Note -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="bi bi-shield-lock me-1"></i>
                        Your information is secure and encrypted
                    </small>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Password visibility toggle
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});

// Auto-fill demo credentials
function fillDemoCredentials(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>