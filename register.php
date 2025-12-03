<?php
session_start();
require_once __DIR__ . '/classes/Database.php';

$errors = [];
$success = "";
$name = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($name === '')        $errors[] = "Name is required.";
    if ($email === '')       $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if ($password === '' || $confirm === '') $errors[] = "Password and confirm are required.";
    elseif ($password !== $confirm)          $errors[] = "Passwords do not match.";
    elseif (strlen($password) < 6)           $errors[] = "Password must be at least 6 characters.";

    if (empty($errors)) {
        $db = new Database();
        $conn = $db->getConnection();

        // check if email already exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors[] = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("
                INSERT INTO admins (name, email, password_hash, created_at)
                VALUES (:name, :email, :password_hash, NOW())
            ");
            $ok = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password_hash' => $hash
            ]);

            if ($ok) {
                $success = "Admin registered successfully. You can now login.";
                $name = $email = "";
            } else {
                $errors[] = "Error inserting into database.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
</head>
<body>
    <h1>Register Admin</h1>

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

    <form method="post" action="register.php">
        <p>
            <label>Name:<br>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </label>
        </p>
        <p>
            <label>Email:<br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </label>
        </p>
        <p>
            <label>Password:<br>
                <input type="password" name="password">
            </label>
        </p>
        <p>
            <label>Confirm Password:<br>
                <input type="password" name="confirm">
            </label>
        </p>
        <button type="submit">Register</button>
    </form>

    <p><a href="index.php">Back to home</a></p>
</body>
</html>
