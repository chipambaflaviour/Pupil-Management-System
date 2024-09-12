<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
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

// Function to generate a unique pupil ID
function generatePupilID($class, $conn) {
    // Define prefixes for each grade (class)
    $classPrefixes = [
        '1' => '240801', // Grade 8
        '2' => '240901', // Grade 9
        '3' => '241001', // Grade 10
        '4' => '241101', // Grade 11
        '5' => '241201'  // Grade 12
    ];

    // Generate a unique ID for the given class
    $prefix = $classPrefixes[$class];
    $sql = "SELECT MAX(SUBSTRING(id, 7, 4)) AS max_suffix FROM pupils WHERE id LIKE '$prefix%'";
    $result = $conn->query($sql);
    
    $suffix = 1;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['max_suffix'] !== null) {
            $suffix = $row['max_suffix'] + 1;
        }
    }

    $uniqueID = str_pad($suffix, 4, '0', STR_PAD_LEFT);

    // Combine prefix and unique ID to form pupil ID
    $pupilID = $prefix . $uniqueID;

    return $pupilID;
}

// Handle form submission or data insertion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $added_by = 1; // Example: Assuming the admin ID who added the pupil
    $class_id = $_POST['class']; // Assuming a dropdown select menu for class

    // Generate pupil ID
    $pupilID = generatePupilID($class_id, $conn);

    if ($pupilID) {
        // Insert data into database
        $insert_sql = "INSERT INTO pupils (id, firstname, lastname, sex, birthdate, added_by, class_id)
                       VALUES ('$pupilID', '$firstname', '$lastname', '$sex', '$birthdate', $added_by, $class_id)";

        if ($conn->query($insert_sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error generating pupil ID.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pupil Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1F3F49;
            text-align: center;
            margin-top: 50px;
            color: white;
        }
        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #CED2CC;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 2px;
        }
        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
            color: black;
        }
        form input, form select {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button-row {
            display: flex;
            justify-content: space-between;
        }
        form button {
            width: calc(48% - 10px);
            padding: 10px;
            background-color: #D32D41;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button.cancel {
            background-color: #D32D41; /* Same color as register button */
        }
        form button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<h2>Pupil Registration</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>
    
    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>
    
    <label for="sex">Sex:</label>
    <select id="sex" name="sex" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br><br>
    
    <label for="birthdate">Birthdate:</label>
    <input type="date" id="birthdate" name="birthdate" required><br><br>
    
    <!-- Dropdown select menu for class -->
    <label for="class">Class:</label>
    <select id="class" name="class" required>
        <option value="1">Grade 8</option>
        <option value="2">Grade 9</option>
        <option value="3">Grade 10</option>
        <option value="4">Grade 11</option>
        <option value="5">Grade 12</option>
    </select><br><br>
    
    <div class="button-row">
        <button type="submit">Register Pupil</button>
        <button type="button" class="cancel" onclick="window.location.href='dashboard.php';">Cancel</button>
    </div>
</form>

</body>
</html>
