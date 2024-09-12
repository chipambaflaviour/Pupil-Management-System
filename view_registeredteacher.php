<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Teachers</title>
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
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
        .action-buttons {
            display: flex;
            justify-content: center;
        }
        .action-buttons a {
            display: inline-block;
            padding: 5px 10px;
            color: #fff;
            border-radius: 5px;
            margin: 0 2px;
            text-decoration: none;
        }
        .action-buttons .assign-class {
            background-color: #4CAF50; /* Green */
        }
        .action-buttons .update {
            background-color: #1C4E80; /* Blue */
        }
        .action-buttons .delete {
            background-color: #f44336; /* Red */
        }
        .action-buttons .assign-role {
            background-color: #0091D5; /* Orange */
        }
        .action-buttons a:hover {
            opacity: 0.8;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination button {
            padding: 10px 15px;
            margin: 0 5px;
            border: 1px solid #ddd;
            background-color: #D32D41;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .pagination button:hover {
            background-color: #0056b3;
        }
        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar input {
            padding: 10px;
            width: 50%;
            border: 1px solid purple;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Teachers</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for teachers by name or ID...">
        </div>
        <table id="teachersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>NRC Number</th>
                    <th>TCZ Number</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Class ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // PHP code to fetch and display table rows
                session_start();
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "registration";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, firstname, lastname, nrc_number, tcz_number, contact, address, class_id FROM teachers";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['firstname'] . "</td>";
                        echo "<td>" . $row['lastname'] . "</td>";
                        echo "<td>" . $row['nrc_number'] . "</td>";
                        echo "<td>" . $row['tcz_number'] . "</td>";
                        echo "<td>" . $row['contact'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>" . $row['class_id'] . "</td>";
                        echo "<td class='action-buttons'>";
                        echo "<a href='assign_class.php?id=" . $row['id'] . "' class='assign-class' title='Assign Class'><i class='fas fa-chalkboard-teacher'></i></a>";
                        echo "<a href='update_teacher.php?id=" . $row['id'] . "' class='update' title='Update'><i class='fas fa-edit'></i></a>";
                        echo "<a href='delete_teacher.php?id=" . $row['id'] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this teacher?\");' title='Delete'><i class='fas fa-trash'></i></a>";
                        echo "<a href='assign_role.php?id=" . $row['id'] . "' class='assign-role' title='Assign Role'><i class='fas fa-user-cog'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No teachers found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
        <div class="pagination">
            <button onclick="prevPage()">Previous</button>
            <button onclick="nextPage()">Next</button>
            <button><a href="dashboard.php" class="btn btn-secondary" style="color: white; text-decoration: none;">Cancel</a></button>
        </div>
    </div>
    
    <script>
        let currentPage = 1;
        const rowsPerPage = 5;

        function displayTable(page) {
            const table = document.getElementById('teachersTable').getElementsByTagName('tbody')[0];
            const rows = table.getElementsByTagName('tr');
            const totalRows = rows.length;

            for (let i = 0; i < totalRows; i++) {
                rows[i].style.display = 'none';
            }

            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            for (let i = start; i < end && i < totalRows; i++) {
                rows[i].style.display = '';
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                displayTable(currentPage);
            }
        }

        function nextPage() {
            const table = document.getElementById('teachersTable').getElementsByTagName('tbody')[0];
            const rows = table.getElementsByTagName('tr');
            const totalRows = rows.length;
            if ((currentPage * rowsPerPage) < totalRows) {
                currentPage++;
                displayTable(currentPage);
            }
        }

        function searchTable() {
            let filter = document.getElementById('searchInput').value.toUpperCase();
            let table = document.getElementById('teachersTable');
            let tr = table.getElementsByTagName('tr');

            for (let i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let found = false;
                for (let j = 0; j < td.length && !found; j++) {
                    if (td[j]) {
                        let txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                        }
                    }
                }
                if (found) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }

        displayTable(currentPage);
    </script>
</body>
</html>
