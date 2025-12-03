<?php
session_start();

// Make sure only logged-in admins can see this page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/classes/database.php';

$errors = [];
$success = "";

$name = "";
$description = "";
$price = "";
$quantity = "";
$imagePath = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $quantity    = trim($_POST['quantity'] ?? '');
    $imagePath   = trim($_POST['image_path'] ?? '');

    // basic validation
    if ($name === '')        $errors[] = "Name is required.";
    if ($description === '') $errors[] = "Description is required.";
    if ($price === '')       $errors[] = "Price is required.";
    if ($quantity === '')    $errors[] = "Quantity is required.";

    if (!is_numeric($price)) {
        $errors[] = "Price must be a number.";
    }
    if (!ctype_digit($quantity)) {
        $errors[] = "Quantity must be an integer.";
    }

    if (empty($errors)) {
        $db = new Database();
        $conn = $db->getConnection();

        $priceVal    = (float)$price;
        $quantityVal = (int)$quantity;

        try {
            // note the backticks around `image path`, `created at`, `updated at`
            $sql = "
                INSERT INTO products
                    (`name`, `description`, `price`, `quantity`, `image path`, `created at`, `updated at`)
                VALUES
                    (:name, :description, :price, :quantity, :image_path, NOW(), NOW())
            ";

            $stmt = $conn->prepare($sql);
            $ok = $stmt->execute([
                ':name'       => $name,
                ':description'=> $description,
                ':price'      => $priceVal,
                ':quantity'   => $quantityVal,
                ':image_path' => $imagePath
            ]);

            if ($ok) {
                $success   = "Product added successfully.";
                // clear form
                $name = $description = $price = $quantity = $imagePath = "";
            } else {
                $errors[] = "Database insert failed.";
            }

        } catch (PDOException $e) {
            $errors[] = "DB ERROR in admin_add_product.php: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
</head>
<body>
    <h1>Add New Product</h1>

    <?php if (!empty($errors)): ?>
        <div style="color:red;">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color:green;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="admin_add_product.php">
        <p>
            <label>Name:<br>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </label>
        </p>
        <p>
            <label>Description:<br>
                <textarea name="description" rows="4" cols="40"><?php echo htmlspecialchars($description); ?></textarea>
            </label>
        </p>
        <p>
            <label>Price:<br>
                <input type="text" name="price" value="<?php echo htmlspecialchars($price); ?>">
            </label>
        </p>
        <p>
            <label>Quantity:<br>
                <input type="text" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
            </label>
        </p>
        <p>
            <label>Image path (e.g. images/iphone17.jpg):<br>
                <input type="text" name="image_path" value="<?php echo htmlspecialchars($imagePath); ?>">
            </label>
        </p>
        <button type="submit">Add Product</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
