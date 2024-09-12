<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'subject_teacher') {
    header("Location: index.php");
    exit();
}


if (!isset($_POST['class_id'])) {
    header("Location: select_class.php");
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
    $results = json_decode($_POST['results'], true);
    $class_id = $_POST['class_id'];

    foreach ($results as $result) {
        $pupil_id = $result['pupil_id'];
        $subject_id = $result['subject_id'];
        $score = $result['score'];
        $term = $result['term'];
        $year = $result['year'];

        $sql = "INSERT INTO results (pupil_id, subject_id, score, term, year, class_id) VALUES ('$pupil_id', '$subject_id', '$score', '$term', '$year', '$class_id')";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
    echo "Results submitted successfully!";
}
?>
