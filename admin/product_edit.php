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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product = new Product();
        $product->setTitle($_POST['title']);
        $product->setPrice((float)$_POST['price']);
        $product->setCategory($_POST['category']);

      
        if (!empty($_FILES['image']['name'])) {
            $filename = time() . "_" . $_FILES['image']['name'];
            $destination = "../uploads/" . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $destination);
            $product->setImage($filename);
        } else {
           
            $product->setImage($productData['image']);
        }

        $product->update($id);
        $message = "Product succesvol aangepast!";
        $productData = Product::findById($id);
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
    <input type="number" step="0.01" name="price" value="<?= $productData['price'] ?>" required><br><br>
    <input type="text" name="category" value="<?= htmlspecialchars($productData['category']) ?>"><br><br>

    <p>Huidige afbeelding:</p>
    <img src="../uploads/<?= htmlspecialchars($productData['image']) ?>" width="150"><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit">Opslaan</button>
</form>
