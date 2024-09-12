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

// Fetch pupil's name
$pupil_sql = "SELECT firstname, lastname FROM pupils WHERE id = ?";
$pupil_stmt = $conn->prepare($pupil_sql);
$pupil_stmt->bind_param("i", $pupil_id);
$pupil_stmt->execute();
$pupil_result = $pupil_stmt->get_result();

if ($pupil_result->num_rows == 1) {
    $pupil = $pupil_result->fetch_assoc();
    $pupil_name = htmlspecialchars($pupil['firstname'] . ' ' . $pupil['lastname']);
} else {
    $pupil_name = "Unknown Pupil";
}

// Fetch pupil's results along with term, year, and comment
$sql = "
    SELECT subjects.name AS subject_name, results.score, results.term, results.year, results.comment 
    FROM results 
    INNER JOIN subjects ON results.subject_id = subjects.id 
    WHERE results.pupil_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pupil_id);
$stmt->execute();
$result = $stmt->get_result();

$term = $year = $comment = null;
$results = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['score'] = intval($row['score']); // Remove decimal points
        $results[] = $row;
        if ($term === null) {
            $term = htmlspecialchars($row['term']);
            $year = htmlspecialchars($row['year']);
            $comment = htmlspecialchars($row['comment']);
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
            position: relative;
        }
        h1 {
            text-align: center;
            color: #0073e6;
        }
        .report-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #0073e6;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        .pupil-info, .result-info {
            margin-bottom: 20px;
        }
        .pupil-info p, .result-info p {
            margin: 0;
        }
        .pupil-info p {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #ffffff;
            position: relative;
            z-index: 1;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0073e6;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .watermark {
            position: absolute;
            top: 80px; /* Position below the school name */
            left: 50%;
            transform: translateX(-50%);
            font-size: 2em;
            color: rgba(0, 0, 0, 0.1);
            pointer-events: none;
            z-index: 0;
            user-select: none;
            font-weight: bold;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        .buttons button {
            width: 20%;
            padding: 10px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin: 10px;
            transition: background-color 0.3s;
        }
        .buttons button.cancel {
            background-color: red;
        }
        .buttons button a {
            color: white;
            text-decoration: none;
        }
        @media print {
            .buttons {
                display: none;
            }
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <h1>Chalimbana Secondary School</h1>
        <div class="watermark">Verified Results</div>
        <div class="pupil-info">
            <p>Pupil Name: <?php echo $pupil_name; ?></p>
        </div>

        <?php if ($term && $year && $comment): ?>
        <div class="result-info">
            <p>Term: <?php echo $term; ?></p>
            <p>Year: <?php echo $year; ?></p>
            <p>Comment: <?php echo $comment; ?></p>
        </div>
        <?php endif; ?>

        <?php
        if (!empty($results)) {
            echo '<table>';
            echo '<tr><th>Subject</th><th>Score</th></tr>';
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['subject_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['score']) . '%</td>'; // Format as percentage
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "<p>No results found for this pupil.</p>";
        }
        ?>
        <div class="buttons">
            <button class="cancel"><a href="home.php">Cancel</a></button>
        </div>
    </div>
</body>
</html>
