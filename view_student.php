<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admin');
define('DB_NAME', 'educ');

if (!isset($_GET['id'])) {
    echo "No student ID provided.";
    exit();
}

$studentId = $_GET['id'];
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT fullName, email, program, yearLevel, resumePdf, skills, photoUrl FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "Student not found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            padding: 40px;
            margin: 20px;
            text-align: center;
        }

        .profile-container h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
        }

        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-container p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .profile-container p strong {
            color: #007bff;
        }

        .resume-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .resume-button:hover {
            background-color: #0056b3;
        }

        .skills {
            margin-top: 30px;
            text-align: left;
        }

        .skills ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .skills li {
            background-color: #f9f9f9;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        .skills li:hover {
            background-color: #e6f7ff;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h1><?php echo htmlspecialchars($student['fullName']); ?> - Profile</h1>
        <img src="<?php echo htmlspecialchars($student['photoUrl']); ?>" alt="Profile Photo">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Program:</strong> <?php echo htmlspecialchars($student['program']); ?></p>
        <p><strong>Year Level:</strong> <?php echo htmlspecialchars($student['yearLevel']); ?></p>

        <div class="skills">
            <p><strong>Skills:</strong></p>
            <ul>
                <?php
                $skills = json_decode($student['skills'], true);
                if (is_array($skills)) {
                    foreach ($skills as $skill) {
                        echo "<li>" . htmlspecialchars($skill) . "</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <?php if (!empty($student['resumePdf'])): ?>
            <a href="<?php echo htmlspecialchars($student['resumePdf']); ?>" target="_blank" class="resume-button">View
                Resume</a>
        <?php endif; ?>
    </div>
</body>

</html>