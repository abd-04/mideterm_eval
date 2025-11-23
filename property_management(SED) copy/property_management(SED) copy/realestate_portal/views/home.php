<!-- Home Page View - Enhanced Version -->
<!-- Software Construction & Development - Midterm Project -->
<!-- MVC Architecture - View Layer -->

<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'Home';
?>

<main id="main-content" class="main-content">
    <!-- Hero Section - Enhanced Design -->
    <section class="hero-section position-relative overflow-hidden">
        <div class="hero-background">
            <div class="hero-overlay"></div>
            <div class="hero-particles">
                <div class="particle particle-1"></div>
                <div class="particle particle-2"></div>
                <div class="particle particle-3"></div>
            </div>
        </div>
        
        <div class="container position-relative z-index-2">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-4 hero-title">
                            Find Your <span class="text-gradient">Dream Property</span>
                        </h1>
                        <p class="lead mb-5 hero-subtitle">
                            Discover the perfect home with Pakistan's most trusted real estate portal. 
                            Browse thousands of properties for sale and rent across major cities with 
                            advanced search and personalized recommendations.
                        </p>
                        
                        <div class="hero-stats mb-5">
                            <div class="row g-4">
                                <div class="col-4">
                                    <div class="stat-item text-center">
                                        <div class="stat-number"><?php echo number_format($propertyStats['total'] ?? 1000); ?></div>
                                        <div class="stat-label">Properties</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item text-center">
                                        <div class="stat-number"><?php echo number_format($propertyStats['total'] ?? 5000); ?></div>
                                        <div class="stat-label">Happy Clients</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item text-center">
                                        <div class="stat-number">10+</div>
                                        <div class="stat-label">Years Experience</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hero-actions d-flex flex-wrap gap-3">
                            <a href="index.php?page=properties" class="btn btn-primary btn-lg hero-btn">
                                <i class="bi bi-search me-2"></i>Explore Properties
                            </a>
                            <a href="index.php?page=register" class="btn btn-outline-light btn-lg hero-btn">
                                <i class="bi bi-plus-circle me-2"></i>List Your Property
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="hero-image-container">
                        <div class="hero-image-card">
                            <div class="hero-image-placeholder">
                                <i class="bi bi-house-heart-fill display-1 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Advanced Search Section -->
    <section class="search-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="search-card shadow-lg">
                        <div class="card-body p-4">
                            <h2 class="text-center mb-4">
                                <i class="bi bi-search me-2"></i>Find Your Perfect Property
                            </h2>
                            
                            <form action="index.php?page=properties" method="GET" class="row g-3">
                                <input type="hidden" name="page" value="properties">
                                
                                <!-- Purpose Filter -->
                                <div class="col-md-6 col-lg-2">
                                    <label for="purpose" class="form-label">Looking For</label>
                                    <select class="form-select" id="purpose" name="purpose">
                                        <option value="">Any</option>
                                        <option value="sale" <?php echo (isset($searchParams['purpose']) && $searchParams['purpose'] === 'sale') ? 'selected' : ''; ?>>For Sale</option>
                                        <option value="rent" <?php echo (isset($searchParams['purpose']) && $searchParams['purpose'] === 'rent') ? 'selected' : ''; ?>>For Rent</option>
                                    </select>
                                </div>
                                
                                <!-- City Filter -->
                                <div class="col-md-6 col-lg-2">
                                    <label for="city_id" class="form-label">City</label>
                                    <select class="form-select" id="city_id" name="city_id" onchange="loadAreas(this.value)">
                                        <option value="">All Cities</option>
                                        <?php foreach ($cities as $city): ?>
                                            <option value="<?php echo $city['id']; ?>" <?php echo (isset($searchParams['city_id']) && $searchParams['city_id'] == $city['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($city['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- Property Type Filter -->
                                <div class="col-md-6 col-lg-2">
                                    <label for="property_type_id" class="form-label">Property Type</label>
                                    <select class="form-select" id="property_type_id" name="property_type_id">
                                        <option value="">All Types</option>
                                        <?php foreach ($propertyTypes as $type): ?>
                                            <option value="<?php echo $type['id']; ?>" <?php echo (isset($searchParams['property_type_id']) && $searchParams['property_type_id'] == $type['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($type['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- Bedrooms Filter -->
                                <div class="col-md-6 col-lg-2">
                                    <label for="bedrooms" class="form-label">Bedrooms</label>
                                    <select class="form-select" id="bedrooms" name="bedrooms">
                                        <option value="">Any</option>
                                        <option value="1" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '1') ? 'selected' : ''; ?>>1+</option>
                                        <option value="2" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '2') ? 'selected' : ''; ?>>2+</option>
                                        <option value="3" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '3') ? 'selected' : ''; ?>>3+</option>
                                        <option value="4" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '4') ? 'selected' : ''; ?>>4+</option>
                                        <option value="5+" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '5+') ? 'selected' : ''; ?>>5+</option>
                                    </select>
                                </div>
                                
                                <!-- Price Range -->
                                <div class="col-md-6 col-lg-2">
                                    <label for="min_price" class="form-label">Min Price</label>
                                    <input type="number" class="form-control" id="min_price" name="min_price" 
                                           placeholder="Min Price" value="<?php echo htmlspecialchars($searchParams['min_price'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 col-lg-2">
                                    <label for="max_price" class="form-label">Max Price</label>
                                    <input type="number" class="form-control" id="max_price" name="max_price" 
                                           placeholder="Max Price" value="<?php echo htmlspecialchars($searchParams['max_price'] ?? ''); ?>">
                                </div>
                                
                                <!-- Search Button -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="bi bi-search me-2"></i>Search Properties
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Properties Section -->
    <?php if (!empty($featuredProperties)): ?>
        <section class="featured-properties py-5 bg-light">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <h2 class="display-5 fw-bold mb-3">Featured Properties</h2>
                        <p class="lead text-muted">Handpicked premium properties by our experts</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <?php foreach ($featuredProperties as $property): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="property-card h-100 shadow-sm">
                                <!-- Property Image -->
                                <div class="property-image-wrapper">
                                    <?php if (!empty($property['main_image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($property['main_image']); ?>" 
                                             class="property-image" alt="<?php echo htmlspecialchars($property['title']); ?>">
                                    <?php else: ?>
                                        <div class="property-image-placeholder bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-house-fill text-muted display-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Property Type Badge -->
                                    <span class="badge bg-primary property-type-badge">
                                        <i class="bi bi-<?php echo htmlspecialchars($property['property_type_icon'] ?? 'house'); ?> me-1"></i>
                                        <?php echo htmlspecialchars($property['property_type_name']); ?>
                                    </span>
                                    
                                    <!-- Featured Badge -->
                                    <span class="badge bg-warning featured-badge">
                                        <i class="bi bi-star-fill me-1"></i>Featured
                                    </span>
                                    
                                    <!-- Price Badge -->
                                    <span class="badge bg-success price-badge">
                                        PKR <?php echo number_format($property['price']); ?>
                                    </span>
                                </div>
                                
                                <!-- Card Body -->
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <h5 class="card-title mb-2"><?php echo htmlspecialchars($property['title']); ?></h5>
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?php echo htmlspecialchars($property['city_name']); ?>, <?php echo htmlspecialchars($property['area_name']); ?>
                                        </p>
                                        <div class="property-specs">
                                            <?php if ($property['bedrooms'] > 0): ?>
                                                <span class="spec-item">
                                                    <i class="bi bi-bed me-1"></i>
                                                    <?php echo htmlspecialchars($property['bedrooms']); ?> Beds
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($property['bathrooms'] > 0): ?>
                                                <span class="spec-item">
                                                    <i class="bi bi-bath me-1"></i>
                                                    <?php echo htmlspecialchars($property['bathrooms']); ?> Baths
                                                </span>
                                            <?php endif; ?>
                                            <span class="spec-item">
                                                <i class="bi bi-rulers me-1"></i>
                                                <?php echo htmlspecialchars($property['area_size']); ?> <?php echo htmlspecialchars($property['area_unit']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="card-text flex-grow-1 small text-muted">
                                        <?php echo htmlspecialchars(substr($property['description'], 0, 120)); ?>...
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="property-meta">
                                                <small class="text-muted">
                                                    <i class="bi bi-eye me-1"></i><?php echo number_format($property['view_count']); ?> views
                                                </small>
                                                <small class="text-muted ms-2">
                                                    <i class="bi bi-heart me-1"></i><?php echo number_format($property['favorite_count']); ?>
                                                </small>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($property['created_at'])); ?>
                                            </small>
                                        </div>
                                        
                                        <a href="index.php?page=property_details&id=<?php echo $property['id']; ?>" 
                                           class="btn btn-primary w-100">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- View All Properties Button -->
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <a href="index.php?page=properties" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-arrow-right me-2"></i>View All Properties
                        </a>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Recent Properties Section -->
    <?php if (!empty($recentProperties)): ?>
        <section class="recent-properties py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <h2 class="display-5 fw-bold mb-3">Recently Added</h2>
                        <p class="lead text-muted">Fresh listings updated daily</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <?php foreach (array_slice($recentProperties, 0, 6) as $property): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="property-card h-100 shadow-sm">
                                <!-- Property Image -->
                                <div class="property-image-wrapper">
                                    <?php if (!empty($property['main_image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($property['main_image']); ?>" 
                                             class="property-image" alt="<?php echo htmlspecialchars($property['title']); ?>">
                                    <?php else: ?>
                                        <div class="property-image-placeholder bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-house-fill text-muted display-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Property Type Badge -->
                                    <span class="badge bg-primary property-type-badge">
                                        <i class="bi bi-<?php echo htmlspecialchars($property['property_type_icon'] ?? 'house'); ?> me-1"></i>
                                        <?php echo htmlspecialchars($property['property_type_name']); ?>
                                    </span>
                                    
                                    <!-- New Badge -->
                                    <span class="badge bg-info new-badge">
                                        <i class="bi bi-clock me-1"></i>New
                                    </span>
                                    
                                    <!-- Purpose Badge -->
                                    <span class="badge bg-<?php echo $property['purpose'] === 'sale' ? 'success' : 'warning'; ?> purpose-badge">
                                        For <?php echo ucfirst(htmlspecialchars($property['purpose'])); ?>
                                    </span>
                                </div>
                                
                                <!-- Card Body -->
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <h5 class="card-title mb-2"><?php echo htmlspecialchars($property['title']); ?></h5>
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?php echo htmlspecialchars($property['city_name']); ?>, <?php echo htmlspecialchars($property['area_name']); ?>
                                        </p>
                                        <div class="property-specs">
                                            <?php if ($property['bedrooms'] > 0): ?>
                                                <span class="spec-item">
                                                    <i class="bi bi-bed me-1"></i>
                                                    <?php echo htmlspecialchars($property['bedrooms']); ?> Beds
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($property['bathrooms'] > 0): ?>
                                                <span class="spec-item">
                                                    <i class="bi bi-bath me-1"></i>
                                                    <?php echo htmlspecialchars($property['bathrooms']); ?> Baths
                                                </span>
                                            <?php endif; ?>
                                            <span class="spec-item">
                                                <i class="bi bi-rulers me-1"></i>
                                                <?php echo htmlspecialchars($property['area_size']); ?> <?php echo htmlspecialchars($property['area_unit']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="h5 text-primary mb-0">
                                                PKR <?php echo number_format($property['price']); ?>
                                            </span>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($property['created_at'])); ?>
                                            </small>
                                        </div>
                                        
                                        <a href="index.php?page=property_details&id=<?php echo $property['id']; ?>" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Statistics Section -->
    <section class="stats-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <i class="bi bi-house-door-fill display-4 mb-3"></i>
                        <h3 class="fw-bold"><?php echo number_format($propertyStats['total'] ?? 1000); ?></h3>
                        <p class="mb-0">Properties Listed</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <i class="bi bi-people-fill display-4 mb-3"></i>
                        <h3 class="fw-bold"><?php echo number_format($propertyStats['total'] ?? 5000); ?></h3>
                        <p class="mb-0">Happy Customers</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <i class="bi bi-trophy-fill display-4 mb-3"></i>
                        <h3 class="fw-bold">10+</h3>
                        <p class="mb-0">Years Experience</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <i class="bi bi-hand-thumbs-up-fill display-4 mb-3"></i>
                        <h3 class="fw-bold">95%</h3>
                        <p class="mb-0">Customer Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action Section -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="cta-card text-center p-5">
                        <h2 class="display-5 fw-bold mb-4">Ready to Find Your Dream Property?</h2>
                        <p class="lead mb-5">
                            Join thousands of satisfied customers who found their perfect home through our platform. 
                            Start your property journey today with Pakistan's most trusted real estate portal.
                        </p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="index.php?page=properties" class="btn btn-primary btn-lg">
                                <i class="bi bi-search me-2"></i>Browse Properties
                            </a>
                            <a href="index.php?page=register" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>List Your Property
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Load areas when city is selected
function loadAreas(cityId) {
    if (!cityId) {
        document.getElementById('area_id').innerHTML = '<option value="">All Areas</option>';
        return;
    }
    
    fetch(`index.php?page=get_areas&city_id=${cityId}`)
        .then(response => response.json())
        .then(areas => {
            let options = '<option value="">All Areas</option>';
            areas.forEach(area => {
                options += `<option value="${area.id}">${area.name}</option>`;
            });
            
            // Add area select if it doesn't exist
            if (!document.getElementById('area_id')) {
                const citySelect = document.getElementById('city_id');
                const areaDiv = document.createElement('div');
                areaDiv.className = 'col-md-6 col-lg-2';
                areaDiv.innerHTML = `
                    <label for="area_id" class="form-label">Area</label>
                    <select class="form-select" id="area_id" name="area_id">
                        ${options}
                    </select>
                `;
                citySelect.closest('.row').appendChild(areaDiv);
            } else {
                document.getElementById('area_id').innerHTML = options;
            }
        })
        .catch(error => {
            console.error('Error loading areas:', error);
        });
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
    
    // Animate statistics on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                $(entry.target).find('.stat-number').each(function() {
                    const $this = $(this);
                    const countTo = parseInt($this.text().replace(/,/g, ''));
                    $({ countNum: 0 }).animate({ countNum: countTo }, {
                        duration: 2000,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum).toLocaleString());
                        },
                        complete: function() {
                            $this.text(countTo.toLocaleString());
                        }
                    });
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe stats section
    if ($('.stats-section').length) {
        observer.observe($('.stats-section')[0]);
    }
});
</script>

<style>
/* Enhanced Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1;
}

.hero-particles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 0;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.particle-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.particle-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.particle-3 {
    width: 60px;
    height: 60px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(120deg); }
    66% { transform: translateY(10px) rotate(240deg); }
}

.hero-content {
    padding: 2rem 0;
}

.hero-title {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.2rem;
    line-height: 1.6;
}

.hero-stats {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
}

.stat-item {
    padding: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #ffffff;
}

.stat-label {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hero-btn {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.hero-image-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.hero-image-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 3rem;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hero-image-placeholder {
    width: 200px;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.1);
}

/* Enhanced Search Section */
.search-section {
    margin-top: -50px;
    position: relative;
    z-index: 10;
}

.search-card {
    background: white;
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Enhanced Property Cards */
.property-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.property-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.property-image-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.property-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.property-card:hover .property-image {
    transform: scale(1.05);
}

.property-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.property-type-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.featured-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.new-badge {
    position: absolute;
    top: 3.5rem;
    right: 1rem;
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.price-badge {
    position: absolute;
    bottom: 1rem;
    left: 1rem;
    font-size: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.purpose-badge {
    position: absolute;
    bottom: 1rem;
    right: 1rem;
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.property-specs {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}

.spec-item {
    font-size: 0.875rem;
    color: #6c757d;
}

.property-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Enhanced Stats Section */
.stats-section {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
}

.stat-item {
    padding: 2rem 1rem;
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-10px);
}

/* Enhanced CTA Section */
.cta-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.cta-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        min-height: 80vh;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .hero-stats {
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .search-section {
        margin-top: -30px;
    }
    
    .search-card {
        margin: 0 1rem;
    }
    
    .property-card {
        margin-bottom: 1rem;
    }
    
    .property-specs {
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-stats {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .hero-btn {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    
    .search-card {
        margin: 0 0.5rem;
    }
    
    .search-card .card-body {
        padding: 1.5rem;
    }
}

/* Animation Classes */
.fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}
</style>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>