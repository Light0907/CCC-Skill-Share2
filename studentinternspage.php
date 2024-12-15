<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admin');
define('DB_NAME', 'educ');

function fetchStudentsByProgram($program)
{
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, fullName, yearLevel FROM users WHERE program = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $program);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    $conn->close();
    return $students;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Interns</title>
    <link rel="stylesheet" href="/css/studentinternspage.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php require_once "admin_navigation.php"; ?>

        <div class="main-content">
            <div class="navbar">
                <h1>Student Interns</h1>
            </div>

            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search for students..." onkeyup="filterStudents()">
            </div>


            <?php
            $programs = ['BSAIS', 'BSA', 'BSIT', 'BSCS'];

            foreach ($programs as $program):
                $students = fetchStudentsByProgram($program);
                ?>
                <div class="program-section">
                    <h2><?php echo $program; ?></h2>
                    <div class="table" id="<?php echo $program; ?>">
                        <div class="row header">
                            <span>Full Name</span>
                            <span>Year Level</span>
                            <span>Actions</span>
                        </div>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <div class="row">
                                    <span><?php echo htmlspecialchars($student['fullName']); ?></span>
                                    <span><?php echo htmlspecialchars($student['yearLevel']); ?></span>
                                    <span>
                                        <a href="view_student.php?id=<?php echo $student['id']; ?>">
                                            <button class="view">View</button>
                                        </a>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="row">
                                <span colspan="4">No students available in this program.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <script>
        function filterStudents() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const tables = document.querySelectorAll('.table');

            tables.forEach(table => {
                const rows = table.querySelectorAll('.row:not(.header)');
                rows.forEach(row => {
                    const name = row.querySelector('span').innerText.toLowerCase();
                    row.style.display = name.includes(input) ? '' : 'none';
                });
            });
        }
    </script>
</body>

</html>