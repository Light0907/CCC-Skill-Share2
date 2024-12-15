<?php
session_start();
require_once "database/config.php";

if (!isset($_SESSION["account"])) {
    $_SESSION['redirect'] = "find_internship.php";
    header("Location: ./login.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid internship ID.";
    header("Location: index.php");
    exit;
}

$internshipId = intval($_GET['id']);
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    $_SESSION['error'] = "Connection failed: " . $conn->connect_error;
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT u.email, company_name, address, contact_person, contact_number, job_qualification, about_us, internship
FROM internship i
JOIN users u ON u.id = i.companyId WHERE i.id = ?");

$stmt->bind_param("i", $internshipId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Internship not found.";
    header("Location: index.php");
    exit;
}

$internship = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 30px auto;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #003580;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group strong {
        color: #003580;
        font-size: 1.1rem;
    }

    .form-group p {
        font-size: 1rem;
        color: #555;
        margin: 5px 0;
    }

    .apply-button {
        background-color: #003580;
        color: white;
        padding: 10px 15px;
        font-size: 1rem;
        width: 100%;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .apply-button:hover {
        background-color: #002a60;
    }
</style>

<?php require_once "./navigator.php"; ?>

<div class="container">
    <h2>Job Application Details</h2>
    <div class="form-group">
        <strong>Company Name:</strong>
        <p id="companyName"><?php echo htmlspecialchars($internship['company_name']); ?></p>
    </div>
    <div class="form-group">
        <strong>Internship Name:</strong>
        <p id="internshipName"><?php echo htmlspecialchars($internship['internship']); ?></p>
    </div>
    <div class="form-group">
        <strong>Address:</strong>
        <p id="address"><?php echo htmlspecialchars($internship['address']); ?></p>
    </div>
    <div class="form-group">
        <strong>Contact Person:</strong>
        <p id="contactPerson"><?php echo htmlspecialchars($internship['contact_person']); ?></p>
    </div>
    <div class="form-group">
        <strong>Contact Number:</strong>
        <p id="contactNumber"><?php echo htmlspecialchars($internship['contact_number']); ?></p>
    </div>
    <div class="form-group">
        <strong>Job Qualification:</strong>
        <p id="jobQualification"><?php echo htmlspecialchars($internship['job_qualification']); ?></p>
    </div>
    <div class="form-group">
        <strong>About Us:</strong>
        <p id="aboutUs"><?php echo htmlspecialchars($internship['about_us']); ?></p>
    </div>
    <div class="form-group">
        <a href="/applyformpage.php?id=<?php echo $internshipId; ?>" style="text-decoration: none; color: inherit;">
            <button class="apply-button">Apply Now</button>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>