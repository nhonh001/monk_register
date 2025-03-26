<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = (int)$_GET['id'];

// Get user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header("Location: dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
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
    $photoName = $user['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadPhoto($_FILES['photo']);
        if (!$uploadResult['success']) {
            die($uploadResult['message']);
        }
        
        // Delete old photo if it exists
        if ($photoName) {
            $oldPhotoPath = "../uploads/" . $photoName;
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
        }
        
        $photoName = $uploadResult['file_name'];
    }
    
    // Update database
    try {
        $stmt = $pdo->prepare("UPDATE users SET 
                              last_name = ?, first_name = ?, photo = ?, sex = ?, position = ?, 
                              dob = ?, doj = ?, pidn = ?, phone = ?, guarantor = ?, address = ? 
                              WHERE id = ?");
        $stmt->execute([$lastName, $firstName, $photoName, $sex, $position, $dob, $doj, 
                        $pidn, $phone, $guarantor, $address, $id]);
        
        header("Location: dashboard.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .edit-form {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .photo-preview {
            margin-top: 10px;
        }
        
        .photo-preview img {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .back-btn {
            padding: 10px 15px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .update-btn {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Record</h1>
        
        <form class="edit-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="sex">Sex:</label>
                <select id="sex" name="sex" required>
                    <option value="ភិក្ខុ" <?php echo $user['sex'] === 'ភិក្ខុ' ? 'selected' : ''; ?>>ភិក្ខុ</option>
                    <option value="សាមណេរ" <?php echo $user['sex'] === 'សាមណេរ' ? 'selected' : ''; ?>>សាមណេរ</option>
                    <option value="និស្សិត" <?php echo $user['sex'] === 'និស្សិត' ? 'selected' : ''; ?>>និស្សិត</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="dob">DOB:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="doj">DOJ:</label>
                <input type="date" id="doj" name="doj" value="<?php echo htmlspecialchars($user['doj']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="pidn">PIDN:</label>
                <input type="text" id="pidn" name="pidn" value="<?php echo htmlspecialchars($user['pidn']); ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            
            <div class="form-group">
                <label for="guarantor">Guarantor:</label>
                <input type="text" id="guarantor" name="guarantor" value="<?php echo htmlspecialchars($user['guarantor']); ?>">
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="photo">Upload Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg, image/png">
                <div class="file-info">Max size: 2MB | Formats: JPG, PNG</div>
                
                <?php if ($user['photo']): ?>
                    <div class="photo-preview">
                        <p>Current Photo:</p>
                        <img src="../uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Current Photo">
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
                <button type="submit" name="update" class="update-btn">Update Record</button>
            </div>
        </form>
    </div>
    
    <script>
        // Photo preview functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const preview = document.querySelector('.photo-preview');
                
                if (!preview) {
                    const photoPreview = document.createElement('div');
                    photoPreview.className = 'photo-preview';
                    photoPreview.innerHTML = '<p>New Photo Preview:</p><img src="" alt="Preview">';
                    e.target.parentNode.appendChild(photoPreview);
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.querySelector('.photo-preview img');
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>