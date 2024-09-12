<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pupil_id = $_POST['pupil_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registration";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT results.id, subjects.name AS subject_name, results.score, results.term, results.year
            FROM results
            JOIN subjects ON results.subject_id = subjects.id
            WHERE results.pupil_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pupil_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $pass_count = 0;
    $fail_count = 0;
    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
        if ($row['score'] >= 50) {
            $pass_count++;
        } else {
            $fail_count++;
        }
    }

    $stmt->close();
    $conn->close();

    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td>' . $row['subject_name'] . '</td>';
        echo '<td>' . $row['score'] . '</td>';
        echo '<td>' . $row['term'] . '</td>';
        echo '<td>' . $row['year'] . '</td>';
        echo '</tr>';
    }

    $comment = '';
    if ($pass_count >= 5) {
        $comment = 'Excellent results';
    } elseif ($fail_count > 0) {
        $comment = 'Poor results, work extra hard...';
    }

    echo '<script>document.getElementById("comment").value = "' . $comment . '";</script>';
}
?>
