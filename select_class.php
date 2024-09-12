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

// Fetch classes assigned to the teacher
$user_id = $_SESSION['user_id'];
$stmt_classes = $conn->prepare("SELECT classes.id, classes.class_name FROM classes INNER JOIN teachers ON classes.id = teachers.class_id WHERE teachers.id = ?");
$stmt_classes->bind_param("i", $user_id);
$stmt_classes->execute();
$classes_result = $stmt_classes->get_result();

$stmt_classes->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Class</title>
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
            background: #CED2CC;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 600px;
        }
        h2 {
            color: black;
        }
        label, select, input[type="submit"] {
            width: 100%;
            margin-top: 10px;
        }
        select, input[type="submit"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color:  #D32D41;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #1F3F49;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Select Class</h2>
        
        <!-- Class selection form -->
        <form method="POST" action="mark_attendance.php">
            <div class="mb-3">
                <label for="class_id" class="form-label">Select Class</label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">Select a class...</option>
                    <?php while($row = $classes_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['class_name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Select Class</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
