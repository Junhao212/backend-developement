<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../classes/Product.php';

if (!isset($_GET['id'])) {
    die("Geen product ID meegegeven");
}

$id = (int)$_GET['id'];

Product::delete($id);

// terug naar overzicht
header("Location: products.php");
exit;
