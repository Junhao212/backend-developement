<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();

    if ($user->login($email, $password)) {

        // redirect per rol
        if ($_SESSION['role'] === 'admin') {
            header("Location: ../admin/products.php");
            exit;
        } else {
            header("Location: ../index.php");
            exit;
        }
    } else {
        $message = "Ongeldige logingegevens";
    }
}
?>

<form method="POST">
    <h2>Login</h2>

    <?php if($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>
    <button type="submit">Inloggen</button>
</form>
