<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
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

// Handle form submission for attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['attendance'])) {
        $attendance = $_POST['attendance'];

        // Insert attendance records into the database
        $stmt = $conn->prepare("INSERT INTO attendance (pupil_id, status, date) VALUES (?, ?, CURDATE()) ON DUPLICATE KEY UPDATE status = VALUES(status)");
        
        foreach ($attendance as $pupil_id => $status) {
            $stmt->bind_param("is", $pupil_id, $status);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        header("Location: teacherdashboard.php?message=Attendance+submitted+successfully");
        exit();
    }
}

// Redirect back to the form if the request method is not POST or required data is missing
header("Location: select_class.php");
exit();
?>
