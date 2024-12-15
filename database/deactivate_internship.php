<?php
require_once 'config.php';

if (isset($_POST['internshipId']) && is_numeric($_POST['internshipId'])) {
    $internshipId = $_POST['internshipId'];
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    $sql = "UPDATE internship SET isDeactivated = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $internshipId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
    $conn->close();
} else {
    echo 'error';
}
?>