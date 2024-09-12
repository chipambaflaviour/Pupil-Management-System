<?php
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate inputs (you might want to add more validation as needed)
    $teacher_id = $_POST['teacher'];
    $role = $_POST['role'];

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registration";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update user's role in the database
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("si", $role, $teacher_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Role assigned successfully
        $_SESSION['success_message'] = "Role assigned successfully.";
    } else {
        // Error assigning role
        $_SESSION['error_message'] = "Error assigning role: " . $conn->error;
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Redirect back to assign_role.php after processing
    header("Location: assign_role.php");
    exit();
} else {
    // If the form was not submitted, redirect to assign_role.php
    header("Location: assign_role.php");
    exit();
}
?>
