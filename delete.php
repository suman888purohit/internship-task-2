<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: index.php');
exit;