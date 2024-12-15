<?php
session_start();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<?php
require_once "./navigator.php";
if (!isset($_SESSION["account"])) {
	$_SESSION['redirect'] = "post_internship.php";
	require_once "./login.php";
} else {
	?>
	<div class="container mt-4">
		<form action="database/post_intern.php" method="POST">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group mb-3">
						<label for="companyName">Company Name*</label>
						<input type="text" id="companyName" name="company_name" class="form-control"
							placeholder="Enter company name" required />
					</div>
					<div class="form-group mb-3">
						<label for="internship">Internship Name*</label>
						<input type="text" id="internship" name="internship" class="form-control"
							placeholder="Enter internship name" required />
					</div>

					<div class="form-group mb-3">
						<label for="address">Address*</label>
						<input type="text" id="address" name="address" class="form-control" placeholder="Enter address"
							required />
						<a href="#" id="viewLocation" target="_blank"
							style="display:none; margin-left: 10px; color: #003580;">View on Map</a>
						<small id="validationError" style="color: red; display: none;">Invalid address. Please check the
							input.</small>
					</div>

					<div class="form-group mb-3">
						<label for="contact-person">Contact Person*</label>
						<input type="text" id="contact-person" name="contact_person" class="form-control"
							placeholder="Enter contact person name" required />
					</div>

					<div class="form-group mb-3">
						<label for="contact-number">Contact Number*</label>
						<input type="text" id="contact-number" name="contact_number" class="form-control"
							placeholder="Enter contact number" required />
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group mb-3">
						<label for="jobQualification">Job Qualification*</label>
						<textarea id="jobQualification" name="job_qualification" class="form-control"
							placeholder="Enter job qualification" required></textarea>
					</div>

					<div class="form-group mb-3">
						<label for="aboutUs">About Us*</label>
						<textarea id="aboutUs" name="about_us" class="form-control"
							placeholder="Enter details about your company" required></textarea>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-between mt-4">
				<button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</form>
	</div>
<?php } ?>

<script>
	function goBack() {
		history.back();
	}

	const addressInput = document.getElementById('address');
	const viewLocationLink = document.getElementById('viewLocation');
	const validationError = document.getElementById('validationError');

	const apiKey = 'AIzaSyBZYWIFhx9T2nK3MQByxOVxaqB0Yr14Pn0';

	addressInput.addEventListener('input', async function () {
		const address = addressInput.value.trim();

		if (address) {
			const geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;

			try {
				const response = await fetch(geocodeUrl);
				const data = await response.json();

				if (data.status === "OK") {
					const googleMapsUrl = `https://www.google.com/maps?q=${encodeURIComponent(address)}`;
					viewLocationLink.href = googleMapsUrl;
					viewLocationLink.style.display = 'inline';
					validationError.style.display = 'none';
				} else {
					viewLocationLink.style.display = 'none';
					validationError.style.display = 'block';
					validationError.textContent = "Invalid address. Please check the input.";
				}
			} catch (error) {
				console.error("Error validating address:", error);
				validationError.style.display = 'block';
				validationError.textContent = "Unable to validate address. Please try again.";
			}
		} else {
			viewLocationLink.style.display = 'none';
			validationError.style.display = 'none';
		}
	});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>