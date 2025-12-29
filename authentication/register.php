<?php
require_once __DIR__ . '/../classes/User.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);

        if ($user->emailExists()) {
            $message = "Dit e-mailadres bestaat al";
        } else {
            $user->save();
            $message = "Account aangemaakt! Je kan nu inloggen.";
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<h2>Registreren</h2>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>
    <button type="submit">Registreren</button>
</form>
