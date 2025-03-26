<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER FORM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }
        .admin-login {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .admin-login a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        label {
            width: 120px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select, textarea {
            flex: 1;
            min-width: 200px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .name-section {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .name-fields {
            flex: 1;
            min-width: 250px;
        }
        .photo-upload {
            flex: 1;
            min-width: 250px;
            margin-top: 15px;
        }
        .photo-upload-container {
            border: 2px dashed #ddd;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .photo-upload-container:hover {
            border-color: #4CAF50;
        }
        .photo-upload input[type="file"] {
            display: none;
        }
        .photo-label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .file-info {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }
        #photoPreview {
            margin-top: 15px;
        }
        #photoPreview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 4px;
            border: 1px solid #eee;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            flex-wrap: wrap;
            gap: 10px;
        }
        button {
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            flex: 1;
            min-width: 120px;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        #searchBtn {
            background-color: #2196F3;
            color: white;
        }
        #searchBtn:hover {
            background-color: #0b7dda;
        }
        button[type="reset"] {
            background-color: #f44336;
            color: white;
        }
        button[type="reset"]:hover {
            background-color: #da190b;
        }

        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                align-items: flex-start;
            }
            label {
                width: 100%;
                margin-bottom: 5px;
            }
            input, select, textarea {
                width: 100%;
                min-width: auto;
            }
            .name-section {
                gap: 0;
            }
            .photo-upload {
                margin-top: 20px;
                margin-left: 0;
                width: 100%;
            }
        }
     
        .logo-container {
            max-width: 200px; /* Adjust based on your logo size */
        }

        .logo {
            max-width: 50%;
            height: auto;
        }

h1 {
    margin-top: 0;
    margin-bottom: 30px;
    clear: both; /* Ensure it appears below the header */
}

    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="images/01.png"  alt="Your Organization Logo" class="logo">
        </div>
        <div class="admin-login">
            <a href="dashboard/login.php"><i class="fas fa-sign-in-alt"></i> Admin Login</a>
        </div>
        
        

        <h1>MONK_REGISTER FORM</h1>
        
        <form id="registrationForm" action="process.php" method="POST" enctype="multipart/form-data">
            <!-- Name and Photo Section -->
            <div class="name-section">
                <div class="name-fields">
                    <div class="form-row">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                    <div class="form-row">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                </div>
                
                <div class="photo-upload">
                    <label class="photo-label"><i class="fas fa-camera"></i> Photo</label>
                    <label class="photo-upload-container" for="photo">
                        Click to upload photo
                        <input type="file" id="photo" name="photo" accept="image/jpeg, image/png">
                    </label>
                    <div class="file-info">Max size: 2MB (JPG or PNG)</div>
                    <div id="photoPreview"></div>
                </div>
            </div>
            
            <!-- Other Fields -->
            <div class="form-row">
                <label for="sex">Sex:</label>
                <select id="sex" name="sex" required>
                    <option value="">Select</option>
                    <option value="ភិក្ខុ">ភិក្ខុ</option>
                    <option value="សាមណេរ">សាមណេរ</option>
                    <option value="និស្សិត">និស្សិត</option>
                </select>
            </div>
            
            <div class="form-row">
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" required>
            </div>

            
            <div class="form-row">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            
            <div class="form-row">
                <label for="doj">Date of Joining:</label>
                <input type="date" id="doj" name="doj" required>
            </div>
            
            <div class="form-row">
                <label for="pidn">PIDN:</label>
                <input type="text" id="pidn" name="pidn">
            </div>
            
            <div class="form-row">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone">
            </div>
            
            <div class="form-row">
                <label for="guarantor">Guarantor:</label>
                <input type="text" id="guarantor" name="guarantor">
            </div>
            
            <div class="form-row" style="grid-column: span 2;">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3"></textarea>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" name="submit">Submit</button>
                <button type="button" id="searchBtn">Search</button>
                <button type="reset">Close</button>
            </div>
        </form>
        
        <!-- Search Modal (unchanged) -->
        <div id="searchModal" class="modal">
            <!-- ... existing search modal code ... -->
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        // Photo preview functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const validTypes = ['image/jpeg', 'image/png'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a JPG or PNG file.');
                    e.target.value = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    alert('File size exceeds 2MB limit.');
                    e.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('photoPreview');
                    preview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>