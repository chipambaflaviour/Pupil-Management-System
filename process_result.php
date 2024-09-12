<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $pupil_id = $_POST['pupil_id'];
    $term = $_POST['term'];
    $year = $_POST['year'];
    $comment = $_POST['comment'];
    $subject_ids = $_POST['subject_ids'];
    $scores = $_POST['scores'];

    $conn->begin_transaction();

    try {
        foreach ($subject_ids as $index => $subject_id) {
            $score = $scores[$index];
            $stmt = $conn->prepare("INSERT INTO results (pupil_id, subject_id, score, term, year, comment) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisdss", $pupil_id, $subject_id, $score, $term, $year, $comment);
            $stmt->execute();
        }

        $conn->commit();
        echo "Results added successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to add results: " . $e->getMessage();
    }

    $stmt->close();
}

$conn->close();
?>
