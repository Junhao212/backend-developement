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
    <link rel="stylesheet" href="../css/product.css">
</head>
<body>

<p><a href="../index.php">← Terug</a></p>

<h1><?= htmlspecialchars($product['title']) ?></h1>
<p><strong>Prijs:</strong> €<?= number_format((float)$product['price'], 2, ',', '.') ?></p>
<p><strong>Categorie:</strong> <?= htmlspecialchars($product['category'] ?? '-') ?></p>

<?php if (!empty($product['image'])): ?>
    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="product" style="max-width:250px;">
<?php endif; ?>

<hr>

<?php if ($isLoggedIn): ?>
    <form method="POST" action="add_to_cart.php">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <button type="submit">+ In winkelmandje</button>
    </form>

    <p><a href="cart.php">Ga naar winkelmandje</a></p>
<?php else: ?>
    <p><a href="../authentication/login.php">Log in om te kopen</a></p>
<?php endif; ?>

<hr>

<h2>Reviews / comments</h2>

<?php if (empty($comments)): ?>
    <p>Nog geen comments.</p>
<?php else: ?>
    <?php foreach ($comments as $c): ?>
        <div style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
            <strong><?= htmlspecialchars($c['email']) ?></strong> — rating: <?= (int)$c['rating'] ?>/5<br>
            <?= nl2br(htmlspecialchars($c['comment'])) ?><br>
            <small><?= htmlspecialchars($c['created_at']) ?></small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($isLoggedIn): ?>
    <h3>Schrijf een comment</h3>
    <form id="commentForm">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <textarea name="comment" rows="3" placeholder="Jouw comment..." required></textarea><br><br>
        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
        <button type="submit">Verstuur</button>
    </form>

    <p id="commentMsg"></p>

    <script>
        document.getElementById("commentForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            const res = await fetch("../comments/add_comment.php", {
                method: "POST",
                body: formData
            });

            const data = await res.json();
            document.getElementById("commentMsg").textContent = data.message;

            if (data.success) {
                setTimeout(() => location.reload(), 500);
            }
        });
    </script>
<?php endif; ?>

</body>
</html>
