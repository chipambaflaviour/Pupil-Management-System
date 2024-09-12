<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

if (!isset($_GET['pupil_id'])) {
    header("Location: view_result.php");
    exit();
}

$pupil_id = $_GET['pupil_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch results for the specific pupil
$stmt = $conn->prepare("
    SELECT p.firstname AS pupil_name, s.name AS subject_name, r.term, r.year, r.score, r.comment
    FROM results r 
    JOIN pupils p ON r.pupil_id = p.id 
    JOIN subjects s ON r.subject_id = s.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $pupil_id);
$stmt->execute();
$results = $stmt->get_result();

$pupil_name = '';
$pupil_results = [];
$term = '';
$year = '';
$comment = '';

while ($row = $results->fetch_assoc()) {
    if (!$pupil_name) {
        $pupil_name = $row['pupil_name'];
        $term = $row['term'];
        $year = $row['year'];
        $comment = $row['comment'];
    }
    $pupil_results[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Results for <?php echo htmlspecialchars($pupil_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        h1, h2, h3 {
            color: #343a40;
        }
        table {
            margin-bottom: 20px;
        }
        th {
            background-color: #007bff;
            color: black;
        }
        td, th {
            padding: 10px;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Results for <?php echo htmlspecialchars($pupil_name); ?></h1>
        <h2>Term: <?php echo htmlspecialchars($term); ?>, Year: <?php echo htmlspecialchars($year); ?></h2>
        <h3>Teacher's Comment: <?php echo htmlspecialchars($comment); ?></h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pupil_results as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($result['score']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-primary" onclick="window.print();">Print</button>
        <a href="teacherdashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
