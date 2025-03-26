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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        
        .photo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .photo-container img {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #ddd;
        }
        
        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .detail-item {
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: bold;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                padding: 0;
            }
            
            .print-container {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="header">
            <h1>Registration Details</h1>
        </div>
        
        <div class="photo-container">
            <?php if ($user['photo']): ?>
                <img src="../uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Photo">
            <?php else: ?>
                <p>No photo available</p>
            <?php endif; ?>
        </div>
        
        <div class="details">
            <div class="detail-item">
                <span class="detail-label">ID:</span>
                <span><?php echo htmlspecialchars($user['id']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Last Name:</span>
                <span><?php echo htmlspecialchars($user['last_name']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">First Name:</span>
                <span><?php echo htmlspecialchars($user['first_name']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Sex:</span>
                <span><?php echo htmlspecialchars($user['sex']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Position:</span>
                <span><?php echo htmlspecialchars($user['position']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Date of Birth:</span>
                <span><?php echo htmlspecialchars($user['dob']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Date of Joining:</span>
                <span><?php echo htmlspecialchars($user['doj']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">PIDN:</span>
                <span><?php echo htmlspecialchars($user['pidn']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Phone:</span>
                <span><?php echo htmlspecialchars($user['phone']); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Guarantor:</span>
                <span><?php echo htmlspecialchars($user['guarantor']); ?></span>
            </div>
            
            <div class="detail-item" style="grid-column: span 2;">
                <span class="detail-label">Address:</span>
                <span><?php echo nl2br(htmlspecialchars($user['address'])); ?></span>
            </div>
        </div>
        
        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Close</button>
        </div>
    </div>
</body>
</html>