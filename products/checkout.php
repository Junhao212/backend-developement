<?php
session_start();
require_once __DIR__ . '/../classes/order.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../authentication/login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: cart.php");
  exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
  $_SESSION['flash'] = "Winkelmandje is leeg.";
  header("Location: cart.php");
  exit;
}

$result = Order::purchaseCart((int)$_SESSION['user_id'], $cart);

$_SESSION['flash'] = $result['message'];

if ($result['success']) {
  $_SESSION['currency'] = $result['new_currency'];
  $_SESSION['cart'] = [];
}

header("Location: ../index.php");
exit;
