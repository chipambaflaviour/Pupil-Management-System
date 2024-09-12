<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Role to Teacher</title>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1F3F49;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #CED2CC;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: black;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn-container button,
        .btn-container a {
            padding: 10px 20px;
            background-color: #D32D41;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-container button:hover,
        .btn-container a:hover {
            background-color: #4CB5F5;
        }
        .btn-container a {
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assign Role to Teacher</h2>
        <div class="form-container">
            <form action="assign_role_submit.php" method="POST">
                <div class="form-group">
                    <label for="teacher">Select Teacher:</label>
                    <select name="teacher" id="teacher">
                        <?php
                        // PHP code to fetch teachers for selection
                        session_start();
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "registration";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT id, firstname, lastname FROM teachers";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No teachers found</option>";
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="role">Assign Role:</label>
                    <select name="role" id="role">
                        <option value="teacher">Teacher</option>
                        <option value="subject_teacher">Subject Teacher</option>
                    </select>
                </div>
                <div class="btn-container">
                    <button type="submit">Assign Role</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
