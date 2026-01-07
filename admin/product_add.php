<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../classes/Product.php';

$message = "";

function safeUploadImage(array $file): ?string
{
    if (empty($file['name'])) {
        return null;
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        throw new Exception("Upload mislukt");
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed, true)) {
        throw new Exception("Alleen JPG, PNG of WEBP toegelaten");
    }

    $filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    $destination = __DIR__ . "/../uploads/" . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Kon afbeelding niet opslaan");
    }

    return $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product = new Product();
        $product->setTitle($_POST['title']);
        $product->setPrice((float)$_POST['price']);
        $product->setCategory($_POST['category'] ?? '');

        $filename = safeUploadImage($_FILES['image'] ?? []);
        if ($filename !== null) {
            $product->setImage($filename);
        } else {
            $product->setImage('');
        }

        $product->add();
        $message = "Product succesvol toegevoegd!";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin | Nieuw product</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="container">
    <h1>Nieuw product toevoegen</h1>

    <p><a class="btn" href="products.php">← Terug</a></p>

    <?php if ($message): ?>
        <p class="notice"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-card">
        <div class="form-row">
            <label>Titel</label>
            <input type="text" name="title" placeholder="Titel" required>
        </div>

        <div class="form-row">
            <label>Prijs</label>
            <input type="number" step="0.01" name="price" placeholder="Prijs" required>
        </div>

        <div class="form-row">
            <label>Categorie</label>
            <input type="text" name="category" placeholder="Categorie">
        </div>

        <div class="form-row">
            <label>Afbeelding (optioneel)</label>
            <input type="file" name="image">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Product opslaan</button>
        </div>
    </form>
</div>

</body>
</html>
