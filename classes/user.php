<?php
require_once __DIR__ . '/../config/db.php';

class User
{
    private string $email;
    private string $password;

    public function setEmail(string $email): void
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres");
        }
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        if (strlen($password) < 4) {
            throw new Exception("Wachtwoord te kort");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function emailExists(): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([":email" => $this->email]);
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save(string $role = 'user'): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO users (email, password, role, currency)
            VALUES (:email, :password, :role, 100)
        ");
        return $stmt->execute([
            ":email" => $this->email,
            ":password" => $this->password,
            ":role" => $role
        ]);
    }

    public function login(string $email, string $password): ?array
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([":email" => trim($email)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return null;
        if (!password_verify($password, $user['password'])) return null;

        return $user;
    }

    public static function changePassword(int $userId, string $oldPassword, string $newPassword): void
    {
        if (strlen($newPassword) < 4) {
            throw new Exception("Nieuw wachtwoord te kort");
        }

        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute([":id" => $userId]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$u || !password_verify($oldPassword, $u['password'])) {
            throw new Exception("Oud wachtwoord klopt niet");
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = :p WHERE id = :id");
        $stmt->execute([":p" => $newHash, ":id" => $userId]);
    }
}
