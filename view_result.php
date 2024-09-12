<?php
// Database connection
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

// Initialize variables for search
$search_pupil_id = isset($_GET['pupil_id']) ? $_GET['pupil_id'] : '';
$search_class_name = isset($_GET['class_name']) ? $_GET['class_name'] : '';

// Query based on search filters
$sql = "SELECT c.class_name, p.id AS pupil_id, p.firstname, p.lastname, 
               GROUP_CONCAT(CONCAT(s.name, ' ', ROUND(r.score), '%') SEPARATOR ' | ') AS subject_scores,
               r.term, r.year, r.comment
        FROM results r
        JOIN pupils p ON r.pupil_id = p.id
        JOIN classes c ON p.class_id = c.id
        JOIN subjects s ON r.subject_id = s.id
        WHERE (p.id LIKE '%$search_pupil_id%' OR '$search_pupil_id' = '')
          AND (c.class_name LIKE '%$search_class_name%' OR '$search_class_name' = '')
        GROUP BY p.id, r.term, r.year
        ORDER BY c.class_name, p.lastname, p.firstname, r.term, r.year";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output table with internal styling
    echo "<style>
            body {
                background-color: #1F3F49;
                color: #FFFFFF;
                font-family: Arial, sans-serif;
            }
            table {
                width: 80%;
                margin: 20px auto;
                border-collapse: collapse;
                background-color: #CED2CC;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            th {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
                background-color: #6AB187;
                color: #FFFFFF;
            }
            td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
                background-color: #23282D;
                color: #FFFFFF;
            }
            tr:nth-child(even) td {
                background-color: #1F3F49;
            }
            tr:nth-child(odd) td {
                background-color: #23282D;
            }
            h2 {
                text-align: center;
                color: #FFFFFF;
            }
            form {
                text-align: center;
                margin-bottom: 20px;
            }
            input[type='text'] {
                padding: 5px;
                margin: 5px;
            }
            input[type='submit'], button {
                padding: 10px 20px;
                margin: 10px;
                background-color: #D32D41;
                color: #FFFFFF;
                border: none;
                cursor: pointer;
            }
            input[type='submit']:hover, button:hover {
                background-color: #B3C100;
            }
            label {
                color: #FFFFFF;
            }
        </style>";
    
    // Output search form
    echo "<h2>Chalimbana Secondary School Results</h2>";
    
    echo "<form method='GET' action=''>
            <label for='pupil_id'>Search by Pupil ID:</label>
            <input type='text' id='pupil_id' name='pupil_id' value='$search_pupil_id'>
            <label for='class_name'>Class Name:</label>
            <input type='text' id='class_name' name='class_name' value='$search_class_name'>
            <input type='submit' value='Search'>
            <button type='button' onclick='window.location.href=\"dashboard.php\"'>Cancel</button>
          </form>";

    // Initialize variables
    $currentPupil = "";

    // Output table header
    echo "<table>
            <tr>
                <th>Class</th>
                <th>Pupil ID</th>
                <th>Name</th>
                <th>Subjects & Scores</th>
                <th>Term</th>
                <th>Year</th>
                <th>Comment</th>
            </tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["class_name"] . "</td>
                <td>" . $row["pupil_id"] . "</td>
                <td>" . $row["firstname"] . " " . $row["lastname"] . "</td>
                <td>" . $row["subject_scores"] . "</td>
                <td>" . $row["term"] . "</td>
                <td>" . $row["year"] . "</td>";
        
        // Display comment only once per pupil
        if ($row["pupil_id"] != $currentPupil) {
            echo "<td>" . $row["comment"] . "</td>";
            $currentPupil = $row["pupil_id"];
        } else {
            echo "<td></td>";
        }
        
        echo "</tr>";
    }
    echo "</table>";
  
} else {
    echo "<p>No results found.</p>";
}

$conn->close();
?>
