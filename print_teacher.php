<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitize($conn, $input) {
    return htmlspecialchars(strip_tags(mysqli_real_escape_string($conn, $input)));
}

// Fetch teacher details
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $teacher_id = sanitize($conn, $_GET['id']);

    // Query to fetch teacher details
    $sql = "SELECT * FROM teachers WHERE id = $teacher_id";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $teacher = $result->fetch_assoc();
        } else {
            echo "Teacher not found.";
            exit;
        }
    } else {
        echo "Error executing query: " . $conn->error;
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Teacher Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teacher Record</h1>
        <table>
            <tr>
                <th>ID</th>
                <td><?php echo $teacher['id']; ?></td>
            </tr>
            <tr>
                <th>First Name</th>
                <td><?php echo $teacher['firstname']; ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo $teacher['lastname']; ?></td>
            </tr>
            <!-- Add more fields as needed -->
        </table>

        <div class="print-button">
            <button onclick="window.print()">Print</button>
        </div>
    </div>
</body>
</html>
