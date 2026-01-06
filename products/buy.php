<?php
session_start();

require_once __DIR__ . '/../classes/Order.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
if ($productId <= 0) {
    header("Location: ../index.php");
    exit;
}

$result = Order::purchase((int)$_SESSION['user_id'], $productId);

$_SESSION['flash'] = $result['message'];
header("Location: product.php?id=" . $productId);
exit;
