<?php
require_once __DIR__ . '/../config/Db.php';

class Comment
{
    private int $userId;
    private int $productId;
    private string $comment;
    private int $rating;

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function setComment(string $comment): void
    {
        if (strlen(trim($comment)) < 2) {
            throw new Exception("Comment te kort");
        }
        $this->comment = $comment;
    }

    public function setRating(int $rating): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new Exception("Rating moet tussen 1 en 5 zijn");
        }
        $this->rating = $rating;
    }


    public function save(): bool
    {
        $conn = Db::getConnection();

        $stmt = $conn->prepare("
            INSERT INTO comments (user_id, product_id, comment, rating)
            VALUES (:user_id, :product_id, :comment, :rating)
        ");

        return $stmt->execute([
            ':user_id' => $this->userId,
            ':product_id' => $this->productId,
            ':comment' => $this->comment,
            ':rating' => $this->rating
        ]);
    }

 
    public static function getByProduct(int $productId): array
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            SELECT * FROM comments 
            WHERE product_id = :product_id 
            ORDER BY created_at DESC
        ");
        $stmt->bindValue(':product_id', $productId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
