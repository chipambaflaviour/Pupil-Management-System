<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Check if class_id is set
if (!isset($_POST['class_id'])) {
    header("Location: select_class.php");
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

// Fetch class details
$class_id = $_POST['class_id'];
$stmt_class = $conn->prepare("SELECT * FROM classes WHERE id = ?");
$stmt_class->bind_param("i", $class_id);
$stmt_class->execute();
$class_result = $stmt_class->get_result();

if ($class_result->num_rows > 0) {
    $class_data = $class_result->fetch_assoc();

    // Fetch pupils of the selected class
    $stmt_pupils = $conn->prepare("SELECT id, firstname, lastname FROM pupils WHERE class_id = ?");
    $stmt_pupils->bind_param("i", $class_id);
    $stmt_pupils->execute();
    $pupils_result = $stmt_pupils->get_result();
} else {
    $class_data = null;
    $pupils_result = null;
}

$stmt_class->close();
$stmt_pupils->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1F3F49;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 800px;
        }
        h3 {
            color: #333;
        }
        .status-buttons label {
            margin: 0 5px;
        }
        .btn-present {
            background-color: #A5D8DD;
        }
        .btn-absent {
            background-color: #EA6A47;
        }
        .btn-sick {
            background-color: #1C4E80;
        }
        .btn-submit {
            background-color: #1F3F49;
            color: #fff;
        }
        .btn-submit:hover{
            background-color: green;
            color: white;
        }
        .btn-cancel {
            background-color: #D32D41;
            color: #fff;
        }
        table {
            width: 100%;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
        }
        .btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php if (isset($class_data) && $class_data): ?>
            <h3>Mark Attendance for Class: <?php echo htmlspecialchars($class_data['class_name']); ?></h3>
            <form method="POST" action="process_attendance.php">
                <input type="hidden" name="class_id" value="<?php echo $class_data['id']; ?>">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Pupil ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $pupils_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['firstname']; ?></td>
                                <td><?php echo $row['lastname']; ?></td>
                                <td>
                                    <div class="status-buttons">
                                        <input type="radio" id="present-<?php echo $row['id']; ?>" name="attendance[<?php echo $row['id']; ?>]" value="present" checked>
                                        <label for="present-<?php echo $row['id']; ?>" class="btn btn-present">Present</label>
                                        
                                        <input type="radio" id="absent-<?php echo $row['id']; ?>" name="attendance[<?php echo $row['id']; ?>]" value="absent">
                                        <label for="absent-<?php echo $row['id']; ?>" class="btn btn-absent">Absent</label>
                                        
                                        <input type="radio" id="sick-<?php echo $row['id']; ?>" name="attendance[<?php echo $row['id']; ?>]" value="sick">
                                        <label for="sick-<?php echo $row['id']; ?>" class="btn btn-sick">Sick</label>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-submit">Submit Attendance</button>
                <a href="teacherdashboard.php" class="btn btn-cancel">Cancel</a>
            </form>
        <?php else: ?>
            <h3>No class data found.</h3>
            <a href="select_class.php" class="btn btn-cancel">Go Back</a>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
