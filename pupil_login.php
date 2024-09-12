<?php
session_start();
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "registration";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $pupil_id = $_POST['pupil_id'];

    $sql = "SELECT * FROM pupils WHERE id = ? AND firstname = ? AND lastname = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $pupil_id, $firstname, $lastname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['pupil_id'] = $pupil_id;
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid login credentials";
        header("Location: pupil.php?error=" . urlencode($error));
        exit();
    }
}
?>
