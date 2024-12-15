<?php
session_start();
require_once("./config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == "admin" && $password == "admin") {
        header("Location: ../dashboardadmin.php");
        exit;
    }

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
        header("Location: ../index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, fullName, email, password, program, yearLevel, skills, photoUrl, resumePdf, isCompany FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $fullName, $userEmail, $hashedPassword, $program, $yearLevel, $skillsJson, $photoUrl, $resumePdf, $aCompany);
    $stmt->fetch();
    $stmt->close();

    if ($userId && password_verify($password, $hashedPassword)) {
        $_SESSION['account'] = [
            'id' => $userId,
            'photoUrl' => $photoUrl,
            'resumePdf' => $resumePdf,
            'fullName' => $fullName,
            'email' => $userEmail,
            'program' => $program,
            'yearLevel' => $yearLevel,
            'aCompany' => "0",
            'skills' => json_decode($skillsJson, true),
        ];
        $_SESSION['success'] = "Welcome back, {$fullName}!";

        if (isset($_SESSION['redirect'])) {
            $location = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
            header("Location: ../{$location}");
        } else {
            header("Location: ../index.php");
        }
        exit();
    }

    $stmt = $conn->prepare("SELECT id, photoUrl, companyName, email, password, website, address FROM company WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($companyId, $companyPhoto, $companyName, $companyEmail, $companyHashedPassword, $website, $address);
    $stmt->fetch();
    $stmt->close();

    if ($companyId && password_verify($password, $companyHashedPassword)) {
        $_SESSION['account'] = [
            'id' => $companyId,
            'photoUrl' => $companyPhoto,
            'companyName' => $companyName,
            'email' => $companyEmail,
            'website' => $website,
            'address' => $address,
            'aCompany' => "1"
        ];
        $_SESSION['success'] = "Welcome back, {$companyName}!";

        if (isset($_SESSION['redirect'])) {
            $location = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
            header("Location: ../{$location}");
        } else {
            header("Location: ../index.php");
        }
        exit();
    }

    $_SESSION['error'] = "Invalid email or password.";
    header("Location: ../index.php");
    exit();
}
?>