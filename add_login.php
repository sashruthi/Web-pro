<?php
session_start();

if (!isset($_GET['role'])) {
    die('Role not specified.');
}

$role = $_GET['role'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $success = "User $username with role $role has been created successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New <?= ucfirst($role) ?> Login</title>
</head>
<body>
    <h2>Create New <?= ucfirst($role) ?> Login</h2>
    <form method="post" action="cashier_login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Create Login</button>
    </form>
    <?php
    if (isset($success)) {
        echo "<p style='color: green;'>$success</p>";
    }
    ?>
</body>
</html>
