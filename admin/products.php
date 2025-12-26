<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../classes/Product.php';

$products = Product::getAll();
?>

<h1>Producten overzicht</h1>

<p>
    <a href="product_add.php">➕ Nieuw product</a>
</p>

<table border="1" cellpadding="10" cellspacing="0">
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
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td>€<?= number_format((float)$p['price'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($p['category']) ?></td>
            <td>
                <?php if (!empty($p['image'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" width="80">
                <?php endif; ?>
            </td>
            <td>
                <a href="product_edit.php?id=<?= $p['id'] ?>">✏️ Bewerken</a>
                |
                <a href="product_delete.php?id=<?= $p['id'] ?>"
                   onclick="return confirm('Ben je zeker dat je dit product wil verwijderen?')">
                    🗑️ Verwijderen
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
