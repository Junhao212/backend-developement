<?php
require_once __DIR__ . '/../config/db.php';

class User
{
    private string $email;
    private string $password;

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres");
        }
        $this->email = strtolower(trim($email));
    }

    public function setPassword(string $password): void
    {
        if (strlen($password) < 4) {
            throw new Exception("Wachtwoord moet minstens 4 tekens zijn");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function register(): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $this->email]);

        if ($stmt->fetch()) {
            throw new Exception("E-mailadres bestaat al");
        }

        $stmt = $conn->prepare("
            INSERT INTO users (email, password, role, currency)
            VALUES (:email, :password, 'user', 100)
        ");

        return $stmt->execute([
            ':email' => $this->email,
            ':password' => $this->password
        ]);
    }

    public function login(string $email, string $password): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => strtolower(trim($email))]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['currency'] = $user['currency'];

        return true;
    }
}
