<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'List Your Property';
?>

<main id="main-content" class="main-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white p-4">
                        <h2 class="mb-0"><i class="bi bi-house-add me-2"></i>List Your Property</h2>
                        <p class="mb-0 opacity-75">Fill in the details to list your property for sale or rent.</p>
                    </div>
                    <div class="card-body p-5">
                        
                        <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($errors['general']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?page=add_property" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            
                            <!-- Basic Information -->
                            <h4 class="mb-4 text-primary border-bottom pb-2">Basic Information</h4>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Property Title</label>
                                <input type="text" class="form-control" id="title" name="title" required 
                                       value="<?php echo htmlspecialchars($formData['title'] ?? ''); ?>"
                                       placeholder="e.g., Luxury Villa in DHA Phase 6">
                                <?php if (isset($errors['title'])): ?>
                                    <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['title']); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required
                                          placeholder="Describe your property features, condition, and surroundings..."><?php echo htmlspecialchars($formData['description'] ?? ''); ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['description']); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="purpose" class="form-label fw-bold">Purpose</label>
                                    <select class="form-select" id="purpose" name="purpose" required>
                                        <option value="sale" <?php echo (isset($formData['purpose']) && $formData['purpose'] == 'sale') ? 'selected' : ''; ?>>For Sale</option>
                                        <option value="rent" <?php echo (isset($formData['purpose']) && $formData['purpose'] == 'rent') ? 'selected' : ''; ?>>For Rent</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="property_type_id" class="form-label fw-bold">Property Type</label>
                                    <select class="form-select" id="property_type_id" name="property_type_id" required>
                                        <option value="">Select Type</option>
                                        <?php foreach ($propertyTypes as $type): ?>
                                            <option value="<?php echo $type['id']; ?>" <?php echo (isset($formData['property_type_id']) && $formData['property_type_id'] == $type['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($type['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['property_type_id'])): ?>
                                        <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['property_type_id']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Location -->
                            <h4 class="mb-4 mt-5 text-primary border-bottom pb-2">Location</h4>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="city_id" class="form-label fw-bold">City</label>
                                    <select class="form-select" id="city_id" name="city_id" required onchange="loadAreas(this.value)">
                                        <option value="">Select City</option>
                                        <?php foreach ($cities as $city): ?>
                                            <option value="<?php echo $city['id']; ?>" <?php echo (isset($formData['city_id']) && $formData['city_id'] == $city['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($city['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['city_id'])): ?>
                                        <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['city_id']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="area_id" class="form-label fw-bold">Area</label>
                                    <select class="form-select" id="area_id" name="area_id" required <?php echo empty($formData['city_id']) ? 'disabled' : ''; ?>>
                                        <option value="">Select City First</option>
                                        <!-- Areas will be loaded via AJAX -->
                                    </select>
                                    <?php if (isset($errors['area_id'])): ?>
                                        <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['area_id']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Price and Area -->
                            <h4 class="mb-4 mt-5 text-primary border-bottom pb-2">Price & Area</h4>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label fw-bold">Price</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="price" name="price" required min="0"
                                               value="<?php echo htmlspecialchars($formData['price'] ?? ''); ?>">
                                        <select class="form-select" name="price_unit" style="max-width: 100px;">
                                            <option value="PKR">PKR</option>
                                        </select>
                                    </div>
                                    <?php if (isset($errors['price'])): ?>
                                        <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['price']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="area_size" class="form-label fw-bold">Area Size</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="area_size" name="area_size" required min="0" step="0.1"
                                               value="<?php echo htmlspecialchars($formData['area_size'] ?? ''); ?>">
                                        <select class="form-select" name="area_unit" style="max-width: 120px;">
                                            <option value="Marla" <?php echo (isset($formData['area_unit']) && $formData['area_unit'] == 'Marla') ? 'selected' : ''; ?>>Marla</option>
                                            <option value="Kanal" <?php echo (isset($formData['area_unit']) && $formData['area_unit'] == 'Kanal') ? 'selected' : ''; ?>>Kanal</option>
                                            <option value="Sq. Ft." <?php echo (isset($formData['area_unit']) && $formData['area_unit'] == 'Sq. Ft.') ? 'selected' : ''; ?>>Sq. Ft.</option>
                                        </select>
                                    </div>
                                    <?php if (isset($errors['area_size'])): ?>
                                        <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['area_size']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Features -->
                            <h4 class="mb-4 mt-5 text-primary border-bottom pb-2">Features</h4>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="bedrooms" class="form-label fw-bold">Bedrooms</label>
                                    <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0"
                                           value="<?php echo htmlspecialchars($formData['bedrooms'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="bathrooms" class="form-label fw-bold">Bathrooms</label>
                                    <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0"
                                           value="<?php echo htmlspecialchars($formData['bathrooms'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- Images -->
                            <h4 class="mb-4 mt-5 text-primary border-bottom pb-2">Images</h4>
                            
                            <div class="mb-4">
                                <label for="main_image" class="form-label fw-bold">Main Image</label>
                                <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*">
                                <div class="form-text">Upload a high-quality image of your property (JPG, PNG). Max size 5MB.</div>
                                <?php if (isset($errors['main_image'])): ?>
                                    <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['main_image']); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Submit Listing
                                </button>
                                <a href="index.php?page=dashboard" class="btn btn-outline-secondary">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function loadAreas(cityId) {
    const areaSelect = document.getElementById('area_id');
    
    if (!cityId) {
        areaSelect.innerHTML = '<option value="">Select City First</option>';
        areaSelect.disabled = true;
        return;
    }

    // In a real app, this would be an AJAX call
    // For now, we'll simulate it or use a simple JS object if data is available
    // Or better, let's make a real AJAX call if the controller supports it
    
    fetch(`index.php?page=api_get_areas&city_id=${cityId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Select Area</option>';
            data.forEach(area => {
                options += `<option value="${area.id}">${area.name}</option>`;
            });
            areaSelect.innerHTML = options;
            areaSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading areas:', error);
            // Fallback for demo if API fails
            areaSelect.innerHTML = '<option value="">Select Area</option>';
            areaSelect.disabled = false;
        });
}
</script>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>
