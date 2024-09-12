<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pupil_id = $_POST['pupil'];
    $comment = $_POST['comment'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registration";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the pupil already has a result
    $stmt = $conn->prepare("SELECT id FROM results WHERE pupil_id = ?");
    $stmt->bind_param("i", $pupil_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If the pupil already has a result, update the existing record
        $stmt->close();
        $stmt = $conn->prepare("UPDATE results SET comment = ? WHERE pupil_id = ?");
        $stmt->bind_param("si", $comment, $pupil_id);
    } else {
        // If the pupil does not have a result, insert a new record
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO results (pupil_id, comment) VALUES (?, ?)");
        $stmt->bind_param("is", $pupil_id, $comment);
    }

    if ($stmt->execute()) {
        echo "Result updated successfully!";
    } else {
        echo "Error updating result: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: update_pupil_results.php");
    exit();
}
?>
