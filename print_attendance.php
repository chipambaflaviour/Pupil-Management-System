<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teacher's class ID
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT class_id FROM teachers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($class_id);
$stmt->fetch();
$stmt->close();

// Fetch attendance data based on the teacher's class ID
$sql = "SELECT a.id, p.firstname, p.lastname, a.date, a.status
        FROM attendance a
        JOIN pupils p ON a.pupil_id = p.id
        WHERE p.class_id = ?
        ORDER BY a.date, p.lastname, p.firstname"; // Order by date, then last name, then first name

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all attendance records into an array grouped by date
$attendance_records = [];
while ($row = $result->fetch_assoc()) {
    $date = $row['date'];
    if (!isset($attendance_records[$date])) {
        $attendance_records[$date] = [];
    }
    $attendance_records[$date][] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Attendance Report</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your main CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            color: #555;
            margin-top: 30px;
        }
        h3 {
            color: #666;
            margin-top: 20px;
            text-decoration: underline;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .status-present {
            color: green;
        }
        .status-absent {
            color: red;
        }
        .status-late {
            color: orange;
        }
    </style>
    <script>
        // JavaScript function to trigger printing
        function printAttendance() {
            window.print();
        }
    </script>
</head>
<body onload="printAttendance()">
    <div class="container">
    <h1> Chalimbana secondary Attendance Report</h1>
        <h2>Class <?php echo $class_id; ?> Attendance</h2>

        <?php foreach ($attendance_records as $date => $records): ?>
            <h3><?php echo date("F j, Y", strtotime($date)); ?></h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $index => $record): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($record['lastname'] . ', ' . $record['firstname']); ?></td>
                        <td class="<?php echo 'status-' . strtolower($record['status']); ?>"><?php echo ucfirst($record['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</body>
</html>
