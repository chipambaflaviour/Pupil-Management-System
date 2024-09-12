<?php
// delete_teacher.php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $teacher_id = $_GET['id'];

    // Delete teacher from the teachers table
    $sql_delete = "DELETE FROM teachers WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $teacher_id);

    if ($stmt_delete->execute()) {
        echo "Teacher deleted successfully.";
    } else {
        echo "Error deleting teacher: " . $stmt_delete->error;
    }

    $stmt_delete->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
