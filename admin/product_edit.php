<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../classes/Product.php';

if (!isset($_GET['id'])) {
    die("Geen product ID");
}

$id = (int)$_GET['id'];
$productData = Product::findById($id);

if (!$productData) {
    die("Product niet gevonden");
}

$message = "";

function safeUploadImageOptional(array $file, string $current): string
{
    if (empty($file['name'])) {
        return $current; // niks geupload, oude behouden
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

        $filename = safeUploadImageOptional($_FILES['image'], $productData['image']);
        $product->setImage($filename);

        $product->update($id);
        $message = "Product succesvol aangepast!";

        $productData = Product::findById($id); // refresh
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<h1>Product bewerken</h1>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($productData['title']) ?>" required><br><br>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($productData['price']) ?>" required><br><br>
    <input type="text" name="category" value="<?= htmlspecialchars($productData['category']) ?>"><br><br>

    <p>Huidige afbeelding:</p>
    <?php if (!empty($productData['image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($productData['image']) ?>" width="150"><br><br>
    <?php endif; ?>

    <input type="file" name="image"><br><br>
    <button type="submit">Opslaan</button>
</form>
