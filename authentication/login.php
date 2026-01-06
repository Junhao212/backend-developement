<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        $user = new User();

        // login() moet sessions zetten: user_id + role + currency
        $ok = $user->login($email, $password);

        if ($ok) {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                header("Location: ../admin/products.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            $message = "Ongeldige logingegevens";
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login | JW Shop</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<form method="POST" class="auth-box">
    <h2>Login</h2>

    <?php if (!empty($message)): ?>
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
