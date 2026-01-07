<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../authentication/login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../index.php");
  exit;
}

$id = (int)($_POST['product_id'] ?? 0);
if ($id <= 0) {
  header("Location: ../index.php");
  exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;

$_SESSION['flash'] = "Product toegevoegd aan winkelmandje.";
header("Location: ../index.php");
exit;
