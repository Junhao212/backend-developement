<?php
session_start();

// Als gebruiker al is ingelogd, meteen doorsturen naar index.php
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Variabele voor foutmelding
$error = "";

// Controleren of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Controle van inloggegevens
    if ($email === "junhao@shop.com" && $password === "12345isnotsecure") {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Ongeldige e-mail of wachtwoord!";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 50px; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 300px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input { display: block; width: 100%; padding: 10px; margin-bottom: 10px; }
        button { width: 100%; padding: 10px; background: #007BFF; border: none; color: #fff; border-radius: 4px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Wachtwoord" required>
        <button type="submit">Inloggen</button>
    </form>
</body>
</html>
