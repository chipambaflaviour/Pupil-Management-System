<?php
session_start();
if (!isset($_SESSION['pupil_id'])) {
    header("Location: pupil_login.php");
    exit();
}

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "registration";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pupil_id = $_SESSION['pupil_id'];

// Fetch pupil's name and class
$pupil_sql = "
    SELECT pupils.firstname, pupils.lastname, classes.class_name 
    FROM pupils 
    JOIN classes ON pupils.class_id = classes.id 
    WHERE pupils.id = ?";
$pupil_stmt = $conn->prepare($pupil_sql);
$pupil_stmt->bind_param("i", $pupil_id);
$pupil_stmt->execute();
$pupil_result = $pupil_stmt->get_result();

if ($pupil_result->num_rows == 1) {
    $pupil = $pupil_result->fetch_assoc();
    $pupil_name = htmlspecialchars($pupil['firstname'] . ' ' . $pupil['lastname']);
    $class_name = htmlspecialchars($pupil['class_name']);
} else {
    $pupil_name = "Unknown Pupil";
    $class_name = "Unknown Class";
}

// Fetch pupil's subjects
$sql = "
    SELECT subjects.name AS subject_name 
    FROM results 
    INNER JOIN subjects ON results.subject_id = subjects.id 
    WHERE results.pupil_id = ?
    GROUP BY subjects.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pupil_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pupil Subjects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }
        button {
            display: block;
            margin: 20px auto;
            width: 200px;
            padding: 10px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
        }
        button:hover {
            background-color: #0056b3;
        }
        h1 {
            text-align: center;
            color: #0073e6;
        }
        .pupil-info, .subject-info {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 60%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #ffffff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #0073e6;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Chalimbana Secondary School</h1>
    <div class="pupil-info">
        <p><strong>Pupil Name:</strong> <?php echo $pupil_name; ?></p>
        <p><strong>Class:</strong> <?php echo $class_name; ?></p>
    </div>

    <?php
    if (!empty($subjects)) {
        echo '<table>';
        echo '<tr><th>Subject</th></tr>';
        foreach ($subjects as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['subject_name']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "<p>No subjects found for this pupil.</p>";
    }
    ?>
    <button><a href="home.php">Cancel</a></button>
</body>
</html>
