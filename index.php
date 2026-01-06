<?php
session_start();
require_once __DIR__ . '/classes/product.php';

$products = Product::getAll();

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && (($_SESSION['role'] ?? '') === 'admin');
$coins = (int)($_SESSION['currency'] ?? 0);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>JW Shop</title>
  <link rel="stylesheet" href="css/indexroot.css">
</head>
<body>

<header class="navbar">
  <div class="nav-left">
    <a class="logo" href="index.php">JW<span>™</span></a>

    <nav class="nav-links">
      <a href="#">NEW IN</a>
      <a href="#">MEN</a>
      <a href="#">WOMEN</a>
      <a href="#">EYEWEAR</a>
      <a href="#">SNEAKERS</a>
      <a href="#">KIDS</a>
      <a href="#">BRAND</a>
    </nav>
  </div>

  <div class="nav-right">
    <span class="icon" title="Zoeken">🔍</span>
    <span class="icon" title="Favorieten">♡</span>
    <span class="icon" title="Winkelmand">🛒</span>

    <?php if ($isLoggedIn): ?>
      <div class="pill">Coins: <strong><?= $coins ?></strong></div>

      <?php if ($isAdmin): ?>
        <a class="btn btn-outline" href="admin/products.php">Admin</a>
        <a class="btn btn-primary" href="admin/product_add.php">+ Nieuw product</a>
      <?php endif; ?>

      <a class="btn btn-ghost" href="authentication/logout.php">Logout</a>
    <?php else: ?>
      <a class="btn btn-outline" href="authentication/login.php">Login</a>
      <a class="btn btn-primary" href="authentication/register.php">Register</a>
    <?php endif; ?>
  </div>
</header>

<section class="hero">
  <div class="hero-inner">
    <h1>JW Shop</h1>
    <p>Minimal fashion. Max impact.</p>
    <a class="btn btn-primary" href="#products">Shop sale</a>
  </div>
</section>

<main class="container">
  <div class="section-head" id="products">
    <h2>SHOP SALE</h2>
    <p class="muted">Producten uit je database.</p>
  </div>

  <?php if (empty($products)): ?>
    <div class="empty">
      <h3>Geen producten gevonden</h3>
      <p class="muted">Maak eerst een product aan via de admin.</p>

      <?php if ($isAdmin): ?>
        <a class="btn btn-primary" href="admin/product_add.php">+ Maak je eerste product</a>
      <?php else: ?>
        <p class="muted">Log in als admin om producten toe te voegen.</p>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <section class="grid">
      <?php foreach ($products as $p): ?>
        <article class="card">
          <a class="card-link" href="products/product.php?id=<?= (int)$p['id'] ?>">
            <div class="card-img">
              <?php if (!empty($p['image'])): ?>
                <!-- als jij uploads gebruikt: zet images in /uploads -->
                <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
              <?php else: ?>
                <div class="img-placeholder">JW</div>
              <?php endif; ?>
            </div>

            <div class="card-body">
              <h3><?= htmlspecialchars($p['title']) ?></h3>
              <p class="muted"><?= htmlspecialchars($p['category'] ?? '') ?></p>
              <div class="price">€<?= number_format((float)$p['price'], 2, ',', '.') ?></div>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>

<footer class="footer">
  <p>© <?= date('Y') ?> JW Shop</p>
</footer>

</body>
</html>
