<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../classes/Product.php';

$message = "";

function safeUploadImage(array $file): string
{
    if (empty($file['name'])) {
        throw new Exception("Afbeelding verplicht");
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

        $filename = safeUploadImage($_FILES['image']);
        $product->setImage($filename);

        $product->add();
        $message = "Product succesvol toegevoegd!";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<h1>Nieuw product toevoegen</h1>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Titel" required><br><br>
    <input type="number" step="0.01" name="price" placeholder="Prijs" required><br><br>
    <input type="text" name="category" placeholder="Categorie"><br><br>
    <input type="file" name="image" required><br><br>
    <button type="submit">Product opslaan</button>
</form>
