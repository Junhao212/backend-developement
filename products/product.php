<?php
session_start();

require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Comment.php';

if (!isset($_GET['id'])) {
    die("Geen product gekozen");
}

$id = (int)$_GET['id'];
$product = Product::findById($id);

if (!$product) {
    die("Product niet gevonden");
}

$comments = Comment::getByProduct($id);

$successMsg = $_GET['success'] ?? '';
$errorMsg = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title']) ?> | JW Shop</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<p><a href="../index.php">← Terug naar shop</a></p>

<?php if ($successMsg): ?>
    <p><?= htmlspecialchars($successMsg) ?></p>
<?php endif; ?>

<?php if ($errorMsg): ?>
    <p><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<h1><?= htmlspecialchars($product['title']) ?></h1>

<?php if (!empty($product['image'])): ?>
    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" width="250" alt="">
<?php endif; ?>

<p>Prijs: <strong><?= number_format((float)$product['price'], 2, ',', '.') ?></strong> coins</p>
<p>Categorie: <?= htmlspecialchars($product['category']) ?></p>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>Jouw coins: <strong><?= (int)($_SESSION['currency'] ?? 0) ?></strong></p>

    <form method="POST" action="buy.php">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <button type="submit">Koop dit product</button>
    </form>
<?php else: ?>
    <p><a href="../auth/login.php">Log in</a> om te kopen.</p>
<?php endif; ?>

<hr>

<h2>Comments</h2>

<div id="commentsList">
    <?php foreach ($comments as $c): ?>
        <div style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
            <strong>Rating: <?= (int)$c['rating'] ?>/5</strong><br>
            <?= htmlspecialchars($c['comment']) ?><br>
            <small><?= htmlspecialchars($c['created_at']) ?></small>
        </div>
    <?php endforeach; ?>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
    <h3>Laat een comment achter (AJAX)</h3>

    <form id="commentForm">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <textarea name="comment" id="comment" placeholder="Schrijf je comment..." required></textarea><br><br>

        <select name="rating" id="rating" required>
            <option value="">Rating</option>
            <option value="1">1/5</option>
            <option value="2">2/5</option>
            <option value="3">3/5</option>
            <option value="4">4/5</option>
            <option value="5">5/5</option>
        </select>

        <button type="submit">Verstuur</button>
    </form>

    <script>
    document.getElementById("commentForm").addEventListener("submit", async function(e){
        e.preventDefault();

        const formData = new FormData(this);

        const res = await fetch("../comments/add_comment.php", {
            method: "POST",
            body: formData
        });

        const data = await res.json();
        alert(data.message);

        if (data.success) {
            location.reload(); 
        }
    });
    </script>
<?php else: ?>
    <p><a href="../auth/login.php">Log in</a> om te commenten.</p>
<?php endif; ?>

</body>
</html>
