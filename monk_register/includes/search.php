<?php
require_once 'db.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (isset($_GET['term']) && !empty($_GET['term'])) {
    $term = '%' . sanitizeInput($_GET['term']) . '%';
    
    try {
        $stmt = $pdo->prepare("SELECT id, last_name, first_name, sex, position FROM users 
                              WHERE last_name LIKE ? OR first_name LIKE ? 
                              ORDER BY last_name, first_name LIMIT 10");
        $stmt->execute([$term, $term]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode([]);
}
?>