<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'teacher' && $_SESSION['role'] != 'teacher')) {
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_results'])) {
    $results = json_decode($_POST['results'], true);
    $class_id = $_POST['class_id'];

    foreach ($results as $result) {
        $pupil_id = $result['pupil_id'];
        $subject_id = $result['subject_id'];
        $score = $result['score'];
        $term = $result['term'];
        $year = $result['year'];

        $sql = "INSERT INTO results (pupil_id, subject_id, score, term, year) VALUES ('$pupil_id', '$subject_id', '$score', '$term', '$year')";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
    echo "Results submitted successfully!";
   
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id as class_id, c.class_name 
        FROM classes c
        JOIN teachers t ON FIND_IN_SET(c.id, t.class_ids)
        WHERE t.id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "No classes available for this teacher.";
    $conn->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Pupil Results Entry</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background-color: #343a40;
            color: white;
            position: sticky;
            top: 0;
            width: 100%;
            padding: 10px;
            z-index: 1000;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            font-size: 1.5rem;
            margin-left: 20px;
        }
        .logout-link {
            margin-right: 20px;
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card:nth-child(1) { background-color: #202020; color: #fff; }
        .card:nth-child(2) { background-color: #7E909A; }
        .card:nth-child(3) { background-color: #1C4E80; color: #fff; }
        .card:nth-child(4) { background-color: #A5D8DD; }
        .card:nth-child(5) { background-color: #EA6A47; color: #fff; }
        .card:nth-child(6) { background-color: #0091D5; color: #fff; }
        #resultsForm {
            display: none;
            background-color:  #CED2CC;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 60%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: black;
        }
        select, input[type="file"], button {
            display: block;
            margin: 10px auto;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
        }
        #spreadsheetContainer {
            width: 80%;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-brand">Teacher Dashboard</div>
        <a href="teacherdashboard.php" class="logout-link">home</a>
    </div>
    <div class="container">
        <h2>Select Class and Enter Pupil Results</h2>
        <div class="cards">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="card" onclick="showResultForm('<?php echo $row['class_id']; ?>')">
                    <?php echo $row['class_name']; ?>
                </div>
            <?php } ?>
        </div>
        <form id="resultsForm">
            <input type="file" id="fileUpload" accept=".xlsx, .xls"/>
            <button type="button" onclick="importFile()">Preview Results</button>
            <div id="spreadsheetContainer"></div>
            <input type="hidden" name="class_id" id="hidden_class_id">
            <button type="submit" name="submit_results">Submit Results</button>
            <a href="add_results.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script>
        function showResultForm(class_id) {
            document.getElementById('hidden_class_id').value = class_id;
            document.querySelector('.cards').style.display = 'none';
            document.getElementById('resultsForm').style.display = 'flex';
        }

        document.getElementById('resultsForm').addEventListener('submit', function(event) {
            event.preventDefault();
            submitResults();
        });

        function importFile() {
            var fileUpload = document.getElementById('fileUpload');
            var reader = new FileReader();

            reader.onload = function(e) {
                var data = e.target.result;
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                workbook.SheetNames.forEach(function(sheetName) {
                    var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                    displayResults(XL_row_object);
                });
            };

            reader.onerror = function(ex) {
                console.log(ex);
            };

            reader.readAsBinaryString(fileUpload.files[0]);
        }

        function displayResults(results) {
            var spreadsheetContainer = document.getElementById('spreadsheetContainer');
            spreadsheetContainer.innerHTML = "";

            var table = document.createElement('table');

            var thead = document.createElement('thead');
            var tbody = document.createElement('tbody');

            // Create table headers
            var headers = Object.keys(results[0]);
            var tr = document.createElement('tr');
            headers.forEach(function(header) {
                var th = document.createElement('th');
                th.appendChild(document.createTextNode(header));
                tr.appendChild(th);
            });
            thead.appendChild(tr);

            // Create table rows
            results.forEach(function(row) {
                var tr = document.createElement('tr');
                headers.forEach(function(header) {
                    var td = document.createElement('td');
                    td.appendChild(document.createTextNode(row[header]));
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });

            table.appendChild(thead);
            table.appendChild(tbody);
            spreadsheetContainer.appendChild(table);
        }

        function submitResults() {
            var results = [];
            var headers = [];
            var rows = document.querySelectorAll('#spreadsheetContainer table tbody tr');

            // Get table headers
            document.querySelectorAll('#spreadsheetContainer table thead th').forEach(function(th) {
                headers.push(th.textContent);
            });

            // Get table rows data
            rows.forEach(function(row) {
                var result = {};
                row.querySelectorAll('td').forEach(function(td, index) {
                    result[headers[index]] = td.textContent;
                });
                results.push(result);
            });

            $.ajax({
                url: 'add_results.php',
                method: 'POST',
                data: {
                    submit_results: true,
                    results: JSON.stringify(results),
                    class_id: document.getElementById('hidden_class_id').value
                },
                success: function(response) {
                    alert(response);
                    window.location.href = 'add_results.php';
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        }
    </script>
</body>
</html>
