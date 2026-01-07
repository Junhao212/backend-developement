<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../classes/Comment.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Je moet ingelogd zijn.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Ongeldige request.'
    ]);
    exit;
}

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$commentText = trim($_POST['comment'] ?? '');
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

if ($productId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Ongeldig product id.'
    ]);
    exit;
}

try {
    $comment = new Comment();
    $comment->setUserId((int)$_SESSION['user_id']);
    $comment->setProductId($productId);
    $comment->setComment($commentText);
    $comment->setRating($rating);

    $comment->add();

    echo json_encode([
        'success' => true,
        'message' => 'Comment toegevoegd!'
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
