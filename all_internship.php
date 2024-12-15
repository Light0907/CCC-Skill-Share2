<?php
session_start();

if (!isset($_SESSION['account']['id'])) {
    echo json_encode(["error" => "You must be logged in as a company"]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_applied_users') {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'admin');
    define('DB_NAME', 'educ');

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $companyId = $_SESSION['account']['id'];

    $sql = "
        SELECT u.id AS user_id, u.fullName, u.email, u.photoUrl, u.yearLevel, u.program
        FROM users u
        JOIN appliedTo a ON u.id = a.userId
        JOIN internship i ON a.internshipId = i.id
        WHERE i.companyId = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $companyId);
    $stmt->execute();
    $result = $stmt->get_result();

    $applicants = [];
    while ($row = $result->fetch_assoc()) {
        $applicants[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($applicants);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for Your Internships</title>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.1.7/dist/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.1.7/dist/js/tabulator.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            padding: 20px;
            color: #0b1f53;
        }

        #applicant-table {
            margin: 30px auto;
            max-width: 1200px;
        }

        .tabulator {
            background-color: #0b1f53;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .tabulator .tabulator-header {
            background-color: #0b1f53;
            color: white;
            font-weight: bold;
        }

        .tabulator .tabulator-cell {
            color: white;
        }

        .tabulator .tabulator-row {
            border-bottom: 1px solid white;
        }

        .tabulator .tabulator-row:hover {
            background-color: #1c3d7c;
        }

        .loading-message {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #0b1f53;
        }

        .error-message {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: red;
        }
    </style>
</head>

<body>
    <h1>Applicants for Your Internships</h1>

    <div id="loading" class="loading-message">
        Loading applicants, please wait...
    </div>

    <div id="applicant-table"></div>

    <div id="error" class="error-message" style="display: none;">
        Failed to load applicants. Please try again later.
    </div>

    <script>
        fetch('?action=get_applied_users')
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';

                if (data.error) {
                    document.getElementById('error').style.display = 'block';
                    return;
                }

                if (data.length === 0) {
                    document.getElementById('error').style.display = 'block';
                    document.getElementById('error').textContent = 'No users have applied to your internships yet.';
                    return;
                }

                new Tabulator("#applicant-table", {
                    data: data,
                    layout: "fitColumns",
                    responsiveLayout: "hide",
                    columns: [
                        { title: "Full Name", field: "fullName", width: 200 },
                        { title: "Email", field: "email", width: 250 },
                        { title: "Year Level", field: "yearLevel", width: 100 },
                        { title: "Program", field: "program", width: 150 },
                        {
                            title: "Profile Picture", field: "photoUrl", width: 100,
                            formatter: "image", formatterParams: {
                                height: 50,
                                width: 50,
                            }
                        },
                    ],
                    rowClick: function (e, row) {
                        alert("You clicked on: " + row.getData().fullName);
                    }
                });
            })
            .catch(error => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                console.error("Error fetching data:", error);
            });
    </script>
</body>

</html>