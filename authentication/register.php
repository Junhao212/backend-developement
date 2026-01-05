<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        if ($user->emailExists()) {
            $message = "Dit e-mailadres bestaat al.";
        } else {
            $user->save('user');
            $message = "Account aangemaakt! Je kan nu inloggen.";
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
    <title>Register | JW Shop</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<form method="POST" class="auth-box">
    <h2>Register</h2>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>

    <button type="submit">Maak account aan</button>

    <p style="margin-top:10px;">
        Al een account? <a href="login.php">Login</a>
    </p>
</form>

</body>
</html>
