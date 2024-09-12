<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$limit = 5; // Number of pupils per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search parameter
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch pupils with class names, paginated
$sql = "SELECT p.id, p.firstname, p.lastname, p.sex, p.birthdate, c.class_name
        FROM pupils p
        LEFT JOIN classes c ON p.class_id = c.id
        WHERE p.id LIKE '%$search%' OR c.class_name LIKE '%$search%'
        LIMIT $start, $limit";

$result = $conn->query($sql);

$pupils = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pupils[] = $row;
    }
}

// Pagination links
$sql_total = "SELECT COUNT(*) AS total FROM pupils WHERE id LIKE '%$search%' OR class_id LIKE '%$search%'";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_pages = ceil($row_total['total'] / $limit);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Pupils</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #CED2CC;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar input {
            padding: 10px;
            width: 50%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-bar button {
            padding: 10px 20px;
            border: none;
            background-color: #D32D41;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color .3s;
        }
        .search-bar button:hover {
            background-color: #0056b3;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            background-color: #D32D41;
            cursor: pointer;
        }
        .pagination a.active {
            background-color: #333;
            color: #fff;
        }
        .pagination a:hover:not(.active) {
            background-color: #D32D41;
        }
        .action-icons {
            display: flex;
            justify-content: center;
        }
        .action-icons a {
            margin: 0 5px;
            color: #333;
            transition: color .3s;
        }
        .action-icons a:hover {
            color: #007bff;
        }
        .dashboard-btn {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .dashboard-btn button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            background-color: #D32D41;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color .3s;
        }
        .dashboard-btn button:hover {
            background-color: #0056b3;
        }
        i {
            color: #D32D41;
        }
        .action-icons a:first-child i {
            color: #4CB5F5;
        }
        .action-icons a:last-child i {
            color: #D32D41;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Pupils</h2>
        <div class="dashboard-btn">
            <button onclick="window.location.href='dashboard.php'">Go to Dashboard</button>
        </div>
        <div class="search-bar">
            <form action="" method="get">
                <input type="text" name="search" id="searchInput" placeholder="Search by class name or pupil ID..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <table id="pupilsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Sex</th>
                    <th>Birthdate</th>
                    <th>Class</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pupils)): ?>
                    <?php foreach ($pupils as $pupil): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pupil['id']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['sex']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['birthdate']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['class_name']); ?></td>
                            <td class="action-icons">
                                <a href="update_pupil.php?id=<?php echo htmlspecialchars($pupil['id']); ?>"><i class="fas fa-edit"></i></a>
                                <a href="delete_pupil.php?id=<?php echo htmlspecialchars($pupil['id']); ?>"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No pupils found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" <?php if ($page == $i) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
