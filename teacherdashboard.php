<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
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

// Fetch teacher's username
$teacher = [];
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT u.username FROM users u WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($teacher['username']);
$stmt->fetch();
$stmt->close();

// Fetch the class ID the teacher is responsible for
$class_id = null;
$stmt = $conn->prepare("SELECT t.class_id FROM teachers t WHERE t.username = ?");
$stmt->bind_param("s", $teacher['username']);
$stmt->execute();
$stmt->bind_result($class_id);
$stmt->fetch();
$stmt->close();

// Fetch total pupils count for the class
$total_pupils_result = $conn->prepare("SELECT COUNT(*) AS total FROM pupils WHERE class_id = ?");
$total_pupils_result->bind_param("i", $class_id);
$total_pupils_result->execute();
$total_pupils_result->bind_result($total_pupils);
$total_pupils_result->fetch();
$total_pupils_result->close();

// Fetch today's attendance counts for the class
$date = date('Y-m-d');

// Prepare and execute queries for present, absent, and sick counts
$present_query = $conn->prepare("SELECT COUNT(*) AS present FROM attendance a JOIN pupils p ON a.pupil_id = p.id WHERE a.date = ? AND a.status = 'present' AND p.class_id = ?");
$present_query->bind_param("si", $date, $class_id);
$present_query->execute();
$present_query->bind_result($present_count);
$present_query->fetch();
$present_query->close();

$absent_query = $conn->prepare("SELECT COUNT(*) AS absent FROM attendance a JOIN pupils p ON a.pupil_id = p.id WHERE a.date = ? AND a.status = 'absent' AND p.class_id = ?");
$absent_query->bind_param("si", $date, $class_id);
$absent_query->execute();
$absent_query->bind_result($absent_count);
$absent_query->fetch();
$absent_query->close();

$sick_query = $conn->prepare("SELECT COUNT(*) AS sick FROM attendance a JOIN pupils p ON a.pupil_id = p.id WHERE a.date = ? AND a.status = 'sick' AND p.class_id = ?");
$sick_query->bind_param("si", $date, $class_id);
$sick_query->execute();
$sick_query->bind_result($sick_count);
$sick_query->fetch();
$sick_query->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding-top: 60px;
            background-color: #1F3F49;
            color: white;
        }
        .navbar {
            background-color: #1F3F49;
            position: sticky;
            color: white;
            top: 0;
            z-index: 1000;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: black;
        }
        button {
            background-color:#EA6A47;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1F3F49;
            color: white;
        }
        .content {
            margin-left: 280px;
            padding: 20px;
            background-color: whitesmoke;
        }
        .footer {
            background-color: #1F3F49;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        #attendanceChart {
            max-width: 800px;
            margin: auto;
            height: 400px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <a class="navbar-brand" href="#">Lilayi Primary School-PMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="mark_attendance.php">
                                <i class="fas fa-users"></i>
                                Mark Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="attendance_report.php">
                                <i class="fas fa-chart-line"></i>
                                Attendance Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="print_attendance.php">
                                <i class="fas fa-print"></i>
                                Print Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_results.php">
                                <i class="fas fa-upload"></i>
                                Upload Results
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="update_pupil_results.php">
                                <i class="fas fa-check"></i>
                                Validate Result
                            </a>
                        </li>
                    </ul>
                    <hr><br><br>
                    <ul class="nav flex-column mt-auto">
                        <li class="nav-item">
                            <button><a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a></button>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Welcome Teacher:  <?php echo htmlspecialchars($teacher['username']); ?></h1>
                </div>
                
                <div>
                    <canvas id="attendanceChart"></canvas>
                </div>
            </main>
        </div>
    </div>
    <footer class="footer">
        <div class="float-right d-none d-sm-inline-block">
            <b><a>copyright@2024 Lilayi Primary School-PMS</a></b> all rights reserved
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('attendanceChart').getContext('2d');
            var attendanceChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Present', 'Absent', 'Sick', 'Total Pupils'],
                    datasets: [{
                        label: 'Attendance',
                        data: [<?php echo $present_count; ?>, <?php echo $absent_count; ?>, <?php echo $sick_count; ?>, <?php echo $total_pupils; ?>],
                        backgroundColor: [
                            '#1C4E80',
                            '#A5D8DD',
                            '#EA6A47',
                            '#0091D5'
                        ],
                        borderColor: [
                            '#1C4E80',
                            '#A5D8DD',
                            '#EA6A47',
                            '#0091D5'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    let value = tooltipItem.raw;
                                    if (tooltipItem.label === 'Total Pupils') {
                                        return `${tooltipItem.label}: ${value}`;
                                    } else {
                                        return `${tooltipItem.label}: ${value} (${(value / <?php echo $total_pupils; ?> * 100).toFixed(2)}%)`;
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
