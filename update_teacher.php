<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Teacher Information</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1F3F49;
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 50%;
            margin: 0 auto;
            padding-top: 50px;
        }
        form {
            background-color: #CED2CC;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: black;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group button, .form-group a.btn {
            width: 100%;
            padding: 10px;
            background-color: #D32D41;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
        }
        .form-group button:hover, .form-group a.btn:hover {
            background-color: #C62828;
        }
        h2 {
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
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

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $teacher_id = $_GET['id'];

            // Fetch teacher details
            $sql_teacher = "SELECT id, firstname, lastname, nrc_number, tcz_number, contact, address FROM teachers WHERE id = ?";
            $stmt_teacher = $conn->prepare($sql_teacher);
            $stmt_teacher->bind_param("i", $teacher_id);
            $stmt_teacher->execute();
            $stmt_teacher->store_result();
            $stmt_teacher->bind_result($id, $firstname, $lastname, $nrc_number, $tcz_number, $contact, $address);

            if ($stmt_teacher->num_rows > 0) {
                $stmt_teacher->fetch();
                ?>
                <h2 class="text-center">Update Teacher Information</h2>
                <form method="post" action="update_teacher_process.php">
                    <input type="hidden" name="teacher_id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nrc_number">NRC Number:</label>
                        <input type="text" class="form-control" id="nrc_number" name="nrc_number" value="<?php echo $nrc_number; ?>">
                    </div>
                    <div class="form-group">
                        <label for="tcz_number">TCZ Number:</label>
                        <input type="text" class="form-control" id="tcz_number" name="tcz_number" value="<?php echo $tcz_number; ?>">
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact:</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $contact; ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
                <?php
            } else {
                echo "<div class='alert alert-danger text-center'>Teacher not found.</div>";
            }

            $stmt_teacher->close();
        } else {
            echo "<div class='alert alert-danger text-center'>Invalid request.</div>";
        }

        $conn->close();
        ?>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
