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
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .review-container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .review-container h2 {
        text-align: center;
        color: #003580;
        margin-bottom: 30px;
    }

    .review-item {
        margin-bottom: 20px;
    }

    .review-item strong {
        font-size: 1.1rem;
        color: #003580;
    }

    .review-item span {
        font-size: 1rem;
        color: #333;
    }

    .checkbox-container {
        margin: 20px 0;
    }

    .checkbox-label {
        font-size: 1rem;
        color: #003580;
    }

    .pdf-button {
        background-color: #003580;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    .pdf-button:hover {
        background-color: #002a60;
    }

    .submit-button {
        background-color: #003580;
        color: white;
        padding: 12px 20px;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 30px;
        transition: background-color 0.3s;
    }

    .submit-button:hover {
        background-color: #002a60;
    }

    .pdf-viewer {
        display: none;
        margin-top: 20px;
        text-align: center;
    }

    .pdf-viewer iframe {
        width: 100%;
        height: 500px;
        border: 1px solid #ccc;
    }

    .close-viewer {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ff4d4d;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        padding: 8px;
        font-size: 1.2rem;
    }

    .close-viewer:hover {
        background-color: #e60000;
    }
</style>

<?php
require_once "./navigator.php";
?>
<div class="review-container">
    <h2>Review Your Application</h2>

    <div class="review-item">
        <strong>Full Name:</strong> <span id="fullName"></span>
    </div>
    <div class="review-item">
        <strong>Email:</strong> <span id="email"></span>
    </div>
    <div class="review-item">
        <strong>Phone Number:</strong> <span id="phone"></span>
    </div>

    <div class="review-item">
        <strong>Resume:</strong>
        <span id="resumeLink"></span>
        <button id="resumeViewButton" class="pdf-button" onclick="viewPDF('resume')">View Resume</button>
        <div class="pdf-viewer" id="resumeViewerContainer">
            <button class="close-viewer" onclick="closeViewer('resume')">X</button>
            <iframe id="resumeViewer" src="" frameborder="0"></iframe>
        </div>
    </div>

    <div class="checkbox-container">
        <input type="checkbox" id="termsCheckbox">
        <label for="termsCheckbox" class="checkbox-label">I agree to the terms and conditions</label>
    </div>
    <form action="database/add_applied.php" method="POST">
        <input type="hidden" name="internshipId" value="<?php echo intval($_GET['id']); ?>">
        <button type="submit" class="submit-button">Submit Application</button>
    </form>
</div>

<script>
    document.getElementById('fullName').innerText = localStorage.getItem('fullName') || 'Not provided';
    document.getElementById('email').innerText = localStorage.getItem('email') || 'Not provided';
    document.getElementById('phone').innerText = localStorage.getItem('phone') || 'Not provided';

    const resumeData = localStorage.getItem('resumeData');
    const resumeName = localStorage.getItem('resumeName');

    if (resumeData) {
        document.getElementById('resumeLink').innerHTML = `<a href="${resumeData}" download="${resumeName}">Download Resume</a>`;
    } else {
        document.getElementById('resumeLink').innerText = 'No resume uploaded';
    }

    function viewPDF(type) {
        if (type === 'resume' && resumeData) {
            const viewer = document.getElementById('resumeViewer');
            viewer.src = resumeData;
            document.getElementById('resumeViewerContainer').style.display = 'block';
        } else {
            alert('No document uploaded');
        }
    }

    function closeViewer(type) {
        if (type === 'resume') {
            document.getElementById('resumeViewerContainer').style.display = 'none';
        }
    }

    function submitApplication() {
        if (!document.getElementById('termsCheckbox').checked) {
            alert('You must agree to the terms and conditions before submitting your application.');
            return;
        }
        localStorage.clear();
        alert('Your application has been submitted successfully!');
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>