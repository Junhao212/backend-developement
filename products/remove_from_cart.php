<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../authentication/login.php");
  exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  header("Location: cart.php");
  exit;
}

if (isset($_SESSION['cart'][$id])) {
  $_SESSION['cart'][$id]--;
  if ($_SESSION['cart'][$id] <= 0) unset($_SESSION['cart'][$id]);
}

header("Location: cart.php");
exit;
