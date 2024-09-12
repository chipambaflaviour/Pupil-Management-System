<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_name = $_POST['class_name'];

    $sql = "INSERT INTO classes (class_name) VALUES ('$class_name')";
    if ($conn->query($sql) === TRUE) {
        header("Location:dashboard.php"); // Redirect to the dashboard after adding class
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, rgb(173, 83, 137), rgb(60, 16, 83));
        }
        .form-container {
            background: get_class_methods;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        button{
            background: linear-gradient(to right, rgb(173, 83, 137), rgb(60, 16, 83));
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Class</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="class_name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="class_name" name="class_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Class</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
