<?php
require_once __DIR__ . '/../config/db.php';

class Product
{
    private string $title;
    private float $price;
    private string $category = '';
    private string $image = '';

    public function setTitle(string $title): void
    {
        if (strlen(trim($title)) < 2) {
            throw new Exception("Titel te kort");
        }
        $this->title = trim($title);
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
        $this->category = trim($category);
    }

    public function setImage(string $image): void
    {
        $this->image = trim($image);
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

    public static function getAll(string $category = '', string $search = ''): array
    {
        $conn = Db::getConnection();

        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if ($category !== '') {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        if ($search !== '') {
            $sql .= " AND title LIKE :q";
            $params[':q'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCategories(): array
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT DISTINCT category FROM products WHERE category <> '' ORDER BY category ASC");
        $stmt->execute();

        return array_map(fn($row) => $row['category'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function findById(int $id): ?array
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);

        return $p ?: null;
    }

    public function update(int $id): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            UPDATE products
            SET title = :title, price = :price, category = :category, image = :image
            WHERE id = :id
        ");

        return $stmt->execute([
            ':title' => $this->title,
            ':price' => $this->price,
            ':category' => $this->category,
            ':image' => $this->image,
            ':id' => $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
