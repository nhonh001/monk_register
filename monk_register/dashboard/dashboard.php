<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle search
$searchTerm = '';
$whereClause = '';
$params = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = sanitizeInput($_GET['search']);
    $whereClause = "WHERE last_name LIKE ? OR first_name LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%"];
}

// Get all users
try {
    $stmt = $pdo->prepare("SELECT * FROM users $whereClause ORDER BY last_name, first_name");
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        // First get the photo name to delete the file
        $stmt = $pdo->prepare("SELECT photo FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        if ($user && $user['photo']) {
            $photoPath = "../uploads/" . $user['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
        
        // Then delete the record
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-form input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-form button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .logout-btn {
            padding: 8px 15px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .action-btns {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        .edit-btn {
            background-color: #2196F3;
            color: white;
        }
        
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        
        .print-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .no-records {
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .photo-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                Operation completed successfully!
            </div>
        <?php endif; ?>
        
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by name..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
            </form>
            <?php if (!empty($searchTerm)): ?>
                <a href="dashboard.php" class="action-btn">Clear</a>
            <?php endif; ?>
        </div>
        
        <?php if (count($users) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Sex</th>
                        <th>Position</th>
                        <th>DOB</th>
                        <th>DOJ</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?php if ($user['photo']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Photo" class="photo-thumbnail">
                                <?php else: ?>
                                    No photo
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['sex']); ?></td>
                            <td><?php echo htmlspecialchars($user['position']); ?></td>
                            <td><?php echo htmlspecialchars($user['dob']); ?></td>
                            <td><?php echo htmlspecialchars($user['doj']); ?></td>
                            <td class="action-btns">
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="action-btn edit-btn">Edit</a>
                                <a href="dashboard.php?delete=<?php echo $user['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                <a href="javascript:void(0);" onclick="printRecord(<?php echo $user['id']; ?>)" class="action-btn print-btn">Print</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-records">
                No records found. <?php if (!empty($searchTerm)) echo "Try a different search term."; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function printRecord(id) {
            window.open(`print.php?id=${id}`, '_blank');
        }
    </script>
</body>
</html>