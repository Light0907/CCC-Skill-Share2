<?php
require_once("./config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); // Start session to store error and success messages

    // Get form inputs
    $photoUrl = $_FILES['photoUrl'];
    $resumePdf = $_FILES['resumePdf'];
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $program = trim($_POST['program']);
    $yearLevel = trim($_POST['yearLevel']);
    $skills = isset($_POST['skills']) ? json_encode($_POST['skills']) : json_encode([]);

    $errors = array(); 

    // Validate inputs
    if (empty($fullName)) {
        $_SESSION['error'] = "Full Name is required.";
        header("Location: ../register.php");
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s\.]+$/", $fullName)) {
        $_SESSION['error'] = "Full Name must contain only letters, spaces, and periods.";
        header("Location: ../register.php");
        exit();
    }
    
    

    if (empty($email)) {
        $_SESSION['error'] = "Email is required.";
        header("Location: ../register.php");
        exit();
    }

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: ../register.php");
        exit();
    }

    if (empty($password)) {
        $_SESSION['error'] = "Password is required.";
        header("Location: ../register.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long.";
        header("Location: ../register.php");
        exit();
    }

    if (empty($program)) {
        $_SESSION['error'] = "Program is required.";
        header("Location: ../register.php");
        exit();
    }

    if (empty($yearLevel) || !is_numeric($yearLevel)) {
        $_SESSION['error'] = "Year Level must be a numeric value.";
        header("Location: ../register.php");
        exit();
    }

    if (empty($photoUrl['name'])) {
        $_SESSION['error'] = "Profile photo is required.";
        header("Location: ../register.php");
        exit();
    }

    $allowedPhotoExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $photoExtension = pathinfo($photoUrl['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($photoExtension), $allowedPhotoExtensions)) {
        $_SESSION['error'] = "Profile photo must be a JPG, JPEG, PNG, or GIF file.";
        header("Location: ../register.php");
        exit();
    }

    if (empty($resumePdf['name'])) {
        $_SESSION['error'] = "Resume PDF is required.";
        header("Location: ../register.php");
        exit();
    }

    if ($resumePdf['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Error uploading resume PDF.";
        header("Location: ../register.php");
        exit();
    }

    $allowedResumeExtensions = ['pdf'];
    $resumeExtension = pathinfo($resumePdf['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($resumeExtension), $allowedResumeExtensions)) {
        $_SESSION['error'] = "Resume must be a PDF file.";
        header("Location: ../register.php");
        exit();
    }

    // Save files
    $photoPath = '../uploads/photos/' . uniqid() . '_' . basename($photoUrl['name']);
    if (!move_uploaded_file($photoUrl['tmp_name'], $photoPath)) {
        $_SESSION['error'] = "Failed to upload profile photo.";
        header("Location: ../register.php");
        exit();
    }

    $resumePath = '../uploads/resumes/' . uniqid() . '_' . basename($resumePdf['name']);
    if (!move_uploaded_file($resumePdf['tmp_name'], $resumePath)) {
        $_SESSION['error'] = "Failed to upload resume PDF.";
        header("Location: ../register.php");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Database connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
        header("Location: ../register.php");
        exit();
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO users (photoUrl, fullName, email, password, program, yearLevel, resumePdf, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "Failed to prepare database statement.";
        header("Location: ../register.php");
        exit();
    }

    $stmt->bind_param("ssssssss", $photoPath, $fullName, $email, $hashedPassword, $program, $yearLevel, $resumePath, $skills);
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Failed to register user. Please try again.";
        header("Location: ../register.php");
        exit();
    }

    // Success message
    $_SESSION['success'] = "Registration successful! Welcome, {$fullName}! You can now log in.";

    // Redirect user
    if (isset($_SESSION['redirect'])) {
        $location = $_SESSION['redirect'];
        unset($_SESSION['redirect']);
        header("Location: ../{$location}");
        exit();
    }
    header("Location: ../index.php");
    exit();
}
?>
