<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
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
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="container">
    <h1>Producten overzicht</h1>

    <p>
        <a class="btn" href="product_add.php">+ Nieuw product</a>
        <a class="btn" href="../index.php">Shop</a>
        <a class="btn" href="../authentication/logout.php">Logout</a>
    </p>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Prijs</th>
            <th>Categorie</th>
            <th>Afbeelding</th>
            <th>Acties</th>
        </tr>

        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= (int)$p['id'] ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td>€<?= number_format((float)$p['price'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($p['category'] ?? '') ?></td>
                <td><?= htmlspecialchars($p['image'] ?? '') ?></td>
                <td class="actions">
                    <a class="btn" href="product_edit.php?id=<?= (int)$p['id'] ?>">Edit</a>
                    <a class="btn danger" href="product_delete.php?id=<?= (int)$p['id'] ?>"
                       onclick="return confirm('Zeker verwijderen?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
