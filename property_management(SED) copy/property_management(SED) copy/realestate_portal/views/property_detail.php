<!-- Property Detail Page View - Enhanced Version -->
<!-- Software Construction & Development - Midterm Project -->
<!-- MVC Architecture - View Layer -->

<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = isset($property) ? $property['title'] : 'Property Details';
?>

<main id="main-content" class="main-content py-5">
    <?php if (isset($property)): ?>
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=properties">Properties</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($property['title']); ?></li>
                </ol>
            </nav>

            <!-- Property Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="property-header">
                        <h1 class="display-6 fw-bold mb-3"><?php echo htmlspecialchars($property['title']); ?></h1>
                        <div class="property-meta-header d-flex flex-wrap gap-3 mb-3">
                            <span class="badge bg-primary">
                                <i class="bi bi-<?php echo htmlspecialchars($property['property_type_icon'] ?? 'house'); ?> me-1"></i>
                                <?php echo htmlspecialchars($property['property_type_name']); ?>
                            </span>
                            <span class="badge bg-<?php echo $property['purpose'] === 'sale' ? 'success' : 'warning'; ?>">
                                For <?php echo ucfirst(htmlspecialchars($property['purpose'])); ?>
                            </span>
                            <span class="badge bg-info">
                                <i class="bi bi-eye me-1"></i><?php echo number_format($property['view_count']); ?> views
                            </span>
                            <?php if ($property['featured']): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i>Featured
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            <?php echo htmlspecialchars($property['city_name']); ?>, <?php echo htmlspecialchars($property['area_name']); ?>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="price-display">
                        <h2 class="display-5 fw-bold text-primary mb-2">
                            PKR <?php echo number_format($property['price']); ?>
                        </h2>
                        <p class="text-muted mb-0">
                            <?php echo number_format($property['area_size']); ?> <?php echo htmlspecialchars($property['area_unit']); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Property Image Gallery -->
                    <div class="property-gallery mb-4">
                        <div class="main-image-container">
                            <?php if (!empty($property['main_image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($property['main_image']); ?>" 
                                     class="img-fluid rounded main-property-image" 
                                     alt="<?php echo htmlspecialchars($property['title']); ?>"
                                     id="mainPropertyImage">
                            <?php else: ?>
                                <div class="main-image-placeholder bg-light rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-house-fill text-muted display-1"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($property['images']) && count($property['images']) > 1): ?>
                            <div class="image-thumbnails mt-3">
                                <div class="row g-2">
                                    <div class="col-3 col-md-2">
                                        <img src="uploads/<?php echo htmlspecialchars($property['main_image']); ?>" 
                                             class="img-fluid rounded thumbnail active" 
                                             alt="Main Image"
                                             onclick="changeMainImage(this, 'uploads/<?php echo htmlspecialchars($property['main_image']); ?>')">
                                    </div>
                                    <?php foreach (array_slice($property['images'], 1, 3) as $image): ?>
                                        <div class="col-3 col-md-2">
                                            <img src="uploads/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                                 class="img-fluid rounded thumbnail" 
                                                 alt="<?php echo htmlspecialchars($image['caption'] ?? 'Property Image'); ?>"
                                                 onclick="changeMainImage(this, 'uploads/<?php echo htmlspecialchars($image['image_path']); ?>')">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Property Details -->
                    <div class="property-details-card shadow-sm rounded mb-4">
                        <div class="card-body p-4">
                            <h3 class="card-title h4 mb-4">
                                <i class="bi bi-info-circle me-2"></i>Property Details
                            </h3>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="property-info">
                                        <h5 class="mb-3">Basic Information</h5>
                                        <div class="info-list">
                                            <div class="info-item d-flex justify-content-between">
                                                <span><i class="bi bi-building me-2"></i>Property Type:</span>
                                                <strong><?php echo htmlspecialchars($property['property_type_name']); ?></strong>
                                            </div>
                                            <div class="info-item d-flex justify-content-between">
                                                <span><i class="bi bi-tag me-2"></i>Purpose:</span>
                                                <strong>For <?php echo ucfirst(htmlspecialchars($property['purpose'])); ?></strong>
                                            </div>
                                            <div class="info-item d-flex justify-content-between">
                                                <span><i class="bi bi-rulers me-2"></i>Area Size:</span>
                                                <strong><?php echo htmlspecialchars($property['area_size']); ?> <?php echo htmlspecialchars($property['area_unit']); ?></strong>
                                            </div>
                                            <div class="info-item d-flex justify-content-between">
                                                <span><i class="bi bi-currency-dollar me-2"></i>Price:</span>
                                                <strong class="text-primary">PKR <?php echo number_format($property['price']); ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="property-features">
                                        <h5 class="mb-3">Features</h5>
                                        <div class="features-grid">
                                            <?php if ($property['bedrooms'] > 0): ?>
                                                <div class="feature-item">
                                                    <i class="bi bi-bed text-primary me-2"></i>
                                                    <span><?php echo htmlspecialchars($property['bedrooms']); ?> Bedrooms</span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($property['bathrooms'] > 0): ?>
                                                <div class="feature-item">
                                                    <i class="bi bi-bath text-primary me-2"></i>
                                                    <span><?php echo htmlspecialchars($property['bathrooms']); ?> Bathrooms</span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Amenities -->
                                            <?php if (!empty($property['amenities'])): ?>
                                                <?php foreach (array_slice($property['amenities'], 0, 6) as $amenity): ?>
                                                    <div class="feature-item">
                                                        <i class="bi bi-<?php echo htmlspecialchars($amenity['icon'] ?? 'check'); ?> text-primary me-2"></i>
                                                        <span><?php echo htmlspecialchars($amenity['name']); ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Description -->
                    <div class="property-description-card shadow-sm rounded mb-4">
                        <div class="card-body p-4">
                            <h3 class="card-title h4 mb-4">
                                <i class="bi bi-file-text me-2"></i>Property Description
                            </h3>
                            <div class="description-content">
                                <?php echo nl2br(htmlspecialchars($property['description'])); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section (Placeholder) -->
                    <div class="property-location-card shadow-sm rounded mb-4">
                        <div class="card-body p-4">
                            <h3 class="card-title h4 mb-4">
                                <i class="bi bi-map me-2"></i>Location
                            </h3>
                            <div class="map-placeholder bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                <div class="text-center">
                                    <i class="bi bi-map-fill text-muted display-4 mb-3"></i>
                                    <h5 class="text-muted">Interactive Map</h5>
                                    <p class="text-muted">Location: <?php echo htmlspecialchars($property['city_name']); ?>, <?php echo htmlspecialchars($property['area_name']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Similar Properties -->
                    <?php if (!empty($similarProperties)): ?>
                        <div class="similar-properties-card shadow-sm rounded mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title h4 mb-4">
                                    <i class="bi bi-houses me-2"></i>Similar Properties
                                </h3>
                                <div class="row g-3">
                                    <?php foreach ($similarProperties as $similar): ?>
                                        <div class="col-md-6">
                                            <div class="similar-property-item">
                                                <div class="row g-2">
                                                    <div class="col-4">
                                                        <?php if (!empty($similar['main_image'])): ?>
                                                            <img src="uploads/<?php echo htmlspecialchars($similar['main_image']); ?>" 
                                                                 class="img-fluid rounded" alt="<?php echo htmlspecialchars($similar['title']); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                                <i class="bi bi-house-fill text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-8">
                                                        <h6 class="mb-1">
                                                            <a href="index.php?page=property_details&id=<?php echo $similar['id']; ?>" 
                                                               class="text-decoration-none">
                                                                <?php echo htmlspecialchars(substr($similar['title'], 0, 30)); ?>...
                                                            </a>
                                                        </h6>
                                                        <p class="text-muted small mb-1">
                                                            <?php echo htmlspecialchars($similar['city_name']); ?>, <?php echo htmlspecialchars($similar['area_name']); ?>
                                                        </p>
                                                        <p class="text-primary fw-bold mb-0">
                                                            PKR <?php echo number_format($similar['price']); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Contact Owner Form -->
                    <div class="contact-owner-card shadow-sm rounded mb-4">
                        <div class="card-body p-4">
                            <h3 class="card-title h5 mb-4">
                                <i class="bi bi-envelope me-2"></i>Contact Owner
                            </h3>
                            
                            <?php if (isset($inquirySuccess) && $inquirySuccess): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Your inquiry has been sent successfully! The owner will contact you soon.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($inquiryErrors['general'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <?php echo htmlspecialchars($inquiryErrors['general']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Owner Info -->
                            <div class="owner-info mb-4 p-3 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <div class="owner-avatar me-3">
                                        <?php if (!empty($property['owner_profile_image'])): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($property['owner_profile_image']); ?>" 
                                                 class="rounded-circle" width="50" height="50" 
                                                 alt="<?php echo htmlspecialchars($property['owner_name']); ?>">
                                        <?php else: ?>
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-person-fill text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($property['owner_name']); ?></h6>
                                        <small class="text-muted">Property Owner</small>
                                    </div>
                                </div>
                                <div class="owner-contact mt-3">
                                    <p class="mb-1">
                                        <i class="bi bi-envelope me-2"></i>
                                        <?php echo htmlspecialchars($property['owner_email']); ?>
                                    </p>
                                    <?php if (!empty($property['owner_phone'])): ?>
                                        <p class="mb-0">
                                            <i class="bi bi-telephone me-2"></i>
                                            <?php echo htmlspecialchars($property['owner_phone']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <form action="index.php?page=property_details&id=<?php echo $property['id']; ?>" method="POST" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="inquiry_name" class="form-label">Your Name</label>
                                    <input type="text" 
                                           class="form-control <?php echo isset($inquiryErrors['name']) ? 'is-invalid' : ''; ?>" 
                                           id="inquiry_name" 
                                           name="name" 
                                           placeholder="Enter your full name"
                                           value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>"
                                           required>
                                    <?php if (isset($inquiryErrors['name'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($inquiryErrors['name']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="inquiry_email" class="form-label">Your Email</label>
                                    <input type="email" 
                                           class="form-control <?php echo isset($inquiryErrors['email']) ? 'is-invalid' : ''; ?>" 
                                           id="inquiry_email" 
                                           name="email" 
                                           placeholder="Enter your email address"
                                           value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>"
                                           required>
                                    <?php if (isset($inquiryErrors['email'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($inquiryErrors['email']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="inquiry_phone" class="form-label">Phone Number (Optional)</label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="inquiry_phone" 
                                           name="phone" 
                                           placeholder="Enter your phone number">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="inquiry_message" class="form-label">Message</label>
                                    <textarea class="form-control <?php echo isset($inquiryErrors['message']) ? 'is-invalid' : ''; ?>" 
                                              id="inquiry_message" 
                                              name="message" 
                                              rows="4" 
                                              placeholder="Enter your message or questions about this property..."
                                              required></textarea>
                                    <?php if (isset($inquiryErrors['message'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($inquiryErrors['message']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="submit" name="inquiry_submit" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-envelope me-2"></i>Send Inquiry
                                </button>
                                
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="bi bi-shield-lock me-1"></i>
                                        Your information is secure and will only be shared with the property owner.
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Property Summary -->
                    <div class="property-summary-card shadow-sm rounded mb-4">
                        <div class="card-body p-4">
                            <h3 class="card-title h5 mb-4">
                                <i class="bi bi-clipboard-data me-2"></i>Property Summary
                            </h3>
                            <div class="summary-list">
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Property Type:</span>
                                    <strong><?php echo htmlspecialchars($property['property_type_name']); ?></strong>
                                </div>
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Purpose:</span>
                                    <strong>For <?php echo ucfirst(htmlspecialchars($property['purpose'])); ?></strong>
                                </div>
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Price:</span>
                                    <strong class="text-primary">PKR <?php echo number_format($property['price']); ?></strong>
                                </div>
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Area:</span>
                                    <strong><?php echo htmlspecialchars($property['area_size']); ?> <?php echo htmlspecialchars($property['area_unit']); ?></strong>
                                </div>
                                <?php if ($property['bedrooms'] > 0): ?>
                                    <div class="summary-item d-flex justify-content-between mb-2">
                                        <span>Bedrooms:</span>
                                        <strong><?php echo htmlspecialchars($property['bedrooms']); ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if ($property['bathrooms'] > 0): ?>
                                    <div class="summary-item d-flex justify-content-between mb-2">
                                        <span>Bathrooms:</span>
                                        <strong><?php echo htmlspecialchars($property['bathrooms']); ?></strong>
                                    </div>
                                <?php endif; ?>
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Status:</span>
                                    <strong class="text-<?php 
                                        echo $property['status'] === 'approved' ? 'success' : 
                                            ($property['status'] === 'pending' ? 'warning' : 
                                            ($property['status'] === 'rejected' ? 'danger' : 'secondary')); 
                                    ?>">
                                        <?php echo ucfirst(htmlspecialchars($property['status'])); ?>
                                    </strong>
                                </div>
                                <div class="summary-item d-flex justify-content-between">
                                    <span>Listed:</span>
                                    <strong><?php echo date('M j, Y', strtotime($property['created_at'])); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions-card shadow-sm rounded">
                        <div class="card-body p-4">
                            <h3 class="card-title h5 mb-4">
                                <i class="bi bi-lightning me-2"></i>Quick Actions
                            </h3>
                            <div class="d-grid gap-2">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button class="btn btn-outline-primary" onclick="toggleFavorite(<?php echo $property['id']; ?>)" id="favoriteBtn">
                                        <i class="bi bi-heart<?php echo $isFavorited ? '-fill' : ''; ?> me-2"></i>
                                        <span><?php echo $isFavorited ? 'Remove from' : 'Add to'; ?> Favorites</span>
                                    </button>
                                <?php else: ?>
                                    <a href="index.php?page=login" class="btn btn-outline-primary">
                                        <i class="bi bi-heart me-2"></i>Add to Favorites
                                    </a>
                                <?php endif; ?>
                                
                                <button class="btn btn-outline-success" onclick="shareProperty()">
                                    <i class="bi bi-share me-2"></i>Share Property
                                </button>
                                
                                <button class="btn btn-outline-info" onclick="printProperty()">
                                    <i class="bi bi-printer me-2"></i>Print Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="error-page">
                        <i class="bi bi-exclamation-triangle-fill display-1 text-warning mb-4"></i>
                        <h2 class="mb-3">Property Not Found</h2>
                        <p class="lead text-muted mb-4">
                            The property you're looking for doesn't exist or has been removed.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="index.php?page=properties" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Browse Properties
                            </a>
                            <a href="index.php" class="btn btn-outline-primary">
                                <i class="bi bi-house me-2"></i>Go Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
// Change main image when thumbnail is clicked
function changeMainImage(thumbnail, imagePath) {
    // Remove active class from all thumbnails
    document.querySelectorAll('.thumbnail').forEach(img => {
        img.classList.remove('active');
    });
    
    // Add active class to clicked thumbnail
    thumbnail.classList.add('active');
    
    // Change main image
    const mainImage = document.getElementById('mainPropertyImage');
    if (mainImage) {
        mainImage.src = imagePath;
        mainImage.alt = thumbnail.alt;
    }
}

// Toggle favorite
function toggleFavorite(propertyId) {
    <?php if (isset($_SESSION['user_id'])): ?>
        fetch('index.php?page=toggle_favorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ property_id: propertyId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const btn = document.getElementById('favoriteBtn');
                const icon = btn.querySelector('i');
                const text = btn.querySelector('span');
                
                if (data.favorited) {
                    icon.className = 'bi bi-heart-fill me-2';
                    text.textContent = 'Remove from Favorites';
                    btn.className = 'btn btn-primary';
                } else {
                    icon.className = 'bi bi-heart me-2';
                    text.textContent = 'Add to Favorites';
                    btn.className = 'btn btn-outline-primary';
                }
                
                // Show success message
                showAlert(data.favorited ? 'Added to favorites!' : 'Removed from favorites!', 'success');
            } else {
                showAlert(data.error || 'Failed to toggle favorite', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Failed to toggle favorite', 'danger');
        });
    <?php else: ?>
        window.location.href = 'index.php?page=login';
    <?php endif; ?>
}

// Share property
function shareProperty() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($property['title']); ?>',
            text: 'Check out this property on Real Estate Portal',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showAlert('Property link copied to clipboard!', 'success');
        });
    }
}

// Print property
function printProperty() {
    window.print();
}

// Show alert message
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Remove alert after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

// Initialize page
$(document).ready(function() {
    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top - 100
        }, 1000);
    });
    
    // Image gallery functionality
    $('.thumbnail').on('click', function() {
        const imagePath = $(this).attr('src');
        $('#mainPropertyImage').attr('src', imagePath);
        $('.thumbnail').removeClass('active');
        $(this).addClass('active');
    });
    
    // Form validation
    $('.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
});
</script>

<style>
/* Enhanced Property Detail Styles */
.property-header {
    padding: 1rem 0;
}

.property-meta-header {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.property-gallery {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.main-image-container {
    position: relative;
    height: 400px;
    overflow: hidden;
}

.main-property-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.main-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.image-thumbnails {
    padding: 1rem;
    background: #f8f9fa;
}

.thumbnail {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}

.thumbnail:hover,
.thumbnail.active {
    border-color: #0d6efd;
    transform: scale(1.05);
}

/* Property Cards */
.property-details-card,
.property-description-card,
.property-location-card,
.contact-owner-card,
.property-summary-card,
.quick-actions-card,
.similar-properties-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.property-details-card:hover,
.property-description-card:hover,
.property-location-card:hover,
.contact-owner-card:hover,
.property-summary-card:hover,
.quick-actions-card:hover,
.similar-properties-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

/* Property Info */
.property-info .info-list {
    list-style: none;
    padding: 0;
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

/* Property Features */
.property-features .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 8px;
}

/* Contact Owner Card */
.contact-owner-card {
    position: sticky;
    top: 100px;
}

.owner-info {
    border: 1px solid #e9ecef;
    border-radius: 10px;
}

.owner-avatar img {
    border: 3px solid #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.owner-contact p {
    margin-bottom: 0.5rem;
}

/* Property Summary */
.summary-list {
    list-style: none;
    padding: 0;
}

.summary-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-item:last-child {
    border-bottom: none;
}

/* Similar Properties */
.similar-property-item {
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.similar-property-item:hover {
    border-color: #0d6efd;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.1);
}

/* Quick Actions */
.quick-actions-card {
    position: sticky;
    top: 400px;
}

/* Error Page */
.error-page {
    padding: 4rem 0;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .property-header {
        text-align: center;
    }
    
    .property-meta-header {
        justify-content: center;
    }
    
    .price-display {
        text-align: center;
        margin-top: 1rem;
    }
    
    .main-image-container {
        height: 300px;
    }
    
    .contact-owner-card,
    .quick-actions-card {
        position: static;
        margin-top: 2rem;
    }
    
    .property-features .features-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .main-image-container {
        height: 250px;
    }
    
    .thumbnail {
        height: 60px;
    }
    
    .similar-property-item {
        padding: 0.5rem;
    }
}

/* Print Styles */
@media print {
    .contact-owner-card,
    .quick-actions-card,
    .navbar,
    .footer {
        display: none !important;
    }
    
    .property-gallery {
        page-break-inside: avoid;
    }
    
    .property-details-card,
    .property-description-card {
        page-break-inside: avoid;
    }
}
</style>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>