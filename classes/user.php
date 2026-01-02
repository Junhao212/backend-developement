<?php
require_once __DIR__ . '/../config/Db.php';

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
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
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

    public function login(string $email, string $password): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['currency'] = (int)$user['currency'];
            return true;
        }

        return false;
    }

    public static function existsByEmail(string $email): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
