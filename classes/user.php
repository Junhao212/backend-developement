<?php
require_once __DIR__ . '/../config/Db.php';

class User
{
    private string $email;
    private string $password;

    // EMAIL SETTER
    public function setEmail(string $email): void
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres");
        }

        $this->email = $email;
    }

    // PASSWORD SETTER
    public function setPassword(string $password): void
    {
        if (strlen($password) < 4) {
            throw new Exception("Wachtwoord te kort");
        }

        // hashing
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    // CHECKT OF EMAIL BESTAAT
    public function emailExists(): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // MAAKT NIEUWE USER
    public function save(): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        return $stmt->execute([
            ":email" => $this->email,
            ":password" => $this->password
        ]);
    }

    // LOGIN FUNCTIE
    public function login(string $email, string $password): bool
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false; // email niet gevonden
        }

        // wachtwoord check
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['currency'] = $user['currency'];
            return true;
        }

        return false;
    }
}
