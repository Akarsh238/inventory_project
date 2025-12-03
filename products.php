<?php
require_once __DIR__ . '/classes/database.php';

$db = new Database();
$conn = $db->getConnection();

$products = [];
$errorMsg = "";

try {
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "DB ERROR on products.php: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products</title>
</head>
<body>
    <h1>All Products</h1>

    <?php if ($errorMsg): ?>
        <p style="color:red;"><?php echo htmlspecialchars($errorMsg); ?></p>
    <?php elseif (empty($products)): ?>
        <p>No products found in the database.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <?php foreach (array_keys($products[0]) as $colName): ?>
                        <th><?php echo htmlspecialchars($colName); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?php echo htmlspecialchars((string)$value); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="index.php">Back to home</a></p>
</body>
</html>
