<?php
session_start();
require_once __DIR__ . '/classes/Product.php';

$category = $_GET['category'] ?? '';
$search = $_GET['q'] ?? '';

$categories = Product::getCategories();
$products = Product::getAll($category, $search);

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && (($_SESSION['role'] ?? '') === 'admin');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <title>JW Shop</title>
  <link rel="stylesheet" href="css/base.css">
  <link rel="stylesheet" href="css/shop.css">
</head>
<body>

<div class="nav">
  <div class="container">
    <div class="brand"><a href="index.php">JW Shop</a></div>
    <div>
      <?php if ($isLoggedIn): ?>
        <span class="badge">Coins: <?= (int)($_SESSION['currency'] ?? 0) ?></span>
        <?php if ($isAdmin): ?>
          <a href="admin/products.php">Admin</a>
        <?php endif; ?>
        <a href="products/cart.php">Winkelmandje</a>
        <a href="authentication/logout.php">Logout</a>
      <?php else: ?>
        <a href="authentication/login.php">Login</a>
        <a href="authentication/register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="container">
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="notice ok"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
      <div>
        <label>Categorie</label><br>
        <select name="category">
          <option value="">Alle</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>" <?= $c === $category ? 'selected' : '' ?>>
              <?= htmlspecialchars($c) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Zoeken</label><br>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Zoek product...">
      </div>

      <button class="btn" type="submit">Filter</button>
      <a class="btn" href="index.php">Reset</a>
    </form>
  </div>

  <h2>Producten</h2>

  <?php if (empty($products)): ?>
    <div class="notice">Geen producten gevonden.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($products as $p): ?>
        <div class="product">
          <h3><?= htmlspecialchars($p['title']) ?></h3>
          <div class="meta"><?= htmlspecialchars($p['category'] ?? '') ?></div>
          <div class="price">€<?= number_format((float)$p['price'], 2, ',', '.') ?></div>

          <a href="products/product.php?id=<?= (int)$p['id'] ?>">Bekijk</a>

          <?php if ($isLoggedIn): ?>
            <form method="POST" action="products/add_to_cart.php" style="margin-top:8px;">
              <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
              <button class="btn" type="submit">+ In winkelmandje</button>
            </form>
          <?php else: ?>
            <div class="meta" style="margin-top:8px;">Log in om te kopen</div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
