<?php
session_start();
require_once __DIR__ . '/../classes/Product.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../authentication/login.php");
  exit;
}

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;

foreach ($cart as $id => $qty) {
  $p = Product::findById((int)$id);
  if ($p) {
    $line = (float)$p['price'] * (int)$qty;
    $total += $line;
    $items[] = ['p' => $p, 'qty' => (int)$qty, 'line' => $line];
  }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <title>Winkelmandje</title>
  <link rel="stylesheet" href="../css/base.css">
  <link rel="stylesheet" href="../css/shop.css">
</head>
<body>
<div class="nav">
  <div class="container">
    <div class="brand"><a href="../index.php">JW Shop</a></div>
    <div>
      <span class="badge">Coins: <?= (int)($_SESSION['currency'] ?? 0) ?></span>
      <a href="../authentication/logout.php">Logout</a>
    </div>
  </div>
</div>

<div class="container">
  <h1>Winkelmandje</h1>

  <?php if (empty($items)): ?>
    <div class="notice">Je winkelmandje is leeg.</div>
  <?php else: ?>
    <?php foreach ($items as $it): ?>
      <div class="card">
        <strong><?= htmlspecialchars($it['p']['title']) ?></strong><br>
        Aantal: <?= $it['qty'] ?><br>
        Subtotaal: €<?= number_format($it['line'], 2, ',', '.') ?><br>
        <a class="btn danger" href="remove_from_cart.php?id=<?= (int)$it['p']['id'] ?>">Verwijder 1</a>
      </div>
    <?php endforeach; ?>

    <div class="card">
      <strong>Totaal: €<?= number_format($total, 2, ',', '.') ?></strong>
      <form method="POST" action="checkout.php" style="margin-top:10px;">
        <button class="btn" type="submit">Afrekenen</button>
      </form>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
