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
    // Check event type
    $sql = "SELECT type FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $eventid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $eventtype = $result->fetch_assoc()['type'];
    switch ($eventtype) {
        case "qualifier":
            $sql = "SELECT pilot, position, extra FROM ranks WHERE eventid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $eventid);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $ranks = [];
            while ($row = $result->fetch_assoc()) {
                // Select laps for this pilot
                $sql_laps = "SELECT laps FROM laps WHERE eventid = ? and pilot = ?";
                $stmt_laps = $conn->prepare($sql_laps);
                $stmt_laps->bind_param("ss", $eventid, $row['pilot']);
                $stmt_laps->execute();
                $result_laps = $stmt_laps->get_result();
                $stmt_laps->close();

                $laps = json_decode($result_laps->fetch_assoc()['laps']);

                $ranks[] = [
                    'pilot' => $row['pilot'],
                    'position' => $row['position'],
                    'extra' => $row['extra'],
                    'laps' => $laps,
                ];
            }
            // Send results as JSON
            echo json_encode($ranks);
        break;
        default:
            $sql = "SELECT name, pilot1, pilot2, pilot3, pilot4, freq1, freq2, freq3, freq4, position1, position2, position3, position4 FROM races WHERE eventid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $eventid);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $races = [];
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
        break;
    }
} else {
    // If params are missing
    http_response_code(400);  // Bad Request
    echo json_encode(['error' => 'eventid is required']);
}

$conn->close();
