<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = new User();

    if ($user->login($email, $password)) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: ../admin/products.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    } else {
        $message = "Ongeldige logingegevens";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login | JW Shop</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<form method="POST" class="auth-box">
    <h2>Login</h2>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>
    <button type="submit">Inloggen</button>

    <p style="margin-top:10px;">
        Geen account? <a href="register.php">Register</a>
    </p>
</form>

</body>
</html>
