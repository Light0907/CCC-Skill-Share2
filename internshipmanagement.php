<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admin');
define('DB_NAME', 'educ');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pendingSql = "SELECT id, internship, company_name, created_at FROM internship WHERE isApprove = 0 AND isRejected = 0";
$pendingResult = $conn->query($pendingSql);

$approvedSql = "SELECT id, internship, company_name, applicants_count, isApprove FROM internship WHERE isApprove = 1";
$approvedResult = $conn->query($approvedSql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/css/internshipmanagment.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    require_once "admin_navigation.php";
    ?>

    <div class="main-content">
        <div class="navbar">
            <h1>Internship Management</h1>
        </div>

        <div class="pending-section">
            <h2>Pending Internships for Approval</h2>
            <div class="search-bar">
                <input type="text" id="pendingSearchInput" placeholder="Search pending internships..."
                    onkeyup="filterPendingInternships()">
            </div>
            <div class="table" id="pendingInternshipsTable">
                <div class="row header">
                    <span>Position</span>
                    <span>Company</span>
                    <span>Posted On</span>
                    <span>Actions</span>
                </div>
                <?php if ($pendingResult->num_rows > 0): ?>
                    <?php while ($internship = $pendingResult->fetch_assoc()): ?>
                        <div class="row">
                            <span><?php echo htmlspecialchars($internship['internship']); ?></span>
                            <span><?php echo htmlspecialchars($internship['company_name']); ?></span>
                            <span><?php echo htmlspecialchars($internship['created_at']); ?></span>
                            <span>
                                <button class="approve"
                                    onclick="approveInternship(<?php echo $internship['id']; ?>)">Approve</button>
                                <button class="reject"
                                    onclick="rejectInternship(<?php echo $internship['id']; ?>)">Reject</button>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="row">
                        <span colspan="4">No pending internships.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="approved-section">
            <h2>Approved Internships</h2>
            <div class="search-bar">
                <input type="text" id="approvedSearchInput" placeholder="Search approved internships..."
                    onkeyup="filterApprovedInternships()">
            </div>
            <div class="table" id="approvedInternshipsTable">
                <div class="row header">
                    <span>Position</span>
                    <span>Company</span>
                    <span>Status</span>
                    <span>Actions</span>
                </div>
                <?php if ($approvedResult->num_rows > 0): ?>
                    <?php while ($internship = $approvedResult->fetch_assoc()): ?>
                        <div class="row">
                            <span><?php echo htmlspecialchars($internship['internship']); ?></span>
                            <span><?php echo htmlspecialchars($internship['company_name']); ?></span>
                            <span><?php echo $internship['isApprove'] ? 'Active' : 'Inactive'; ?></span>
                            <span>
                                <button class="view">View</button>
                                <button class="deactivate"
                                    onclick="deactivateInternship(<?php echo $internship['id']; ?>)">Deactivate</button>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="row">
                        <span colspan="5">No approved internships.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function filterPendingInternships() {
            const input = document.getElementById('pendingSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#pendingInternshipsTable .row:not(.header)');
            rows.forEach(row => {
                const position = row.children[0].textContent.toLowerCase();
                const company = row.children[1].textContent.toLowerCase();
                row.style.display = position.includes(input) || company.includes(input) ? '' : 'none';
            });
        }

        function filterApprovedInternships() {
            const input = document.getElementById('approvedSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#approvedInternshipsTable .row:not(.header)');
            rows.forEach(row => {
                const position = row.children[0].textContent.toLowerCase();
                const company = row.children[1].textContent.toLowerCase();
                row.style.display = position.includes(input) || company.includes(input) ? '' : 'none';
            });
        }

        function approveInternship(id) {
            if (confirm("Are you sure you want to approve this internship?")) {
                $.ajax({
                    url: 'database/approve_internship.php',
                    type: 'POST',
                    data: { internshipId: id },
                    success: function (response) {
                        if (response === 'success') {
                            alert('Internship approved!');
                            $('#internshipRow-' + id).fadeOut();
                        } else {
                            alert('Failed to approve the internship. Please try again.');
                        }
                        window.location.reload();
                    },
                    error: function () {
                        alert('Error occurred while processing the request.');
                    }
                });
            }
        }

        function rejectInternship(id) {
            if (confirm("Are you sure you want to reject this internship?")) {
                $.ajax({
                    url: 'database/reject_internship.php',
                    type: 'POST',
                    data: { internshipId: id },
                    success: function (response) {
                        if (response === 'success') {
                            alert('Internship reject!');
                            $('#internshipRow-' + id).fadeOut();
                        } else {
                            alert('Failed to reject the internship. Please try again.');
                        }
                        window.location.reload();
                    },
                    error: function () {
                        alert('Error occurred while processing the request.');
                    }
                });
            }
        }

        function deactivateInternship(id) {
            if (confirm("Are you sure you want to deactivate this internship?")) {
                $.ajax({
                    url: 'database/deactivate_internship.php',
                    type: 'POST',
                    data: { internshipId: id },
                    success: function (response) {
                        if (response === 'success') {
                            alert('Internship deactivate!');
                            $('#internshipRow-' + id).fadeOut();
                        } else {
                            alert('Failed to deactivate the internship. Please try again.');
                        }
                        window.location.reload();
                    },
                    error: function () {
                        alert('Error occurred while processing the request.');
                    }
                });
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>