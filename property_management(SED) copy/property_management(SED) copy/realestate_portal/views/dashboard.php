<!-- User Dashboard Page View -->
<!-- Software Construction & Development - Midterm Project -->
<!-- MVC Architecture - View Layer -->

<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'Dashboard';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}
?>

<main id="main-content" class="main-content py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-6 fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                        <p class="text-muted">Manage your properties and view inquiries</p>
                    </div>
                    <div>
                        <a href="index.php?page=add_property" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Property
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="dashboard-card text-center">
                    <div class="icon bg-primary text-white mx-auto">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="mt-3 mb-1"><?php echo count($properties); ?></h3>
                    <p class="text-muted mb-0">Total Properties</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-card text-center">
                    <div class="icon bg-success text-white mx-auto">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h3 class="mt-3 mb-1">
                        <?php echo count(array_filter($properties, function($p) { return $p['status'] === 'approved'; })); ?>
                    </h3>
                    <p class="text-muted mb-0">Active Properties</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-card text-center">
                    <div class="icon bg-info text-white mx-auto">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h3 class="mt-3 mb-1"><?php echo count($inquiries); ?></h3>
                    <p class="text-muted mb-0">Total Inquiries</p>
                </div>
            </div>
        </div>

        <!-- Properties Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4">My Properties</h2>
                    <a href="index.php?page=add_property" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus me-1"></i>Add New
                    </a>
                </div>

                <?php if (!empty($properties)): ?>
                    <div class="row g-4">
                        <?php foreach ($properties as $property): ?>
                            <div class="col-md-6 col-lg-4">
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
                                            <?php echo htmlspecialchars($property['property_type_name'] ?? 'N/A'); ?>
                                        </span>
                                        
                                        <!-- Status Badge -->
                                        <span class="badge bg-<?php 
                                            echo $property['status'] === 'approved' ? 'success' : 
                                                ($property['status'] === 'pending' ? 'warning' : 
                                                ($property['status'] === 'rejected' ? 'danger' : 'secondary')); 
                                        ?> status-badge">
                                            <?php echo ucfirst(htmlspecialchars($property['status'])); ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Card Body -->
                                    <div class="card-body d-flex flex-column">
                                        <div class="mb-3">
                                            <h5 class="card-title mb-2"><?php echo htmlspecialchars($property['title']); ?></h5>
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                <?php echo htmlspecialchars($property['city_name'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($property['area_name'] ?? 'N/A'); ?>
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
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="h5 text-primary mb-0">
                                                    PKR <?php echo number_format($property['price']); ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?php echo date('M j, Y', strtotime($property['created_at'])); ?>
                                                </small>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="d-flex gap-2">
                                                <a href="index.php?page=property_details&id=<?php echo $property['id']; ?>" 
                                                   class="btn btn-outline-primary btn-sm flex-fill">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                                <a href="index.php?page=edit_property&id=<?php echo $property['id']; ?>" 
                                                   class="btn btn-outline-secondary btn-sm flex-fill">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </a>
                                                <button onclick="confirmDelete(<?php echo $property['id']; ?>)" 
                                                        class="btn btn-outline-danger btn-sm flex-fill">
                                                    <i class="bi bi-trash me-1"></i>Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- No Properties -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-5 text-center">
                                <i class="bi bi-house display-1 text-muted mb-4"></i>
                                <h4 class="text-muted mb-3">No Properties Yet</h4>
                                <p class="text-muted mb-4">
                                    You haven't listed any properties yet. Start by adding your first property.
                                </p>
                                <a href="index.php?page=add_property" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Add Your First Property
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Inquiries Section -->
        <?php if (!empty($inquiries)): ?>
            <div class="row">
                <div class="col-12">
                    <h2 class="h4 mb-4">Recent Inquiries</h2>
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Property</th>
                                            <th>Inquirer</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($inquiries, 0, 5) as $inquiry): ?>
                                            <tr>
                                                <td>
                                                    <a href="index.php?page=property_details&id=<?php echo $inquiry['property_id']; ?>" 
                                                       class="text-decoration-none">
                                                        <?php echo htmlspecialchars($inquiry['property_title']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                                                <td>
                                                    <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>" 
                                                       class="text-decoration-none">
                                                        <?php echo htmlspecialchars($inquiry['email']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php if (!empty($inquiry['phone'])): ?>
                                                        <?php echo htmlspecialchars($inquiry['phone']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                                          title="<?php echo htmlspecialchars($inquiry['message']); ?>">
                                                        <?php echo htmlspecialchars($inquiry['message']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo date('M j, Y', strtotime($inquiry['created_at'])); ?>
                                                    </small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if (count($inquiries) > 5): ?>
                                <div class="text-center mt-3">
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        View All Inquiries
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Confirm property deletion
function confirmDelete(propertyId) {
    if (confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
        window.location.href = `index.php?page=delete_property&id=${propertyId}`;
    }
}
</script>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>