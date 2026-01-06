<?php
require_once __DIR__ . '/../config/db.php';

class Order
{
    public static function purchase(int $userId, int $productId): array
    {
        $conn = Db::getConnection();

        // 1) product ophalen
        $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = :id");
        $stmt->execute([':id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return ['success' => false, 'message' => 'Product niet gevonden'];
        }

        $price = (int) round((float)$product['price']); // coins als int

        // 2) user coins ophalen
        $stmt = $conn->prepare("SELECT currency FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'User niet gevonden'];
        }

        $coins = (int)$user['currency'];

        if ($coins < $price) {
            return ['success' => false, 'message' => 'Niet genoeg coins'];
        }

        // 3) transaction: coins verminderen + order toevoegen
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("UPDATE users SET currency = currency - :price WHERE id = :id");
            $stmt->execute([
                ':price' => $price,
                ':id' => $userId
            ]);

            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, product_id, price)
                VALUES (:user_id, :product_id, :price)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':product_id' => $productId,
                ':price' => $price
            ]);

            $conn->commit();

            return ['success' => true, 'message' => 'Aankoop gelukt!'];
        } catch (Exception $e) {
            $conn->rollBack();
            return ['success' => false, 'message' => 'Order fout: ' . $e->getMessage()];
        }
    }
}
