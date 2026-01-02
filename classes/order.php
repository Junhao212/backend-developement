<?php
require_once __DIR__ . '/../config/Db.php';

class Order
{
    public static function purchase(int $userId, int $productId): array
    {
        $conn = Db::getConnection();

        // product ophalen
        $stmt = $conn->prepare("SELECT id, title, price FROM products WHERE id = :id");
        $stmt->bindValue(":id", $productId, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return ['success' => false, 'message' => 'Product niet gevonden.'];
        }

        // user ophalen
        $stmt = $conn->prepare("SELECT id, currency FROM users WHERE id = :id");
        $stmt->bindValue(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'User niet gevonden.'];
        }

        $priceCoins = (int)round((float)$product['price']);
        $currentCoins = (int)$user['currency'];

        if ($currentCoins < $priceCoins) {
            return ['success' => false, 'message' => 'Niet genoeg currency.'];
        }

        // transactie: coins verminderen + order opslaan
        $conn->beginTransaction();
        try {
            $newCoins = $currentCoins - $priceCoins;

            $stmt = $conn->prepare("UPDATE users SET currency = :c WHERE id = :id");
            $stmt->execute([':c' => $newCoins, ':id' => $userId]);

            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, product_id, price)
                VALUES (:user_id, :product_id, :price)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':product_id' => $productId,
                ':price' => $priceCoins
            ]);

            $conn->commit();

            return [
                'success' => true,
                'message' => 'Aankoop gelukt!',
                'new_currency' => $newCoins,
                'product_title' => $product['title'],
                'price' => $priceCoins
            ];
        } catch (Exception $e) {
            $conn->rollBack();
            return ['success' => false, 'message' => 'Fout bij aankoop.'];
        }
    }
}
