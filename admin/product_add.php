<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../classes/Product.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product = new Product();
        $product->setTitle($_POST['title']);
        $product->setPrice((float)$_POST['price']);
        $product->setCategory($_POST['category']);

        // afbeelding upload
        if (!empty($_FILES['image']['name'])) {
            $filename = time() . "_" . $_FILES['image']['name'];
            $destination = "../uploads/" . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $destination);
            $product->setImage($filename);
        } else {
            throw new Exception("Afbeelding verplicht");
        }

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
