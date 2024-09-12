<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
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

// Fetch admin username
$user_id = $_SESSION['user_id'];
$user_result = $conn->query("SELECT username FROM users WHERE id = $user_id");
$user_data = $user_result->fetch_assoc();
$admin_username = $user_data['username'];

// Fetch data
$totalPupils = $conn->query("SELECT COUNT(*) as count FROM pupils")->fetch_assoc()['count'];
$totalTeachers = $conn->query("SELECT COUNT(*) as count FROM teachers")->fetch_assoc()['count'];

// Fetch data for specific grades
$totalPupilsG8 = $conn->query("SELECT COUNT(*) as count FROM pupils p LEFT JOIN classes c ON p.class_id = c.id WHERE c.class_name = 'Grade 8'")->fetch_assoc()['count'];
$totalPupilsG9 = $conn->query("SELECT COUNT(*) as count FROM pupils p LEFT JOIN classes c ON p.class_id = c.id WHERE c.class_name = 'Grade 9'")->fetch_assoc()['count'];
$totalPupilsG10 = $conn->query("SELECT COUNT(*) as count FROM pupils p LEFT JOIN classes c ON p.class_id = c.id WHERE c.class_name = 'Grade 10'")->fetch_assoc()['count'];
$totalPupilsG11 = $conn->query("SELECT COUNT(*) as count FROM pupils p LEFT JOIN classes c ON p.class_id = c.id WHERE c.class_name = 'Grade 11'")->fetch_assoc()['count'];
$totalPupilsG12 = $conn->query("SELECT COUNT(*) as count FROM pupils p LEFT JOIN classes c ON p.class_id = c.id WHERE c.class_name = 'Grade 12'")->fetch_assoc()['count'];

// Fetch teachers data
$teachers_result = $conn->query("SELECT t.id, t.firstname, t.lastname, t.username, c.class_name FROM teachers t LEFT JOIN classes c ON t.class_id = c.id");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color:  #1F3F49;

            position: sticky;
            color: white;
            top: 0;
            z-index: 1000;
        }
        .sidebar {
            background-color:  #1F3F49;

            color: white;
            height: 100vh;
            position: fixed;
        }
        .sidebar .nav-link {
            color: white;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            background-color: whitesmoke;
            margin-bottom: 50px;
        }
        .content h1 {
            text-align: center;
            align-items: center;
            width: 100%;
        }
        .footer {
            background-color:#1F3F49;

            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            color:white;
            margin-bottom: 0px;
            height: 80px;
            
            
        }
        .card {
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            color: white;
            
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
     
        .total-teachers {
            background-color: #23282D;
        }
        .total-pupils {
            background: linear-gradient(111.5deg, rgb(20, 100, 196) 0.4%, rgb(33, 152, 214) 100.2%);
        }
        .grade-8 {
            background-color: #1F3F49;
        }
        .grade-9 {
            background-color: #D32D41;
        }
        .grade-10 {
            background-color:#4CB5F5;
        }
        .grade-11 {
            background-color:  #EA6A47;
        }
        .grade-12 {
            background-color: #6AB187;
        }
        button {
            background-color: #D32D41;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            
            color: white;
            opacity: 1s;
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
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                                <i class="fas fa-tachometer-alt"></i>
                                Admin Dashboard
                            </a>
                        </li>
                      
                        <li class="nav-item">
                            <a class="nav-link" href="view_registeredteacher.php">
                                <i class="fas fa-user-plus"></i>
                                View Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="assign_classes.php">
                                <i class="fas fa-user-plus"></i>
                                assign clases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register_pupil.php">
                                <i class="fas fa-user-graduate"></i>
                                register pupils
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_pupils.php">
                                <i class="fas fa-user-graduate"></i>
                                View All Pupils
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_result.php">
                                <i class="fas fa-eye"></i>
                                View Results
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_performance.php">
                                <i class="fas fa-eye"></i>
                                View perfomance
                            </a>
                        </li>
                   
                    </ul>
                    <hr>
                    <br><br> <br>    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <button><a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a></button>
                        </li>
                    </ul>
                </div>
            </nav>

            < <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Welcome Admin:  <?php echo $admin_username; ?></h1>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card total-teachers">
                            <div class="card-body">
                                <i class="fas fa-chalkboard-teacher card-icon"></i>
                                <h5 class="card-title">Total Teachers</h5>
                                <p class="card-text"><?php echo $totalTeachers; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card total-pupils">
                            <div class="card-body">
                                <i class="fas fa-users card-icon"></i>
                                <h5 class="card-title">Total Pupils</h5>
                                <p class="card-text"><?php echo $totalPupils; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card grade-8">
                            <div class="card-body">
                                <i class="fas fa-user-graduate card-icon"></i>
                                <h5 class="card-title">Grade 8 Pupils</h5>
                                <p class="card-text"><?php echo $totalPupilsG8; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card grade-9">
                            <div class="card-body">
                                <i class="fas fa-user-graduate card-icon"></i>
                                <h5 class="card-title">Grade 9 Pupils</h5>
                                <p class="card-text"><?php echo $totalPupilsG9; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card grade-10">
                            <div class="card-body">
                                <i class="fas fa-user-graduate card-icon"></i>
                                <h5 class="card-title">Grade 10 Pupils</h5>
                                <p class="card-text"><?php echo $totalPupilsG10; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card grade-11">
                            <div class="card-body">
                                <i class="fas fa-user-graduate card-icon"></i>
                                <h5 class="card-title">Grade 11 Pupils</h5>
                                <p class="card-text"><?php echo $totalPupilsG11; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card grade-12">
                            <div class="card-body">
                                <i class="fas fa-user-graduate card-icon"></i>
                                <h5 class="card-title">Grade 12 Pupils</h5>
                                <p class="card-text"><?php echo $totalPupilsG12; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <footer class="footer">
        <div class="float-right d-none d-sm-inline-block">
            <b><a>copyright@2024 Lilayi Primary School-PMS</a></b> all rights reserved
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
