<?php
require_once __DIR__ . '/../config/Db.php';

class Product
{
    private string $title;
    private float $price;
    private string $category;
    private string $image;

    public function setTitle(string $title): void
    {
        if (strlen(trim($title)) < 2) {
            throw new Exception("Titel te kort");
        }
        $this->title = $title;
    }

    public function setPrice(float $price): void
    {
        if ($price <= 0) {
            throw new Exception("Prijs moet groter zijn dan 0");
        }
        $this->price = $price;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function add(): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO products (title, price, category, image)
            VALUES (:title, :price, :category, :image)
        ");

        return $stmt->execute([
            ':title' => $this->title,
            ':price' => $this->price,
            ':category' => $this->category,
            ':image' => $this->image
        ]);
    }
}
