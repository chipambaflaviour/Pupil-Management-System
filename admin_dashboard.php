<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../db_connect.php'; // Adjust path as necessary

// Ensure user is logged in and session variables are set
if (!isset($_SESSION['loggedin'], $_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch admin data
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM admin WHERE adminID = '$admin_id'"; // Adjusted column name
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc(); // Fetch admin data
} else {
    echo "Admin not found.";
    // Handle this case more gracefully
}

// Get total number of teachers
$teacher_count_sql = "SELECT COUNT(*) AS total_teachers FROM teacher";
$teacher_count_result = $conn->query($teacher_count_sql);
$teacher_count = $teacher_count_result->fetch_assoc()['total_teachers'];

// Get total number of pupils
$pupil_count_sql = "SELECT COUNT(*) AS total_pupils FROM pupil";
$pupil_count_result = $conn->query($pupil_count_sql);
$pupil_count = $pupil_count_result->fetch_assoc()['total_pupils'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Custom styles for dashboard -->
  <style>
    /* Global Styles */
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 250px;
      background-color: #1E90FF; /* Dodger blue */
      padding-top: 70px;
    }
    .sidebar .nav-link {
      color: #fff;
    }
    .sidebar .nav-link:hover {
      color: #0d6efd;
    }
    .content {
      margin-left: 250px;
      padding: 20px;
    }
    .content h6 {
      font-size: 1.2rem;
      color: #555;
    }
    .card {
      margin-bottom: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
    }
    .card:hover {
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .card-body {
      padding: 20px;
    }
    .card-title {
      font-size: 1.2rem;
      color: #333;
      margin-bottom: 15px;
    }
    .card-text {
      color: #666;
    }
    .card-link {
      color: #1E90FF; /* Dodger blue */
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .card-link:hover {
      color: #0d6efd;
    }
    .navbar {
      background-color: #1E90FF; /* Dodger blue */
    }
    .navbar-brand, .navbar-nav .nav-link {
      color: white !important;
    }
    .navbar-nav .nav-link.username {
      color: white !important;
      margin-right: 15px;
    }
    /* Light Mode Styles */
    .light-mode {
      background-color: #f8f9fa;
      color: #333;
    }
    /* Dark Mode Styles */
    .dark-mode {
      background-color: #333;
      color: #f8f9fa;
    }
    .card.teachers-card {
      background-color: #f8d7da; /* Light red */
      color: #721c24; /* Dark red */
    }
    .card.pupils-card {
      background-color: #d4edda; /* Light green */
      color: #155724; /* Dark green */
    }
  </style>
</head>
<body class="<?php echo isset($_COOKIE['mode']) ? htmlspecialchars($_COOKIE['mode']) : 'light-mode'; ?>">

<!-- Sidebar -->
<div class="sidebar">
  <nav class="nav flex-column">
    <a class="nav-link" href="#"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    <a class="nav-link" href="view_teachers.php"><i class="fas fa-chalkboard-teacher mr-2"></i> Manage Teachers</a>
    <a class="nav-link" href="add_teacher.php"><i class="fas fa-plus mr-2"></i> Add Teacher</a>
    <a class="nav-link" href="settings.php"><i class="fas fa-cog mr-2"></i> Settings</a>
    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
  </nav>
</div>

<!-- Page content -->
<div class="content">
  <nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fas fa-user-circle mr-1"></i> Welcome, <?php echo isset($admin['adminName']) ? htmlspecialchars($admin['adminName']) : ''; ?></a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h6 class="mt-4 mb-3">Welcome, <?php echo isset($admin['adminName']) ? htmlspecialchars($admin['adminName']) : ''; ?></h6>
        <p class="text-muted">Manage your teachers, view their details, and adjust settings here.</p>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card teachers-card">
          <div class="card-body">
            <h5 class="card-title">Total Teachers</h5>
            <p class="card-text"><?php echo $teacher_count; ?> Teachers</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card pupils-card">
          <div class="card-body">
            <h5 class="card-title">Total Pupils</h5>
            <p class="card-text"><?php echo $pupil_count; ?> Pupils</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
