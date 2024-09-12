<!DOCTYPE html>
<html>
<head>
    <title>Pupil Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        .menubar {
            background-color: #1F3F49;
            text-align: right;
            position: sticky;
            top: 0;
            left: 0;
            overflow-x: hidden;
        }
        .menubar ul {
            display: inline-flex;
            list-style: none;
            color: white;
        }
        .menubar ul li {
            width: 120px;
            margin: 10px;
            padding: 15px;
        }
        .menubar ul li a {
            text-decoration: none;
            color: white;
            text-transform: uppercase;
        }
        .menubar ul li:hover {
            background-color: cyan;
            border-radius: 2px;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            color: #333;
            padding: 20px;
        }
        table {
            width: 50%;
			margin-top:30px ;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color:#1F3F49;
            color: white;
            width: 30%;
        }
        td {
            width: 70%;
        }
        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }
        tr:first-child th {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        tr:last-child td {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="menubar">
		
        <ul>
            
            <li><a href="view_pupil_results.php">MY RESULTS</a></li>
            <li><a href="pupils_subjects.php">MY CLASS/SUBJECTS</a></li>
			<li><a href="index.php">LOGOUT</a></li>
        </ul>
    </div>

    <h1 style="text-align: center;">Pupil Information</h1>

    <?php
    session_start();
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "registration";

    // Create connection
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $pupil_id = $_SESSION['pupil_id'];

    $sql = "SELECT * FROM pupils WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pupil_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<table>
        <tr><th>ID</th><td>" . htmlspecialchars($row['id']) . "</td></tr>
        <tr><th>First Name</th><td>" . htmlspecialchars($row['firstname']) . "</td></tr>
        <tr><th>Last Name</th><td>" . htmlspecialchars($row['lastname']) . "</td></tr>
        <tr><th>Sex</th><td>" . htmlspecialchars($row['sex']) . "</td></tr>
        <tr><th>Birthdate</th><td>" . htmlspecialchars($row['birthdate']) . "</td></tr>
        </table>";
    } else {
        echo "<p>No information found for the pupil.</p>";
    }

    $conn->close();
    ?>
     
</body>
</html>
