<?php
// update_teacher_process.php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $nrc_number = $_POST['nrc_number'];
    $tcz_number = $_POST['tcz_number'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Update teacher's information in the teachers table
    $sql_update = "UPDATE teachers SET firstname = ?, lastname = ?, nrc_number = ?, tcz_number = ?, contact = ?, address = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssi", $firstname, $lastname, $nrc_number, $tcz_number, $contact, $address, $teacher_id);

    if ($stmt_update->execute()) {
        echo "Teacher information updated successfully.";
    } else {
        echo "Error updating teacher information: " . $stmt_update->error;
    }

    $stmt_update->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
