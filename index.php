<?php
session_start();

// Controleren of de gebruiker is ingelogd
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Welkom</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; color: #007BFF; }
    </style>
</head>
<body>
    <h1>Welkom op de shop, je bent ingelogd!</h1>
    <p><a href="logout.php">Uitloggen</a></p>
</body>
</html>
