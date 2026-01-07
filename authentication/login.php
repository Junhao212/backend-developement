<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

$message = "";

// ✅ Next URL support (voor "wachtwoord wijzigen" flow)
$next = $_GET['next'] ?? '';
if (!is_string($next)) {
    $next = '';
}

// ✅ Veiligheid: laat enkel interne/relatieve paden toe (geen externe redirects)
if (
    $next === '' ||
    str_contains($next, '://') ||
    str_contains($next, "\n") ||
    str_contains($next, "\r")
) {
    $next = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['currency'] = (int)$user['currency'];

            // ✅ Als gebruiker via "wachtwoord wijzigen" kwam, ga daarnaar
            if ($next !== '' && str_starts_with($next, '../account/')) {
                header("Location: " . $next);
                exit;
            }

            // ✅ Jouw bestaande flow behouden
            if ($_SESSION['role'] === 'admin') {
                header("Location: ../admin/products.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            $message = "Ongeldige logingegevens";
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login | JW Shop</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>

<h2>Login</h2>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>
    <button type="submit">Inloggen</button>
</form>

<p>Geen account? <a href="register.php">Register</a></p>

<!-- ✅ Wachtwoord wijzigen: werkt altijd (eerst login, daarna naar formulier) -->
<p>
    Wachtwoord wijzigen?
    <a href="login.php?next=../account/change_password.php">Klik hier</a>
</p>

</body>
</html>
