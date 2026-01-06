<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit;
}

require_once __DIR__ . '/../classes/Product.php';

$products = Product::getAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin | Producten</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Producten overzicht</h1>

<p>
    <a href="product_add.php">+ Nieuw product</a> |
    <a href="../index.php">Naar webshop</a> |
    <a href="../authentication/logout.php">Logout</a>
</p>

<?php if (empty($products)): ?>
    <p>Geen producten gevonden.</p>
<?php else: ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Prijs</th>
                <th>Categorie</th>
                <th>Afbeelding</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= (int)$p['id'] ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td>€<?= number_format((float)$p['price'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($p['category'] ?? '-') ?></td>
                <td>
                    <?php if (!empty($p['image'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" style="max-width:80px;">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <a href="product_edit.php?id=<?= (int)$p['id'] ?>">✏️ Bewerken</a> |
                    <a href="product_delete.php?id=<?= (int)$p['id'] ?>" onclick="return confirm('Zeker verwijderen?')">🗑️ Verwijderen</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
