<?php
session_start();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<?php
require_once "./navigator.php";
?>
<div class="container">
    <form method="GET" action="/html/loginpagepostinternship.html" onsubmit="return validateForm()">
        <div class="form-section">
            <div class="profile-photo">
                <div class="photo-preview" id="photo-preview">
                    <span>Upload Photo</span>
                </div>
                <label class="upload-button">
                    <input type="file" id="photo-upload" accept="image/*" onchange="previewPhoto()">
                    Upload Your Photo
                </label>
            </div>

            <div class="form-group1">
                <label for="full-name">Company Name*</label>
                <input type="text" id="full-name" placeholder="Enter company name" required>
            </div>

            <div class="form-group1">
                <label for="email">Company email account*</label>
                <input type="email" id="email" placeholder="Enter company email" required>
            </div>

            <div class="form-group1">
                <label for="password">Password*</label>
                <input type="password" id="password" placeholder="Enter a password" required>
            </div>

            <div class="form-group1">
                <label for="confirm-password">Confirm Password*</label>
                <input type="password" id="confirm-password" placeholder="Confirm the password" required>
            </div>

            <div class="form-group1">
                <label for="company-website">Company Website*</label>
                <input type="url" id="company-website" placeholder="https://www.example.com" required>
            </div>

            <div class="form-group1">
                <label for="company-address">Company address</label>
                <input type="text" id="company-address" placeholder="Enter company address" required>
            </div>
    </form>

    <a href="/html/loginpagepostinternship.html" style="text-decoration: none; color: inherit;"></a>
    <button type="submit" class="submit-button">SIGN UP</button>
</div>

<script>
    function previewPhoto() {
        const fileInput = document.getElementById('photo-upload');
        const photoPreview = document.getElementById('photo-preview');

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                photoPreview.innerHTML = '';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Uploaded Photo';

                photoPreview.appendChild(img);
            };

            reader.readAsDataURL(fileInput.files[0]);
        } else {
            photoPreview.innerHTML = '<span>Upload Photo</span>';
        }
    }

    function validateForm() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }

    function navigateToNextPage(event) {
        event.preventDefault();
        if (validateForm()) {
            window.location.href = "/html/loginpagepostinternship.html";
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>