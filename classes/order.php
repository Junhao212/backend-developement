<?php
require_once __DIR__ . '/../config/Db.php';

class Order
{
    private int $userId;
    private int $productId;
    private float $price;

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

 
    public function save(): bool
    {
        $conn = Db::getConnection();

        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, product_id, price)
            VALUES (:user_id, :product_id, :price)
        ");

        return $stmt->execute([
            ':user_id' => $this->userId,
            ':product_id' => $this->productId,
            ':price' => $this->price
        ]);
    }

  
    public static function getByUser(int $userId): array
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            SELECT * FROM orders 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC
        ");
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
