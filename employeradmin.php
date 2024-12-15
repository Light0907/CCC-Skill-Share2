<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/css/employeradmin.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <?php
    require_once "admin_navigation.php";
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar">
            <h1>Employers Management</h1>
        </div>

        <!-- Pending Employers Section -->
        <div class="pending-section">
            <h2>Pending Employers for Approval</h2>
            <div class="search-bar">
                <input type="text" id="pendingSearchInput" placeholder="Search pending employers..."
                    onkeyup="filterPendingEmployers()">
            </div>
            <div class="table" id="pendingEmployersTable">
                <div class="row header">
                    <span>Company Name</span>
                    <span>Email</span>
                    <span>Actions</span>
                </div>
                <div class="row">
                    <span>PendingTech</span>
                    <span>pending@techcorp.com</span>
                    <span>
                        <button class="approve" onclick="approveEmployer(this)">Approve</button>
                        <button class="reject" onclick="rejectEmployer(this)">Reject</button>
                    </span>
                </div>
            </div>
        </div>

        <!-- Registered Employers Section -->
        <div class="employer-section">
            <h2>Registered Employers</h2>
            <div class="search-bar">
                <input type="text" id="registeredSearchInput" placeholder="Search registered employers..."
                    onkeyup="filterRegisteredEmployers()">
            </div>
            <div class="table" id="employerTable">
                <div class="row header">
                    <span>Company Name</span>
                    <span>Email</span>
                    <span>Active Posts</span>
                    <span>Status</span>
                    <span>Actions</span>
                </div>
                <div class="row">
                    <span>TechCorp</span>
                    <span>hr@techcorp.com</span>
                    <span>5</span>
                    <span>Active</span>
                    <span>
                        <button class="view">View</button>
                        <button class="deactivate">Deactivate</button>
                    </span>
                </div>
            </div>
        </div>
    </div>


    <script src="employers.js"></script>

</body>

</html>