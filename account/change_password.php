<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
    exit;
}

require_once __DIR__ . '/../classes/User.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        User::changePassword(
            (int)$_SESSION['user_id'],
            $_POST['old_password'] ?? '',
            $_POST['new_password'] ?? ''
        );
        $msg = "Wachtwoord gewijzigd!";
    } catch (Exception $e) {
        $msg = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Account | Wachtwoord wijzigen</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>

<div class="container">
    <h1>Wachtwoord wijzigen</h1>

    <p>
        <a class="btn" href="../index.php">← Terug naar shop</a>
        <a class="btn" href="orders.php">Mijn bestellingen</a>
    </p>

    <?php if ($msg): ?>
        <p class="notice"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="POST" class="box">
        <label>Oud wachtwoord</label>
        <input type="password" name="old_password" required>

        <label>Nieuw wachtwoord</label>
        <input type="password" name="new_password" required>

        <button type="submit">Wijzigen</button>
    </form>
</div>

</body>
</html>
