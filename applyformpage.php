<?php
session_start();

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

    .form-container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        text-align: center;
        color: #003580;
        margin-bottom: 30px;
    }

    .form-content {
        display: flex;
        flex-direction: column;
    }

    .form-fields {
        margin-bottom: 30px;
    }

    .form-fields label {
        font-size: 1rem;
        font-weight: bold;
        color: #003580;
    }

    .form-fields input {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }

    .form-fields input:focus {
        outline-color: #003580;
        border-color: #003580;
    }

    .resume-section,
    .skills-section,
    .photo-section {
        margin-bottom: 30px;
    }

    .resume-container,
    .skills-section,
    .photo-section {
        background-color: #f1f1f1;
        padding: 15px;
        border-radius: 5px;
    }

    .upload-button {
        background-color: #003580;
        color: white;
        padding: 10px 15px;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    .upload-button:hover {
        background-color: #002a60;
    }

    .fixed-button {
        width: 100%;
    }

    .resume-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .resume-item img {
        width: 24px;
        height: 24px;
        margin-right: 10px;
    }

    .resume-item .resume-info {
        flex-grow: 1;
    }

    .remove-btn {
        background-color: #ff4d4d;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        padding: 5px 10px;
        font-size: 1.1rem;
        margin-left: 10px;
    }

    .remove-btn:hover {
        background-color: #e60000;
    }

    .hidden {
        display: none;
    }
</style>

<?php
require_once "./navigator.php";
?>
<div class="form-container">
    <h2>Apply for Software Engineer</h2>
    <?php

    if (isset($_SESSION['account'])) {
        $user = $_SESSION['account'];
        $skills = json_decode($user['skills'], true);
        ?>

        <div class="form-content">
            <div class="form-fields">
                <label for="full-name">Full Name*</label>
                <input type="text" id="full-name" placeholder="Enter your full name"
                    value="<?php echo htmlspecialchars($user['fullName']); ?>" required>

                <label for="email">Email Address*</label>
                <input type="email" id="email" placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="phone">Mobile Phone Number*</label>
                <input type="tel" id="phone" placeholder="Enter your phone number"
                    value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
            </div>

            <div class="resume-section">
                <div class="resume-container">
                    <label>Resume</label>
                    <div id="resumePreview" class="resume-item hidden">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" alt="PDF icon">
                        <div class="resume-info">
                            <span id="resumeName">Resume.pdf</span>
                            <small id="resumeSize">19 KB</small>
                        </div>
                        <a href="#" id="resumeDownload" download>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/88/Download_icon_-_Font_Awesome.svg/120px-Download_icon_-_Font_Awesome.svg.png"
                                alt="Download" style="width: 24px; height: 24px;">
                        </a>
                        <button class="remove-btn" onclick="removeFile('resume')">×</button>
                    </div>
                    <button class="upload-button small-button"
                        onclick="document.getElementById('resumeUpload').click()">Upload Resume</button>
                    <input type="file" id="resumeUpload" class="hidden" accept=".pdf"
                        onchange="handleFileUpload(event, 'resume')">
                    <small>PDF only (2 MB)</small>
                </div>
            </div>

            <div class="skills-section">
                <label>Skills</label>
                <ul>
                    <?php if (!empty($skills)) {
                        foreach ($skills as $skill) { ?>
                            <li><?php echo htmlspecialchars($skill); ?></li>
                        <?php }
                    } else { ?>
                        <li>No skills added.</li>
                    <?php } ?>
                </ul>
            </div>

            <div class="photo-section">
                <label>Profile Photo</label>
                <?php if (!empty($user['photoUrl'])) { ?>
                    <img src="<?php echo htmlspecialchars($user['photoUrl']); ?>" alt="Profile Photo"
                        style="width: 100px; height: 100px;">
                <?php } else { ?>
                    <p>No photo uploaded.</p>
                <?php } ?>
            </div>
        </div>

        <?php
    } else {
        echo "User data not found.";
    }
    ?>
    <button class="upload-button fixed-button" onclick="submitApplication()">Review Application</button>
</div>

<script>
    function handleFileUpload(event, type) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(type + 'Preview').classList.remove('hidden');
                document.getElementById(type + 'Name').textContent = file.name;
                document.getElementById(type + 'Download').href = e.target.result;

                localStorage.setItem(type + 'Data', e.target.result);
                localStorage.setItem(type + 'Name', file.name);
            };
            reader.readAsDataURL(file);
        }
    }

    function submitApplication() {
        localStorage.setItem('fullName', document.getElementById('full-name').value);
        localStorage.setItem('email', document.getElementById('email').value);
        localStorage.setItem('phone', document.getElementById('phone').value);
        window.location.href = 'reviewapplypage.php?id=<?php echo $internshipId; ?>';
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>