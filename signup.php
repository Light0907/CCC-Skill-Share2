<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
	.login-container {
		margin: 50px auto;
		max-width: 500px;
		padding: 30px;
		border: 1px solid #ddd;
		border-radius: 10px;
		background-color: #fafafa;
	}

	.login-container h2 {
		text-align: center;
		margin-bottom: 20px;
		color: #003580;
	}

	.form-group1,
	.form-group2,
	.form-group3 {
		margin-bottom: 20px;
	}

	.login-container input,
	.login-container select,
	.login-container button {
		width: 100%;
		padding: 10px;
		font-size: 1rem;
		border-radius: 5px;
		border: 1px solid #ddd;
		margin-top: 5px;
	}

	.login-container input[type="file"] {
		padding: 5px;
		font-size: 0.9rem;
	}

	.login-container button {
		background-color: #003580;
		color: white;
		cursor: pointer;
		border: none;
	}

	.login-container button:hover {
		background-color: #002a60;
	}

	.login-container a {
		color: #003580;
		text-decoration: none;
	}

	.login-container a:hover {
		text-decoration: underline;
	}

	.file-preview {
		font-size: 0.9rem;
		margin-top: 5px;
		color: #555;
	}

	.skills-section label {
		display: block;
		font-size: 0.95rem;
		margin-bottom: 5px;
	}

	.upload-button {
		background-color: #003580;
		color: white;
		padding: 8px 15px;
		border-radius: 5px;
		cursor: pointer;
		text-align: center;
	}

	.upload-button:hover {
		background-color: #002a60;
	}
</style>

<?php
// require_once "./navigator.php";
?>

<div class="container login-container">
	<form action="database/signup.php" method="POST" enctype="multipart/form-data"
		onsubmit="return validateFormAndRedirect()">
		<h2>Sign Up</h2>

		<div class="form-section">
			<div class="profile-photo text-center mb-3">
				<div class="photo-preview" id="photo-preview">
					<span>Upload Photo</span>
				</div>
				<label class="upload-button">
					<input name="photoUrl" type="file" id="photo-upload" accept="image/jpeg" onchange="previewPhoto()" />
					Upload Your Photo
				</label>
			</div>

			<div class="form-group1">
				<label for="full-name">Full name*</label>
				<input type="text" id="full-name" name="fullName" placeholder="Enter your full name" required />
			</div>

			<div class="form-group1">
				<label for="email">CCC email account*</label>
				<input type="email" id="email" name="email" placeholder="Enter your email" required />
			</div>

			<div class="form-group1">
				<label for="password">Password*</label>
				<input type="password" id="password" name="password" placeholder="Enter your password" required />
			</div>

			<div class="form-group1">
				<label for="confirm-password">Confirm Password*</label>
				<input type="password" id="confirm-password" placeholder="Confirm your password" required />
			</div>

			<div class="form-group2">
				<label for="program">Program*</label>
				<select id="program" name="program" required onchange="updateSkills()">
					<option value="">Choose your Program</option>
					<option value="BSIT">Bachelor of Science in Information Technology</option>
					<option value="BSCS">Bachelor of Science in Computer Science</option>
					<option value="BSA">Bachelor of Science in Accountancy</option>
					<option value="BSAIS">Bachelor of Science in Accounting Information System</option>
				</select>
			</div>

			<div class="form-group2">
				<label for="year-level">Year Level*</label>
				<select id="year-level" name="yearLevel" required>
					<option value="">Choose Year level</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
				</select>
			</div>

			<div class="form-group3">
				<label>Resume (PDF only)*</label>
				<div class="upload-file">
					<input type="file" id="resume-upload" accept=".pdf" name="resumePdf" required
						onchange="showFileName('resume-upload', 'resume-preview')" />
				</div>
			</div>

			<fieldset class="form-group4" id="skills-section" name="skills">
				<legend>Skills*</legend>
				<!-- Skill options will be dynamically inserted here based on selected program -->
			</fieldset>

			<button type="submit" class="submit-button">SIGN UP</button>
		</div>
	</form>
</div>

<script>
	function previewPhoto() {
		const photoInput = document.getElementById("photo-upload");
		const photoPreview = document.getElementById("photo-preview");

		if (photoInput.files && photoInput.files[0]) {
			const reader = new FileReader();

			reader.onload = function (e) {
				photoPreview.innerHTML = `<img src="${e.target.result}" alt="Uploaded Photo" style="max-width: 100px; max-height: 100px; border-radius: 5px;">`;
			};

			reader.readAsDataURL(photoInput.files[0]);
		}
	}

	function showFileName(inputId, previewId) {
		const input = document.getElementById(inputId);
		const preview = document.getElementById(previewId);
		preview.textContent = input.files.length > 0 ? input.files[0].name : "No file chosen";
	}

	function validateForm() {
		const password = document.getElementById("password").value;
		const confirmPassword = document.getElementById("confirm-password").value;

		if (password !== confirmPassword) {
			alert("Passwords do not match.");
			return false;
		}
		return true;
	}

	function validateFormAndRedirect() {
		return validateForm();
	}

	function updateSkills() {
		const program = document.getElementById("program").value;
		const skillsSection = document.getElementById("skills-section");

		let skillsHtml = "";

		if (program === "BSIT") {
			skillsHtml = `
				<label><input type="checkbox" name="skills" value="networking" class="skill-checkbox"> Networking</label>
				<label><input type="checkbox" name="skills" value="database-management" class="skill-checkbox"> Database Management</label>
				<label><input type="checkbox" name="skills" value="web-development" class="skill-checkbox"> Web Development</label>
				<label><input type="checkbox" name="skills" value="cybersecurity" class="skill-checkbox"> Cybersecurity</label>
				<label><input type="checkbox" name="skills" value="programming" class="skill-checkbox"> Programming</label>
				<label><input type="checkbox" name="skills" value="data-structures" class="skill-checkbox"> Data Structures</label>
				<label><input type="checkbox" name="skills" value="it-support" class="skill-checkbox"> IT Support</label>
				<label><input type="checkbox" name="skills" value="cloud-computing" class="skill-checkbox"> Cloud Computing</label>
				<label><input type="checkbox" name="skills" value="other-skill" id="other-skill-checkbox" onclick="toggleOtherSkill()"> Other (Specify)</label>
				<input type="text" id="other-skill" placeholder="Specify your skill" disabled>`;

		} else if (program === "BSCS") {
			skillsHtml = `
				<label><input type="checkbox" name="skills" value="software-development"> Software Development</label>
				<label><input type="checkbox" name="skills" value="web-development"> Web Development</label>
				<label><input type="checkbox" name="skills" value="database-management"> Database Management</label>
				<label><input type="checkbox" name="skills" value="cloud-computing"> Cloud Computing</label>
				<label><input type="checkbox" name="skills" value="programming"> Programming</label>
				<label><input type="checkbox" name="skills" value="algorithm-design"> Algorithm Design</label>
				<label><input type="checkbox" name="skills" value="object-oriented-programming"> Object-Oriented Programming</label>
				<label><input type="checkbox" name="skills" value="mobile-app-development"> Mobile App Development</label>
				<label><input type="checkbox" name="skills" value="other-skill" id="other-skill-checkbox" onclick="toggleOtherSkill()"> Other (Specify)</label>
				<input type="text" id="other-skill" placeholder="Specify your skill" disabled>`
				;
		} else if (program === "BSA") {
			skillsHtml = `
				<label><input type="checkbox" name="skills" value="accounting-software"> Accounting Software</label>
				<label><input type="checkbox" name="skills" value="financial-reporting"> Financial Reporting</label>
				<label><input type="checkbox" name="skills" value="data-analysis"> Data Analysis</label>
				<label><input type="checkbox" name="skills" value="auditing"> Auditing</label>
				<label><input type="checkbox" name="skills" value="taxation"> Taxation</label>
				<label><input type="checkbox" name="skills" value="financial-modelling"> Financial Modelling</label>
				<label><input type="checkbox" name="skills" value="budgeting"> Budgeting</label>
				<label><input type="checkbox" name="skills" value="forensic-accounting"> Forensic Accounting</label>
				<label><input type="checkbox" name="skills" value="other-skill" id="other-skill-checkbox" onclick="toggleOtherSkill()"> Other (Specify)</label>
				<input type="text" id="other-skill" placeholder="Specify your skill" disabled>`
				;
		} else if (program === "BSAIS") {
			skillsHtml = `
				<label><input type="checkbox" name="skills" value="accounting-software"> Accounting Software</label>
				<label><input type="checkbox" name="skills" value="financial-reporting"> Financial Reporting</label>
				<label><input type="checkbox" name="skills" value="data-analysis"> Data Analysis</label>
				<label><input type="checkbox" name="skills" value="auditing"> Auditing</label>
				<label><input type="checkbox" name="skills" value="taxation"> Taxation</label>
				<label><input type="checkbox" name="skills" value="financial-modelling"> Financial Modelling</label>
				<label><input type="checkbox" name="skills" value="budgeting"> Budgeting</label>
				<label><input type="checkbox" name="skills" value="forensic-accounting"> Forensic Accounting</label>
				<label><input type="checkbox" name="skills" value="other-skill" id="other-skill-checkbox" onclick="toggleOtherSkill()"> Other (Specify)</label>
				<input type="text" id="other-skill" placeholder="Specify your skill" disabled>`
				;
		}

		skillsSection.innerHTML = skillsHtml;
	}

	function toggleOtherSkill() {
		const otherSkillCheckbox = document.getElementById("other-skill-checkbox");
		const otherSkillInput = document.getElementById("other-skill");

		if (otherSkillCheckbox.checked) {
			otherSkillInput.disabled = false;
		} else {
			otherSkillInput.disabled = true;
			otherSkillInput.value = "";
		}
	}

	window.onload = updateSkills;
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>