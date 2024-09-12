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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Custom styles for settings -->
  <style>
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
    /* Custom button styles */
    .btn a {
      color: white; /* Set text color to white */
      text-decoration: none; /* Remove underline */
    }
    .btn-primary a:hover, .btn-secondary a:hover {
      color: white; /* Ensure text color remains white on hover */
    }
  </style>
</head>
<body class="<?php echo isset($_COOKIE['mode']) ? htmlspecialchars($_COOKIE['mode']) : 'light-mode'; ?>">

<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-md-12">
      <h6 class="mt-4 mb-3">Settings</h6>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Theme Settings</h5>
          <p class="card-text">Choose between light and dark mode.</p>
          <button id="light-mode-btn" class="btn btn-primary"><a href="dashboard.php">Light Mode</a></button>
          <button id="dark-mode-btn" class="btn btn-secondary"><a href="dashboard.php">Dark Mode</a></button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript for mode switching -->
<script>
  document.getElementById('light-mode-btn').addEventListener('click', function() {
    document.cookie = "mode=light-mode;path=/;max-age=31536000"; // 1 year
    window.location.href = 'dashboard.php';
  });

  document.getElementById('dark-mode-btn').addEventListener('click', function() {
    document.cookie = "mode=dark-mode;path=/;max-age=31536000"; // 1 year
    window.location.href = 'dashboard.php';
  });
</script>

<!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
