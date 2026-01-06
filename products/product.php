<?php
session_start();

require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Comment.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($productId <= 0) {
    echo "Ongeldig product id.";
    exit;
}

$product = Product::findById($productId);
if (!$product) {
    echo "Product niet gevonden.";
    exit;
}

$comments = Comment::getByProduct($productId);

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title']) ?> | JW Shop</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<a href="../index.php">← Terug naar overzicht</a>

<h1><?= htmlspecialchars($product['title']) ?></h1>
<p><strong>Prijs:</strong> €<?= number_format((float)$product['price'], 2, ',', '.') ?></p>
<p><strong>Categorie:</strong> <?= htmlspecialchars($product['category'] ?? '-') ?></p>

<?php if (!empty($product['image'])): ?>
    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="product" style="max-width:250px;">
<?php endif; ?>

<hr>

<?php if ($isLoggedIn): ?>
    <form action="buy.php" method="POST">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <button type="submit">Koop</button>
    </form>
<?php else: ?>
    <p>Je moet eerst <a href="../authentication/login.php">inloggen</a> om te kopen.</p>
<?php endif; ?>

<hr>

<h2>Comments</h2>

<?php if (count($comments) === 0): ?>
    <p>Nog geen comments.</p>
<?php else: ?>
    <?php foreach ($comments as $c): ?>
        <div style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
            <p><strong>Rating:</strong> <?= (int)$c['rating'] ?>/5</p>
            <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
            <small><?= htmlspecialchars($c['created_at']) ?></small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($isLoggedIn): ?>
    <h3>Schrijf een comment</h3>
    <form action="../comments/add_comment.php" method="POST">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

        <textarea name="comment" rows="4" placeholder="Jouw comment..." required></textarea><br><br>

        <label>Rating (1-5)</label>
        <input type="number" name="rating" min="1" max="5" required><br><br>

        <button type="submit">Plaats comment</button>
    </form>
<?php endif; ?>

</body>
</html>
