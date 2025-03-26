<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = (int)$_GET['id'];

try {
    // First get the photo name to delete the file
    $stmt = $pdo->prepare("SELECT photo FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    
    // Delete the photo file if it exists
    if ($user && $user['photo']) {
        $photoPath = "../uploads/" . $user['photo'];
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }
    
    // Delete the record from database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    // Redirect back to dashboard with success message
    header("Location: dashboard.php?success=1");
    exit();
    
} catch (PDOException $e) {
    // Handle database errors
    die("Database error: " . $e->getMessage());
}
?>