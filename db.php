<?php
$host = 'localhost';
$db   = 'blog';
$user = 'root';
$pass = ''; // default XAMPP MySQL password is empty
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Start session for authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>