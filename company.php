<?php
session_start();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<?php
require_once "./navigator.php";
?>
<div class="container mt-4">
    <form method="POST" action="database/company_signup.php" onsubmit="return validateForm()"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="profile-photo text-center">
                        <div class="photo-preview" id="photo-preview">
                            <span>Upload Photo</span>
                        </div>
                        <label class="btn btn-outline-primary mt-2">
                            <input type="file" name="photoUrl" id="photo-upload" accept="image/*"
                                onchange="previewPhoto()" hidden>
                            Upload Your Photo
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="full-name">Company Name*</label>
                    <input type="text" id="full-name" name="company_name" class="form-control"
                        placeholder="Enter company name" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Company Email Account*</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter company email"
                        required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password*</label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Enter a password" required>
                </div>

                <div class="form-group mb-3">
                    <label for="confirm-password">Confirm Password*</label>
                    <input type="password" id="confirm-password" name="confirm_password" class="form-control"
                        placeholder="Confirm the password" required>
                </div>

                <div class="form-group mb-3">
                    <label for="company-website">Company Website*</label>
                    <input type="url" id="company-website" name="company_website" class="form-control"
                        placeholder="https://www.example.com" required>
                </div>

                <div class="form-group mb-3">
                    <label for="company-address">Company Address</label>
                    <input type="text" id="company-address" name="company_address" class="form-control"
                        placeholder="Enter company address" required>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
        </div>
    </form>
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
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';
                img.style.borderRadius = '8px';

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
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>