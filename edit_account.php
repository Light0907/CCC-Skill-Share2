<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admin');
define('DB_NAME', 'educ');


if (!isset($_SESSION['account'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    header("Location: index.php");
    exit();
}

if ($_SESSION['account']['aCompany'] == 1) {
    $companyId = $_SESSION['account']['id'];
    $stmt = $conn->prepare("SELECT photoUrl, companyName, email, website, address FROM company WHERE id = ?");
    $stmt->bind_param("i", $companyId);
    $stmt->execute();
    $stmt->bind_result($photoUrl, $companyName, $email, $website, $address);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newCompanyName = $_POST['companyName'];
        $newEmail = $_POST['email'];
        $newWebsite = $_POST['website'];
        $newAddress = $_POST['address'];
        $newPhotoUrl = $_POST['photoUrl'];
        $updateStmt = $conn->prepare("UPDATE company SET companyName = ?, email = ?, website = ?, address = ?, photoUrl = ? WHERE id = ?");
        $updateStmt->bind_param("sssssi", $newCompanyName, $newEmail, $newWebsite, $newAddress, $newPhotoUrl, $companyId);
        $updateStmt->execute();
        $updateStmt->close();

        $_SESSION['success'] = "Your company information has been updated!";
        header("Location: edit_account.php");
        exit();
    }
} else {
    $userId = $_SESSION['account']['id'];
    $stmt = $conn->prepare("SELECT photoUrl, fullName, email, program, yearLevel, resumePdf FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($photoUrl, $fullName, $email, $program, $yearLevel, $resumePdf);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newFullName = $_POST['fullName'];
        $newEmail = $_POST['email'];
        $newProgram = $_POST['program'];
        $newYearLevel = $_POST['yearLevel'];

        $updateStmt = $conn->prepare("UPDATE users SET fullName = ?, email = ?, program = ?, yearLevel = ? WHERE id = ?");
        $updateStmt->bind_param("ssssi", $newFullName, $newEmail, $newProgram, $newYearLevel, $userId);
        $updateStmt->execute();
        $updateStmt->close();

        $_SESSION['success'] = "Your profile information has been updated!";
        header("Location: edit_account.php");
        exit();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once "./navigator.php"; ?>

    <div class="container mt-5">
        <h2>Edit Account</h2>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal">
            Edit Account
        </button>

        <!-- Modal -->
        <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="edit_account.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="photo-upload">Upload Photo</label>
                                <input type="file" class="form-control" id="photo-upload" name="photoUrl"
                                    accept="image/*">
                                <?php if ($photoUrl): ?>
                                    <img src="<?= $photoUrl ?>" alt="Profile Photo" class="mt-3" style="max-width: 150px;">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <?php if ($_SESSION['account']['aCompany'] == 1): ?>
                                    <label for="companyName">Company Name*</label>
                                    <input type="text" class="form-control" id="companyName" name="companyName"
                                        value="<?= $companyName ?>" required>
                                <?php else: ?>
                                    <label for="fullName">Full Name*</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName"
                                        value="<?= $fullName ?>" required>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="email">Email*</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>"
                                    required>
                            </div>

                            <?php if ($_SESSION['account']['aCompany'] == 1): ?>
                                <div class="form-group">
                                    <label for="website">Company Website*</label>
                                    <input type="url" class="form-control" id="website" name="website"
                                        value="<?= $website ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="address">Company Address*</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="<?= $address ?>" required>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label for="program">Program*</label>
                                    <input type="text" class="form-control" id="program" name="program"
                                        value="<?= $program ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="yearLevel">Year Level*</label>
                                    <input type="text" class="form-control" id="yearLevel" name="yearLevel"
                                        value="<?= $yearLevel ?>" required>
                                </div>
                            <?php endif; ?>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>