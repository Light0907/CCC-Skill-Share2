<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'get_applied_internships') {
    if (!isset($_SESSION['account']['id'])) {
        echo json_encode(["error" => "User not logged in"]);
        exit;
    }

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'admin');
    define('DB_NAME', 'educ');

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION['account']['id'];

    $sql = "
        SELECT a.id AS application_id, i.company_name, i.internship, i.address, i.contact_person, i.contact_number
        FROM appliedTo a
        JOIN internship i ON a.internshipId = i.id
        WHERE a.userId = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $internships = [];
    while ($row = $result->fetch_assoc()) {
        $internships[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($internships);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applied Internships</title>
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

        #applied-internships-table {
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

        .tabulator .tabulator-header .tabulator-col {
            padding: 10px;
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
    <h1>Your Applied Internships</h1>

    <div id="loading" class="loading-message">
        Loading internships, please wait...
    </div>

    <div id="applied-internships-table"></div>

    <div id="error" class="error-message" style="display: none;">
        Failed to load applied internships. Please try again later.
    </div>

    <script>
        fetch('?action=get_applied_internships')
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';

                if (data.error) {
                    document.getElementById('error').style.display = 'block';
                    return;
                }

                if (data.length === 0) {
                    document.getElementById('error').style.display = 'block';
                    document.getElementById('error').textContent = 'You have not applied to any internships yet.';
                    return;
                }

                new Tabulator("#applied-internships-table", {
                    data: data,
                    layout: "fitColumns",
                    responsiveLayout: "hide",
                    columns: [
                        { title: "Company Name", field: "company_name", width: 200 },
                        { title: "Internship", field: "internship", width: 200 },
                        { title: "Address", field: "address", width: 300 },
                        { title: "Contact Person", field: "contact_person", width: 200 },
                        { title: "Contact Number", field: "contact_number", width: 150 },
                    ],
                    rowClick: function (e, row) {
                        alert("You clicked on: " + row.getData().company_name);
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