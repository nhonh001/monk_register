<?php
require_once '../includes/db.php';

// Reset password to 'angtaminh2025@'
$newHash = password_hash('nhonhda123', PASSWORD_BCRYPT);
$stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'angtaminh'");
$stmt->execute([$newHash]);

echo "Password reset complete! Try logging in again.";