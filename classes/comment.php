<?php
require_once __DIR__ . '/../config/db.php';

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
        $this->comment = trim($comment);
    }

    public function setRating(int $rating): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new Exception("Rating moet tussen 1 en 5 zijn");
        }
        $this->rating = $rating;
    }

    public function add(): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO comments (user_id, product_id, comment, rating)
            VALUES (:uid, :pid, :comment, :rating)
        ");
        return $stmt->execute([
            ':uid' => $this->userId,
            ':pid' => $this->productId,
            ':comment' => $this->comment,
            ':rating' => $this->rating
        ]);
    }

    public static function getByProduct(int $productId): array
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            SELECT c.*, u.email
            FROM comments c
            JOIN users u ON u.id = c.user_id
            WHERE c.product_id = :pid
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([":pid" => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
