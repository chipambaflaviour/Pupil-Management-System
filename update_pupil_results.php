<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
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

$classes = $conn->query("SELECT id, class_name FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }
        h1 {
            margin-bottom: 1.5rem;
            color: #333;
        }
        .print-button {
            margin-top: 1rem;
        }
        .grading-system {
            margin-top: 2rem;
            text-align: center;
        }
        @media print {
            .print-button {
                display: none;
            }
            body::before {
                content: "Official Results";
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 4rem;
                color: rgba(0, 0, 0, 0.1);
                z-index: -1;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#class').change(function() {
                var class_id = $(this).val();
                $.ajax({
                    url: 'fetch_pupils.php',
                    method: 'POST',
                    data: { class_id: class_id },
                    success: function(data) {
                        $('#pupil').html(data);
                    }
                });
            });

            $('#pupil').change(function() {
                var pupil_id = $(this).val();
                $.ajax({
                    url: 'fetch_results.php',
                    method: 'POST',
                    data: { pupil_id: pupil_id },
                    success: function(data) {
                        $('#results').html(data);
                        $.ajax({
                            url: 'fetch_comment.php',
                            method: 'POST',
                            data: { pupil_id: pupil_id },
                            success: function(comment) {
                                $('#comment').val(comment);
                            }
                        });
                    }
                });
            });
        });

        function formatScore(score) {
            return Math.round(score) + '%';
        }

        function printReportCard() {
            var pupilName = $('#pupil option:selected').text();
            var className = $('#class option:selected').text();
            var term = $('#results').find('tr').first().find('td').eq(2).text();
            var year = $('#results').find('tr').first().find('td').eq(3).text();
            var teacherComment = $('#comment').val();

            var printContents = `
                <div style="text-align: center;">
                    <h2>Chalimbana Secondary School</h2>
                    <h3>Report Card</h3>
                </div>
                <div>
                    <p><strong>Pupil Name:</strong> ${pupilName}</p>
                    <p><strong>Class:</strong> ${className}</p>
                    <p><strong>Term:</strong> ${term}</p>
                    <p><strong>Year:</strong> ${year}</p>
                    <p><strong>Teacher Comment:</strong> ${teacherComment}</p>
                </div>
                <div>
                    <h4>Results</h4>
                    <table border="1" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #000; padding: 8px;">Subject</th>
                                <th style="border: 1px solid #000; padding: 8px;">Score</th>
                                <th style="border: 1px solid #000; padding: 8px;">Term</th>
                                <th style="border: 1px solid #000; padding: 8px;">Year</th>
                            </tr>
                        </thead>
                        <tbody id="print-results">
                            ${$('#results').html().replace(/(\d+\.\d+)/g, function(match) {
                                return formatScore(parseFloat(match));
                            })}
                        </tbody>
                    </table>
                </div>
                <div class="grading-system">
                    <h4>Grading System</h4>
                    <table border="1" style="width: 60%; margin: 0 auto; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #000; padding: 8px;">Grade</th>
                                <th style="border: 1px solid #000; padding: 8px;">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px;">A</td>
                                <td style="border: 1px solid #000; padding: 8px;">75 - 100 (Excellent)</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px;">B</td>
                                <td style="border: 1px solid #000; padding: 8px;">60 - 74 (Very Good)</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px;">C</td>
                                <td style="border: 1px solid #000; padding: 8px;">50 - 59 (Good)</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px;">D</td>
                                <td style="border: 1px solid #000; padding: 8px;">40 - 49 (Satisfactory)</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px;">F</td>
                                <td style="border: 1px solid #000; padding: 8px;">0 - 39 (Fail)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;

            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Update Pupil Results</h1>
        <form action="process_update_result.php" method="post">
            <div class="mb-3 row">
                <label for="class" class="col-sm-2 col-form-label">Class</label>
                <div class="col-sm-10">
                    <select id="class" name="class" class="form-select" required>
                        <option value="">Select class</option>
                        <?php while ($row = $classes->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['class_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="pupil" class="col-sm-2 col-form-label">Pupil</label>
                <div class="col-sm-10">
                    <select id="pupil" name="pupil" class="form-select" required>
                        <option value="">Select pupil</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Results</label>
                <div class="col-sm-10" id="results-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Subject</th>
                                <th scope="col">Score</th>
                                <th scope="col">Term</th>
                                <th scope="col">Year</th>
                            </tr>
                        </thead>
                        <tbody id="results">
                            <!-- Results will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="comment" class="col-sm-2 col-form-label">Teacher Comment</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="comment" name="comment" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Update Results</button>
                    <button type="button" class="btn btn-secondary print-button" onclick="printReportCard()">Print Report Card</button>
                    <button type="submit" class="btn btn-primary"><a href="teacherdashboard.php" style="color: white;">cancel</a></button>
                </div>
            </div>
        </form>
        <div class="grading-system">
            <h4>Grading System</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>A</td>
                        <td>75 - 100 (Excellent)</td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td>60 - 74 (Very Good)</td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td>50 - 59 (Good)</td>
                    </tr>
                    <tr>
                        <td>D</td>
                        <td>40 - 49 (Satisfactory)</td>
                    </tr>
                    <tr>
                        <td>F</td>
                        <td>0 - 39 (Fail)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
