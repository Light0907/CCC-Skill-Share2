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
$sql = "UPDATE users SET isCompany = TRUE WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    $_SESSION['account']['aCompany'] = true;
    $_SESSION['success'] = "You are successfully a company account!";
    header("Location: ../index.php");
} else {
    $_SESSION['error'] = "Error: " . $stmt->error;
    header("Location: ../index.php");
}
?>