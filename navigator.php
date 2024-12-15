<?php


$conn = new mysqli('localhost', 'root', 'admin', 'educ');
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['account'])) {
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
}
$conn->close();
?>
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
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/CCC Logo 1.png" alt="CCC Logo">
            <span class="ms-2">CCC - SkillLink</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php
                if (!isset($_SESSION["account"])) {
                    echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="company.php">Become Company</a></li>';
                } elseif (isset($_SESSION["account"]["aCompany"]) && $_SESSION["account"]["aCompany"] == "0") {
                    echo '<li class="nav-item"><a class="nav-link" href="find_internship.php">Find Internship</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="all_applied.php">Applied Intership</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                } elseif (isset($_SESSION["account"]["aCompany"]) && $_SESSION["account"]["aCompany"] == "1") {
                    echo '<li class="nav-item"><a class="nav-link" href="post_internship.php">Post Internship</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="all_internship.php">All Applied</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                }
                ?>
            </ul>

            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION["account"])) {
                    echo '<img src="images/icons8-user-96.png" alt="User Icon" id="userIcon" data-bs-toggle="modal" data-bs-target="#editAccountModal">';
                    echo '<img src="images/icons8-search-150.png" alt="Search Icon" data-bs-toggle="modal" data-bs-target="#filterModal">';
                } ?>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter by Location and Radius</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm" action="find_internship.php" method="GET">
                    <div class="form-group mt-3">
                        <label for="radius">Radius (miles)</label>
                        <input type="number" class="form-control" id="radius" name="radius" min="5" max="50"
                            placeholder="Enter radius (5-50)" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            document.getElementById('userLat').value = lat;
            document.getElementById('userLon').value = lon;
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
</script>

<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
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
                        <input type="file" class="form-control" id="photo-upload" name="photoUrl" accept="image/*">
                        <?php if (isset($photoUrl) && $photoUrl): ?>
                            <img src="<?= htmlspecialchars($photoUrl) ?>" alt="Profile Photo" class="mt-3"
                                style="max-width: 150px;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <?php if ($_SESSION['account']['aCompany'] == 1): ?>
                            <label for="companyName">Company Name*</label>
                            <input type="text" class="form-control" id="companyName" name="companyName"
                                value="<?= htmlspecialchars($companyName) ?>" required>
                        <?php else: ?>
                            <label for="fullName">Full Name*</label>
                            <input type="text" class="form-control" id="fullName" name="fullName"
                                value="<?= htmlspecialchars($fullName) ?>" required>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
