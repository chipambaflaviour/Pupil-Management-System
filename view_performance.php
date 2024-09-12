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

// Fetch classes
$classes_sql = "SELECT id, class_name FROM classes";
$classes_result = $conn->query($classes_sql);

// Initialize data array
$data = [];

if ($classes_result->num_rows > 0) {
    while ($class = $classes_result->fetch_assoc()) {
        $class_id = $class['id'];
        $class_name = $class['class_name'];

        // Fetch results for each class
        $results_sql = "SELECT p.id as pupil_id, p.firstname, p.lastname, r.subject_id, r.score 
                        FROM pupils p
                        JOIN results r ON p.id = r.pupil_id
                        WHERE p.class_id = $class_id";
        $results_result = $conn->query($results_sql);

        $subjects_count_sql = "SELECT COUNT(id) as subject_count FROM subjects";
        $subjects_count_result = $conn->query($subjects_count_sql);
        $subjects_count = $subjects_count_result->fetch_assoc()['subject_count'];

        $total_students = [];
        $passed_students = [];

        if ($results_result->num_rows > 0) {
            while ($result = $results_result->fetch_assoc()) {
                $pupil_id = $result['pupil_id'];
                $score = $result['score'];

                if (!isset($total_students[$pupil_id])) {
                    $total_students[$pupil_id] = 0;
                    $passed_students[$pupil_id] = 0;
                }

                $total_students[$pupil_id]++;
                if ($score >= 50) {
                    $passed_students[$pupil_id]++;
                }
            }

            $total_pupils = count($total_students);
            $total_passed = 0;

            foreach ($total_students as $pupil_id => $total) {
                if ($passed_students[$pupil_id] >= $total / 2) {
                    $total_passed++;
                }
            }

            $pass_percentage = ($total_pupils > 0) ? ($total_passed / $total_pupils) * 100 : 0;

            $data[] = [
                'class' => $class_name,
                'pass_percentage' => $pass_percentage,
                'total_pupils' => $total_pupils,
                'total_passed' => $total_passed
            ];
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Performance View</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #1F3F49;
        }
        h1 {
            margin: 20px 0;
            color: white;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        canvas {
            width: 100% !important;
            height: 300px !important; /* Adjust height for better visibility */
        }
        .btn-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .btn {
            padding: 10px 20px;
            background-color: #D32D41;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .summary {
            margin-top: 20px;
            width: 100%;
            overflow-x: auto; /* Allows horizontal scrolling if needed */
        }
        .summary table {
            width: 100%; /* Ensure table fits within container */
            border-collapse: collapse;
        }
        .summary th, .summary td {
            padding: 6px; /* Reduced padding */
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .summary th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Pupil Performance in All Classes</h1>
    <div class="container">
        <canvas id="performanceChart"></canvas>
        <div class="summary">
            <h2>Performance Summary</h2>
            <table>
                <tr>
                    <th>Class</th>
                    <th>Total Pupils</th>
                    <th>Total Passed</th>
                    <th>Pass Percentage</th>
                </tr>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['class']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_pupils']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_passed']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['pass_percentage'], 0)); ?>%</td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="btn-container">
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
    <script>
        const colors = [
            '#0091D5', '#EA6A47', '#23282D', '#488A99', '#DBAE58'
        ];
        var ctx = document.getElementById('performanceChart').getContext('2d');
        var performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($data, 'class')); ?>,
                datasets: [{
                    label: 'Pass Percentage',
                    data: <?php echo json_encode(array_column($data, 'pass_percentage')); ?>,
                    backgroundColor: colors.map(color => color + '80'), // Add transparency
                    borderColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>
</html>
