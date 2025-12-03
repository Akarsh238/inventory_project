<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$adminName = htmlspecialchars($_SESSION['admin_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <strong><?php echo $adminName; ?></strong>!</p>

    <h3>Admin Options</h3>
    <ul>
        <li><a href="admin_add_product.php">Add New Product</a></li>
        <li><a href="products.php">View All Products</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="index.php">Home</a></li>
    </ul>
</body>
</html>
