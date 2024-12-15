<?php
session_start();
require_once("./config.php");

function getCoordinates($address)
{
    $address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyBZYWIFhx9T2nK3MQByxOVxaqB0Yr14Pn0";

    $response = file_get_contents($url);
    $data = json_decode($response);

    if ($data->status == "OK") {
        $lat = $data->results[0]->geometry->location->lat;
        $lon = $data->results[0]->geometry->location->lng;
        return ['lat' => $lat, 'lon' => $lon];
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = $_POST['company_name'];
    $address = $_POST['address'];
    $contactPerson = $_POST['contact_person'];
    $contactNumber = $_POST['contact_number'];
    $internship = $_POST['internship'];
    $jobQualification = $_POST['job_qualification'];
    $aboutUs = $_POST['about_us'];

    if (empty($companyName) || empty($address) || empty($contactPerson) || empty($contactNumber) || empty($internship) || empty($jobQualification) || empty($aboutUs)) {
        echo "All fields are required.";
        exit();
    }

    $coordinates = getCoordinates($address);
    if ($coordinates === null) {
        echo "Unable to get coordinates for the address.";
        exit();
    }

    $lat = $coordinates['lat'];
    $lon = $coordinates['lon'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO internship (companyId, company_name, internship, address, contact_person, contact_number, job_qualification, about_us, lat, lon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssdd", $_SESSION['account']['id'], $companyName, $internship, $address, $contactPerson, $contactNumber, $jobQualification, $aboutUs, $lat, $lon);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>