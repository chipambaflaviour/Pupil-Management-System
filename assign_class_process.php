<?php
// assign_class_process.php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'], $_POST['class_id'])) {
    $teacher_id = $_POST['teacher_id'];
    $class_id = $_POST['class_id'];

    // Update teacher's class_id in the teachers table
    $sql_update_teacher = "UPDATE teachers SET class_id = ? WHERE id = ?";
    $stmt_update_teacher = $conn->prepare($sql_update_teacher);
    $stmt_update_teacher->bind_param("ii", $class_id, $teacher_id);

    if ($stmt_update_teacher->execute()) {
        // Update teacher's account status to active in the users table
        $sql_update_user = "UPDATE users SET active = 1 WHERE id = ?";
        $stmt_update_user = $conn->prepare($sql_update_user);
        $stmt_update_user->bind_param("i", $teacher_id);

        if ($stmt_update_user->execute()) {
            header("Location: view_registeredteacher.php");
            exit(); // Stop further execution after the redirect
        }
         else {
            echo "Error updating user status: " . $stmt_update_user->error;
        }

        $stmt_update_user->close();
    } else {
        echo "Error assigning class: " . $stmt_update_teacher->error;
    }

    $stmt_update_teacher->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
