<?php
require_once './config.php';

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT fullName, program, yearLevel, email, skills FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        echo "<p><strong>Name:</strong> " . htmlspecialchars($student['fullName']) . "</p>";
        echo "<p><strong>Program:</strong> " . htmlspecialchars($student['program']) . "</p>";
        echo "<p><strong>Year Level:</strong> " . htmlspecialchars($student['yearLevel']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($student['email']) . "</p>";

        if (!empty($student['skills'])) {
            echo "<p><strong>Skills:</strong> " . htmlspecialchars($student['skills']) . "</p>";
        }

    } else {
        echo "<p>No details found for this student.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>