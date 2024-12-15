<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="/css/dashboardadmin.css" />
	<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;500;700&display=swap" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@400;700&display=swap" rel="stylesheet" />
</head>

<body>
	<?php
	require_once "admin_navigation.php";
	require_once "database/config.php";

	$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$totalStudents = $conn->query("SELECT COUNT(*) AS count FROM users WHERE isCompany = 0")->fetch_assoc()['count'];
	$activeInternships = $conn->query("SELECT COUNT(*) AS count FROM internship WHERE isApprove = 1 AND isDeactivated = 0")->fetch_assoc()['count'];
	$applicationsSubmitted = $conn->query("SELECT COUNT(*) AS count FROM appliedTo")->fetch_assoc()['count'];

	$conn->close();
	?>

	<div class="main-content">
		<div class="navbar">
			<h1>Admin Dashboard</h1>
		</div>

		<div class="dashboard">
			<div class="overview">
				<h2>Overview</h2>
				<div class="stats">
					<div class="stat">
						<h3>Total Students</h3>
						<p><?php echo $totalStudents; ?></p>
					</div>

					<div class="stat">
						<h3>Active Internships</h3>
						<p><?php echo $activeInternships; ?></p>
					</div>
					<div class="stat">
						<h3>Applications Submitted</h3>
						<p><?php echo $applicationsSubmitted; ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>