<?php
require_once __DIR__ . '/../classes/User.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        $user->setEmail($_POST['email'] ?? '');
        $user->setPassword($_POST['password'] ?? '');

        if ($user->emailExists()) {
            $message = "Dit e-mailadres bestaat al";
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
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>

<div class="container small">
    <h2>Registreren</h2>

    <?php if ($message): ?>
        <p class="notice"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="box">
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Wachtwoord" required>
        <button type="submit">Registreren</button>

        <p class="muted" style="margin-top:10px;">
            Heb je al een account? <a href="login.php">Login</a>
        </p>
    </form>
</div>

</body>
</html>
