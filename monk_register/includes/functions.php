<?php
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function uploadPhoto($file) {
    $targetDir = "uploads/";
    $fileName = uniqid() . '_' . basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['success' => false, 'message' => 'File is not an image.'];
    }
    
    // Check file size (2MB max)
    if ($file["size"] > 2000000) {
        return ['success' => false, 'message' => 'File is too large (max 2MB).'];
    }
    
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        return ['success' => false, 'message' => 'Only JPG, JPEG, PNG files are allowed.'];
    }
    
    // Try to upload file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ['success' => true, 'file_name' => $fileName];
    } else {
        return ['success' => false, 'message' => 'Error uploading file.'];
    }
}
?>