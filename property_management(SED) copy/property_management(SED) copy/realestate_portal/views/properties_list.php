<!-- Properties List Page View -->
<!-- Software Construction & Development - Midterm Project -->
<!-- MVC Architecture - View Layer -->

<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'Properties';
?>

<main id="main-content" class="main-content py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-5 fw-bold text-center">Property Listings</h1>
                <p class="lead text-center text-muted">Find your perfect property from our extensive collection</p>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-funnel me-2"></i>Filter Properties
                        </h5>
                        <form action="index.php" method="GET" class="row g-3">
                            <input type="hidden" name="page" value="properties">
                            
                            <div class="col-md-6 col-lg-2">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       placeholder="Enter city" value="<?php echo htmlspecialchars($searchParams['city'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6 col-lg-2">
                                <label for="property_type" class="form-label">Type</label>
                                <select class="form-select" id="property_type" name="property_type">
                                    <option value="">All Types</option>
                                    <option value="House" <?php echo (isset($searchParams['property_type']) && $searchParams['property_type'] === 'House') ? 'selected' : ''; ?>>House</option>
                                    <option value="Flat" <?php echo (isset($searchParams['property_type']) && $searchParams['property_type'] === 'Flat') ? 'selected' : ''; ?>>Flat</option>
                                    <option value="Plot" <?php echo (isset($searchParams['property_type']) && $searchParams['property_type'] === 'Plot') ? 'selected' : ''; ?>>Plot</option>
                                    <option value="Commercial" <?php echo (isset($searchParams['property_type']) && $searchParams['property_type'] === 'Commercial') ? 'selected' : ''; ?>>Commercial</option>
                                </select>
                            </div>
                            
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
                            
                            <div class="col-md-6 col-lg-2">
                                <label for="bedrooms" class="form-label">Bedrooms</label>
                                <select class="form-select" id="bedrooms" name="bedrooms">
                                    <option value="">Any</option>
                                    <option value="1" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '1') ? 'selected' : ''; ?>>1+</option>
                                    <option value="2" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '2') ? 'selected' : ''; ?>>2+</option>
                                    <option value="3" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '3') ? 'selected' : ''; ?>>3+</option>
                                    <option value="4" <?php echo (isset($searchParams['bedrooms']) && $searchParams['bedrooms'] === '4') ? 'selected' : ''; ?>>4+</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 col-lg-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <span id="results-count"><?php echo count($properties); ?></span> properties found
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleView('grid')" id="grid-view-btn">
                            <i class="bi bi-grid-3x3-gap"></i> Grid
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleView('list')" id="list-view-btn">
                            <i class="bi bi-list"></i> List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Properties Grid/List -->
        <?php if (!empty($properties)): ?>
            <div class="row g-4" id="properties-container">
                <?php foreach ($properties as $property): ?>
                    <div class="col-md-6 col-lg-4 property-item">
                        <div class="card property-card h-100 shadow-sm">
                            <!-- Property Image -->
                            <div class="property-image-wrapper">
                                <?php if (!empty($property['main_image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($property['main_image']); ?>" 
                                         class="card-img-top property-image" alt="<?php echo htmlspecialchars($property['title']); ?>">
                                <?php else: ?>
                                    <div class="property-image-placeholder bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-house-fill text-muted display-4"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Property Type Badge -->
                                <span class="badge bg-primary property-type-badge">
                                    <?php echo htmlspecialchars($property['property_type_name']); ?>
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
                                    <p class="text-muted small mb-0">
                                        <?php if ($property['bedrooms'] > 0): ?>
                                            <?php echo htmlspecialchars($property['bedrooms']); ?> bedrooms • 
                                        <?php endif; ?>
                                        <?php if ($property['bathrooms'] > 0): ?>
                                            <?php echo htmlspecialchars($property['bathrooms']); ?> bathrooms • 
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($property['area_size']); ?> sq ft
                                    </p>
                                </div>
                                
                                <p class="card-text flex-grow-1 small text-muted">
                                    <?php echo htmlspecialchars(substr($property['description'], 0, 120)); ?>...
                                </p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">
                                            Listed by: <?php echo htmlspecialchars($property['owner_name']); ?>
                                        </small>
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

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <nav aria-label="Property listings pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php else: ?>
            <!-- No Properties Found -->
            <div class="row">
                <div class="col-12 text-center">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <i class="bi bi-search display-1 text-muted mb-4"></i>
                            <h3 class="text-muted mb-3">No Properties Found</h3>
                            <p class="text-muted mb-4">
                                We couldn't find any properties matching your search criteria. 
                                Try adjusting your filters or browse all properties.
                            </p>
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="index.php?page=properties" class="btn btn-primary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                                </a>
                                <a href="index.php?page=add_property" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-circle me-2"></i>List Your Property
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Toggle between grid and list view
function toggleView(viewType) {
    const container = document.getElementById('properties-container');
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    
    if (viewType === 'list') {
        container.className = 'row g-3';
        container.querySelectorAll('.property-item').forEach(item => {
            item.className = 'col-12 property-item';
        });
        gridBtn.classList.remove('active');
        listBtn.classList.add('active');
    } else {
        container.className = 'row g-4';
        container.querySelectorAll('.property-item').forEach(item => {
            item.className = 'col-md-6 col-lg-4 property-item';
        });
        listBtn.classList.remove('active');
        gridBtn.classList.add('active');
    }
}

// Initialize default view
document.addEventListener('DOMContentLoaded', function() {
    toggleView('grid');
});
</script>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>