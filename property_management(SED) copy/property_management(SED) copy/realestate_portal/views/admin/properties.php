<!-- Admin Properties View -->
<?php 
require_once ROOT_PATH . '/views/partials/header.php'; 
$pageTitle = 'Manage Properties';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=admin_dashboard">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=admin_users">
                            <i class="bi bi-people me-2"></i>
                            Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php?page=admin_properties">
                            <i class="bi bi-house-door me-2"></i>
                            Properties
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=admin_inquiries">
                            <i class="bi bi-envelope me-2"></i>
                            Inquiries
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Properties</h1>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($properties)): ?>
                                    <?php foreach ($properties as $property): ?>
                                        <tr>
                                            <td><?php echo $property['id']; ?></td>
                                            <td>
                                                <a href="index.php?page=property_details&id=<?php echo $property['id']; ?>" target="_blank">
                                                    <?php echo htmlspecialchars($property['title']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($property['type']); ?></td>
                                            <td><?php echo number_format($property['price']); ?> PKR</td>
                                            <td><?php echo htmlspecialchars($property['location']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo match($property['status']) {
                                                        'approved', 'available' => 'success',
                                                        'pending' => 'warning',
                                                        'rejected' => 'danger',
                                                        'sold' => 'info',
                                                        default => 'secondary'
                                                    };
                                                ?>">
                                                    <?php echo ucfirst($property['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <?php if ($property['status'] === 'pending'): ?>
                                                        <a href="index.php?page=admin_update_property_status&id=<?php echo $property['id']; ?>&status=approved" 
                                                           class="btn btn-success"
                                                           onclick="return confirm('Approve this property?')">
                                                            Approve
                                                        </a>
                                                        <a href="index.php?page=admin_update_property_status&id=<?php echo $property['id']; ?>&status=rejected" 
                                                           class="btn btn-danger"
                                                           onclick="return confirm('Reject this property?')">
                                                            Reject
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?page=delete_property&id=<?php echo $property['id']; ?>" 
                                                           class="btn btn-danger"
                                                           onclick="return confirm('Are you sure you want to delete this property completely?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center">No properties found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/partials/footer.php'; ?>
