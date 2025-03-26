<?php
require_once 'includes/db.php';

$username = 'angtaminh';
$password = 'angtaminh2025@';

$stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ? AND password = ?");
$stmt->execute([$username, $password]);
$count = $stmt->fetchColumn();

echo "Matching users found: $count";
?>