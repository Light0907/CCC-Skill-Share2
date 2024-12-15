<?php
require_once("./config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); // Start the session to handle error/success messages

    // Collect form inputs
    $photoUrl = $_FILES['photoUrl'];
    $company_name = trim($_POST['company_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $company_website = trim($_POST['company_website']);
    $company_address = trim($_POST['company_address']);

    // Initialize an array to store errors
    $errors = [];

    // === VALIDATIONS ===

    // 1. Validate Company Name
    if (empty($company_name)) {
        $errors[] = "Company name is required.";
    }

    // 2. Validate Email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // 3. Validate Password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // 4. Validate Company Website
    if (empty($company_website)) {
        $errors[] = "Company website is required.";
    } elseif (!filter_var($company_website, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid website URL.";
    }

    // 5. Validate Company Address
    if (empty($company_address)) {
        $errors[] = "Company address is required.";
    }

    // 6. Validate Photo Upload
    if (empty($photoUrl['name'])) {
        $errors[] = "Profile photo is required.";
    } elseif ($photoUrl['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading profile photo. Error code: " . $photoUrl['error'];
    } else {
        $allowedPhotoExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $photoExtension = pathinfo($photoUrl['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($photoExtension), $allowedPhotoExtensions)) {
            $errors[] = "Profile photo must be a JPG, JPEG, PNG, or GIF file.";
        }

        // Check file size (optional, e.g., 2MB max)
        if ($photoUrl['size'] > 2 * 1024 * 1024) {
            $errors[] = "Profile photo must not exceed 2MB in size.";
        }
    }

    // === END VALIDATIONS ===

    // If there are validation errors, store them in the session and redirect
    if (!empty($errors)) {
        $_SESSION['error'] = implode(". ", $errors); // Use period and space instead of <br>
        header("Location: ../register.php");
        exit();
    }

    // Save profile photo to the uploads directory
    $photoPath = '../uploads/photos/' . uniqid() . '_' . basename($photoUrl['name']);
    if (!move_uploaded_file($photoUrl['tmp_name'], $photoPath)) {
        $_SESSION['error'] = "Failed to upload profile photo.";
        header("Location: ../register.php");
        exit();
    }

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Establish a database connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
        header("Location: ../register.php");
        exit();
    }

    // Prepare the SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO company (photoUrl, companyName, email, password, website, address) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "Failed to prepare database statement.";
        header("Location: ../register.php");
        exit();
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("ssssss", $photoPath, $company_name, $email, $hashedPassword, $company_website, $company_address);
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Failed to register company. Please try again.";
        header("Location: ../register.php");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Set success message and redirect to the index page
    $_SESSION['success'] = "Registration successful! Welcome, {$company_name}!";
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
