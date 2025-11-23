<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT * FROM properties ORDER BY id DESC");
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database View - Properties</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status-approved { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Properties Table (Live Data)</h1>
    <p>This shows the raw data from your SQLite database.</p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Price</th>
                <th>City ID</th>
                <th>Area ID</th>
                <th>Status</th>
                <th>Image</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($properties as $p): ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['title']); ?></td>
                <td><?php echo number_format($p['price']); ?></td>
                <td><?php echo $p['city_id']; ?></td>
                <td><?php echo $p['area_id']; ?></td>
                <td class="status-<?php echo $p['status']; ?>"><?php echo $p['status']; ?></td>
                <td><?php echo htmlspecialchars($p['main_image']); ?></td>
                <td><?php echo $p['created_at']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
