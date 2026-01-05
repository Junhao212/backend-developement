<?php
session_start();
require_once __DIR__ . '/classes/Product.php';

$products = Product::getAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JW Shop - Premium Fashion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <div class="nav-logo">JW™</div>

    <nav class="nav-links">
        <a href="#new">NEW IN</a>
        <a href="#men">MEN</a>
        <a href="#women">WOMEN</a>
        <a href="#eyewear">EYEWEAR</a>
        <a href="#sneakers">SNEAKERS</a>
        <a href="#kids">KIDS</a>
        <a href="#brand">BRAND</a>
    </nav>

    <div class="nav-auth">
        <span class="icon" role="button">🔍</span>
        <span class="icon" role="button">🤍</span>
        <span class="icon" role="button">🛒</span>

        <?php if (isset($_SESSION['user_id'])): ?>
            <span style="margin-right:10px;">Coins: <?= (int)($_SESSION['currency'] ?? 0) ?></span>

            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="admin/products.php" class="auth-btn Login-btn">Admin</a>
            <?php endif; ?>

            <a href="auth/logout.php" class="auth-btn Register-btn">Logout</a>
        <?php else: ?>
            <a href="auth/login.php" class="auth-btn Login-btn">Login</a>
            <a href="auth/register.php" class="auth-btn Register-btn">Register</a>
        <?php endif; ?>
    </div>
</header>

<section class="hero">
    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8" alt="JW Hero" class="hero-img">
    <button class="hero-button">Shop all</button>
</section>

<h2 class="section-title">SHOP SALE</h2>

<section class="products-grid">
    <?php foreach ($products as $p): ?>
        <article class="product-card">
            <span class="heart" role="button">🤍</span>

            <a href="products/product.php?id=<?= (int)$p['id'] ?>">
                <?php if (!empty($p['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                <?php else: ?>
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab" alt="Product">
                <?php endif; ?>

                <h3><?= htmlspecialchars($p['title']) ?></h3>
                <p class="price">€<?= number_format((float)$p['price'], 2, ',', '.') ?></p>
            </a>
        </article>
    <?php endforeach; ?>
</section>

</body>
</html>
