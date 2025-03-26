<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize and validate input
    $lastName = sanitizeInput($_POST['last_name']);
    $firstName = sanitizeInput($_POST['first_name']);
    $sex = sanitizeInput($_POST['sex']);
    $position = sanitizeInput($_POST['position']);
    $dob = sanitizeInput($_POST['dob']);
    $doj = sanitizeInput($_POST['doj']);
    $pidn = sanitizeInput($_POST['pidn']);
    $phone = sanitizeInput($_POST['phone']);
    $guarantor = sanitizeInput($_POST['guarantor']);
    $address = sanitizeInput($_POST['address']);
    
    // Validate required fields
    if (empty($lastName) || empty($firstName) || empty($sex) || empty($position) || empty($dob) || empty($doj)) {
        die("Required fields are missing.");
    }
    
    // Validate dates
    if (!validateDate($dob) || !validateDate($doj)) {
        die("Invalid date format. Please use YYYY-MM-DD.");
    }
    
    // Handle photo upload
    $photoName = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadPhoto($_FILES['photo']);
        if (!$uploadResult['success']) {
            die($uploadResult['message']);
        }
        $photoName = $uploadResult['file_name'];
    }
    
    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (last_name, first_name, photo, sex, position, dob, doj, pidn, phone, guarantor, address) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$lastName, $firstName, $photoName, $sex, $position, $dob, $doj, $pidn, $phone, $guarantor, $address]);
        
        // Redirect with success message
        header("Location: index.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>