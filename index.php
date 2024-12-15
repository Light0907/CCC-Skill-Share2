<?php
session_start();
require_once("modals/modals.php");
require_once("modals/service_modal.php");

if (isset($_SESSION['success'])) {
    echo "<script>showSuccessModal('{$_SESSION['success']}');</script>";
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    echo "<script>showErrorModal('{$_SESSION['error']}');</script>";
    unset($_SESSION['error']);
}
unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .navbar {
        background-color: #003580;
    }

    .navbar a {
        color: white !important;
        text-decoration: none !important;
        margin-right: 15px;
    }

    .navbar-brand img {
        height: 50px;
    }

    .navbar img {
        height: 30px;
        margin-left: 10px;
    }
</style>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CCC - SkillLink</title>
    <!-- <link rel="stylesheet" href="/css/landingpage.css" /> -->
    <link rel="stylesheet" href="/css/modal.css" />
</head>

<body>
    <?php
    require_once "./navigator.php";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>