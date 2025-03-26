<?php
// Database configuration
$host = 'localhost';
$dbname = 'monk_register';
$username = 'root';  // Default WAMP MySQL username
$password = '';      // Default WAMP MySQL password (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>