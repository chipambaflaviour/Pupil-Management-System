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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $class_id = $_POST['class_id'];

    $sql = "UPDATE pupils SET firstname=?, lastname=?, sex=?, birthdate=?, class_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $firstname, $lastname, $sex, $birthdate, $class_id, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pupils WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pupil = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Pupil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #23282D;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #F4F4F4;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #0091D5;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form label {
            margin-bottom: 10px;
            color: #333;
        }
        form input, form select {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        .button-group button, .button-group a {
            padding: 10px;
            flex: 1;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color .3s;
            text-align: center;
            color: #fff;
            text-decoration: none;
        }
        .button-group button {
            background-color: #0091D5;
            border: none;
        }
        .button-group button:hover {
            background-color: #007bb5;
        }
        .button-group a {
            background-color: #EA6A47;
            color: white;
        }
        .button-group a:hover {
            background-color: #d45d40;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Pupil</h2>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($pupil['id']); ?>">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($pupil['firstname']); ?>" required>
            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($pupil['lastname']); ?>" required>
            <label for="sex">Sex:</label>
            <select name="sex" id="sex" required>
                <option value="Male" <?php if ($pupil['sex'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($pupil['sex'] == 'Female') echo 'selected'; ?>>Female</option>
            </select>
            <label for="birthdate">Birthdate:</label>
            <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($pupil['birthdate']); ?>" required>
            <label for="class_id">Class:</label>
            <input type="number" name="class_id" id="class_id" value="<?php echo htmlspecialchars($pupil['class_id']); ?>" required>
            <div class="button-group">
                <button type="submit">Update</button>
                <a href="dashboard.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
