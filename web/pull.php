<?php
require_once("passwords.php");
require_once("utils.php");

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    dolog('Failed to connect to DB ' . $conn->connect_error);
    die("Connection to DB failed");
}


// CORS config
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

// Get params
$eventid = $_GET['eventid'] ?? null;

if ($eventid) {
    $sql = "SELECT name, pilot1, pilot2, pilot3, pilot4, freq1, freq2, freq3, freq4, position1, position2, position3, position4 FROM races WHERE eventid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $eventid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Initialiser un tableau pour stocker les résultats
    $races = [];

    // Boucler à travers les résultats et les ajouter au tableau
    while ($row = $result->fetch_assoc()) {
        $races[] = [
            'name' => $row['name'],
            'pilot1' => $row['pilot1'],
            'pilot2' => $row['pilot2'],
            'pilot3' => $row['pilot3'],
            'pilot4' => $row['pilot4'],
            'freq1' => $row['freq1'],
            'freq2' => $row['freq2'],
            'freq3' => $row['freq3'],
            'freq4' => $row['freq4'],
            'position1' => $row['position1'],
            'position2' => $row['position2'],
            'position3' => $row['position3'],
            'position4' => $row['position4'],
        ];
    }

    // Send results as JSON
    echo json_encode($races);
} else {
    // If params are missing
    http_response_code(400);  // Bad Request
    echo json_encode(['error' => 'eventid is required']);
}

$conn->close();
