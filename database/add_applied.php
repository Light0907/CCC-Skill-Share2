<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admin');
define('DB_NAME', 'educ');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['account']['id'];
$internshipId = intval($_POST['internshipId']);
$sql = "INSERT INTO appliedTo (userId, internshipId) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $internshipId);

if ($stmt->execute()) {
    $_SESSION['success'] = "Application submitted successfully!";
    header("Location: ../find_internship.php");
} else {
    $_SESSION['error'] = "Error: " . $stmt->error;
    header("Location: ../index.php");
}
?>