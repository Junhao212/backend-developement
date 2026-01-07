<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
    exit;
}

require_once __DIR__ . '/../classes/Order.php';

$userId = (int)$_SESSION['user_id'];
$orders = Order::getByUserId($userId);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Account | Bestellingen</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/shop.css">
</head>
<body>

<div class="container">
    <h1>Mijn bestellingen</h1>

    <p>
        <a class="btn" href="../index.php">← Terug naar shop</a>
        <a class="btn" href="change_password.php">Wachtwoord wijzigen</a>
    </p>

    <?php if (empty($orders)): ?>
        <div class="notice">Nog geen bestellingen.</div>
    <?php else: ?>
        <?php foreach ($orders as $o): ?>
            <div class="card">
                <strong><?= htmlspecialchars($o['title']) ?></strong><br>
                Prijs: €<?= number_format((float)$o['price'], 2, ',', '.') ?><br>
                Datum: <?= htmlspecialchars($o['created_at']) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
