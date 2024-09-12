<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
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

// Fetch teachers and classes from the database
$teachers_result = $conn->query("SELECT id, firstname, lastname FROM teachers");
$classes_result = $conn->query("SELECT id, class_name FROM classes");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_POST['teacher'];
    $class_ids = isset($_POST['classes']) ? $_POST['classes'] : [];
    $class_ids_str = implode(',', $class_ids);

    // Update the class_ids column in the teachers table
    $sql = "UPDATE teachers SET class_ids='$class_ids_str' WHERE id='$teacher_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Classes assigned successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Classes</title>
    <style>
        /* Internal CSS for styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #1F3F49;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #CED2CC;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: black;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            color: black;
        }
        select, button {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #4CB5F5;
            border-radius: 4px;
        }
        .checkbox-group {
            display: flex;
            flex-direction: column;
        }
        .checkbox-group label {
            margin: 5px 0;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .button-group button {
            flex: 1;
            background-color: #D32D41;
            color: white;
            cursor: pointer;
            margin: 0 5px;
        }
        .button-group button:hover {
            background-color: #23282D;
        }
        .back-button a {
            text-decoration: none;
            color: #CED2CC;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Assign Classes to Teacher</h2>
        <form method="POST">
            <label for="teacher">Select Teacher:</label>
            <select name="teacher" id="teacher" required>
                <option value="">Select a teacher</option>
                <?php while($teacher = $teachers_result->fetch_assoc()): ?>
                    <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['firstname'] . ' ' . $teacher['lastname']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="classes">Select Classes:</label>
            <div class="checkbox-group">
                <?php while($class = $classes_result->fetch_assoc()): ?>
                    <label>
                        <input type="checkbox" name="classes[]" value="<?php echo $class['id']; ?>">
                        <?php echo $class['class_name']; ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <div class="button-group">
                <button type="submit">Assign Classes</button>
                <button type="button" onclick="window.location.href='dashboard.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
