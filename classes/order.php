<?php
require_once __DIR__ . '/../config/db.php';

class Order
{
    public static function purchaseCart(int $userId, array $cart): array
    {
        $conn = Db::getConnection();

        $stmt = $conn->prepare("SELECT currency FROM users WHERE id = :id");
        $stmt->execute([":id" => $userId]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$u) return ["success" => false, "message" => "User niet gevonden"];

        $coins = (int)$u['currency'];

        $total = 0.0;
        $lines = [];

        foreach ($cart as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            if ($pid <= 0 || $qty <= 0) continue;

            $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = :id");
            $stmt->execute([":id" => $pid]);
            $p = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$p) continue;

            $lines[] = ["product_id" => $pid, "qty" => $qty, "price" => (float)$p['price']];
            $total += (float)$p['price'] * $qty;
        }

        if ($total <= 0 || empty($lines)) {
            return ["success" => false, "message" => "Winkelmandje is leeg/ongeldig"];
        }

        $costCoins = (int)round($total);
        if ($coins < $costCoins) {
            return ["success" => false, "message" => "Niet genoeg coins"];
        }

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("UPDATE users SET currency = currency - :t WHERE id = :id");
            $stmt->execute([":t" => $costCoins, ":id" => $userId]);

            $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, price) VALUES (:u, :p, :price)");
            foreach ($lines as $l) {
                for ($i = 0; $i < $l['qty']; $i++) {
                    $stmt->execute([
                        ":u" => $userId,
                        ":p" => $l['product_id'],
                        ":price" => $l['price']
                    ]);
                }
            }

            $conn->commit();

            $stmt = $conn->prepare("SELECT currency FROM users WHERE id = :id");
            $stmt->execute([":id" => $userId]);
            $newCoins = (int)($stmt->fetch(PDO::FETCH_ASSOC)['currency'] ?? 0);

            return ["success" => true, "message" => "Aankoop gelukt!", "new_currency" => $newCoins];
        } catch (Exception $e) {
            $conn->rollBack();
            return ["success" => false, "message" => "Order fout: " . $e->getMessage()];
        }
    }

    public static function getByUserId(int $userId): array
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            SELECT o.*, p.title
            FROM orders o
            JOIN products p ON p.id = o.product_id
            WHERE o.user_id = :uid
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([":uid" => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
