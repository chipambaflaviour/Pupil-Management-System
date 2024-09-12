<?php
// login.php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch hashed password, role, and activation status based on username
    $sql = "SELECT id, password, role, active FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $stored_password, $role, $active);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
      
        if ($active == 0) {
            $_SESSION['error'] = "Account pending activation. Please wait for admin approval.";
            header("Location: index.php");
            exit();
        }

        if (hash('sha256', $password) === $stored_password) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            if ($role == 'admin') {
                header("Location: dashboard.php");
            } elseif ($role == 'teacher') {
                header("Location: teacherdashboard.php");
            } elseif ($role == 'subject_teacher') {
                header("Location: add_result.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with that username.";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
