<?php
// register_teacher.php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $raw_password = $_POST['password']; // Store the password in plain text temporarily

    // Hash the password using SHA-256
    $hashed_password = hash('sha256', $raw_password);

    $nrc_number = $_POST['nrc_number'];
    $tcz_number = $_POST['tcz_number'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Insert into teachers table
    $sql = "INSERT INTO teachers (firstname, lastname, username, password, nrc_number, tcz_number, contact, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $firstname, $lastname, $username, $hashed_password, $nrc_number, $tcz_number, $contact, $address);

    if ($stmt->execute()) {
        $teacher_id = $stmt->insert_id;

        // Insert into users table with role 'teacher' and inactive status
        $sql_user = "INSERT INTO users (username, password, role, active) VALUES (?, ?, 'teacher', 0)";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("ss", $username, $hashed_password);
        
        if ($stmt_user->execute()) {
            echo "Teacher registered successfully! Wait for admin approval.";
        } else {
            echo "Error: " . $stmt_user->error;
        }
        
        $stmt_user->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Teacher</title>
    <style>
        body {
            background-color: #1F3F49;
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 35%;
            margin: 0 auto;
            padding-top: 50px;
        }
        form {
            background-color: #CED2CC;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 80%;
            padding: 10px;
            
            border-radius: 5px;
            margin-left: 20px;
        }
        .form-group .button-container {
            display: flex;
            justify-content: space-between;
        }
        .form-group button {
            width: 48%;
            padding: 10px;
            background: #D32D41;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color:#1F3F49;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="register_teacher.php" method="POST">
            <h2>Register Teacher</h2>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="nrc_number">NRC Number</label>
                <input type="text" id="nrc_number" name="nrc_number" required>
            </div>
            <div class="form-group">
                <label for="tcz_number">TCZ Number</label>
                <input type="text" id="tcz_number" name="tcz_number" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" id="contact" name="contact" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group button-container">
                <button type="submit">Register</button>
                <button type="button" class="cancel" onclick="window.location.href='index.php';">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
