<?php
require_once("./config.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Database connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die('<div class="error-message">Database connection failed: ' . htmlspecialchars($conn->connect_error) . '</div>');
    }

    // Check if token exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE token = ? AND verified = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();

        // Mark user as verified
        $stmt = $conn->prepare("UPDATE users SET verified = 1, token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            echo '<div class="success-message">Your email has been verified! You can now log in.</div>';
        } else {
            echo '<div class="error-message">Failed to verify email. Please try again later.</div>';
        }
    } else {
        echo '<div class="error-message">Invalid or expired token.</div>';
    }
    $stmt->close();
    $conn->close();
} else {
    echo '<div class="error-message">No token provided.</div>';
}
?>
