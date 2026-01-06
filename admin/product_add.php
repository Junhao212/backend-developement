<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../classes/product.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product = new Product();
        $product->setTitle($_POST['title'] ?? '');
        $product->setPrice((float)($_POST['price'] ?? 0));
        $product->setCategory($_POST['category'] ?? '');
        $product->setImage(''); 

        $product->add();

        header("Location: products.php");
        exit;

    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuw product</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Nieuw product toevoegen</h1>

<?php if ($message): ?>
    <p style="color:red;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="title" placeholder="Titel" required><br><br>
    <input type="number" step="0.01" name="price" placeholder="Prijs" required><br><br>
    <input type="text" name="category" placeholder="Categorie"><br><br>

    <button type="submit">Product opslaan</button>
</form>

<p><a href="products.php">← Terug naar overzicht</a></p>

</body>
</html>
