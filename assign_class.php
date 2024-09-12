<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Class</title>
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
            background-color: #CED2CC;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        h2 {
            color: #333;
        }
        label, select {
            width: 100%;
            margin-top: 10px;
            color: black;
        }
        select {
            padding: 10px;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #D32D41;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #C62828;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .button-container button {
            background-color: #D32D41;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            flex: 1;
            margin: 0 5px;
            text-align: center;
        }
        .button-container button a {
            text-decoration: none;
            color: white;
        }
        .button-container button:hover {
            background-color: #C62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $teacher_id = $_GET['id'];

            // Fetch teacher details
            $sql_teacher = "SELECT id, firstname, lastname FROM teachers WHERE id = ?";
            $stmt_teacher = $conn->prepare($sql_teacher);
            $stmt_teacher->bind_param("i", $teacher_id);
            $stmt_teacher->execute();
            $stmt_teacher->store_result();
            $stmt_teacher->bind_result($id, $firstname, $lastname);

            if ($stmt_teacher->num_rows > 0) {
                $stmt_teacher->fetch();
                echo "<h2>Assign Class to Teacher</h2>";
                echo "<p>Teacher: $firstname $lastname</p>";
                echo "<form method='post' action='assign_class_process.php'>";
                echo "<input type='hidden' name='teacher_id' value='$id'>";
                echo "<label>Select Class:</label><br>";
                echo "<select name='class_id'>";
                
                // Fetch classes from the database
                $sql_classes = "SELECT id, class_name FROM classes";
                $result_classes = $conn->query($sql_classes);
                
                if ($result_classes->num_rows > 0) {
                    while ($row = $result_classes->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['class_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No classes found</option>";
                }
                
                echo "</select><br><br>";
                echo "<input type='submit' value='Assign Class'>";
                echo "</form>";
            } else {
                echo "Teacher not found.";
            }

            $stmt_teacher->close();
        } else {
            echo "Invalid request.";
        }

        $conn->close();
        ?>
        <div class="button-container">
            <button><a href="dashboard.php">Cancel</a></button>
            <!-- Add any other button here if needed, for example:
            <button><a href="another_page.php">Another Button</a></button>
            -->
        </div>
    </div>
</body>
</html>
