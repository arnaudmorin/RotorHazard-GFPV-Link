<?php
require_once("passwords.php");
require_once("utils.php");

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    dolog('', 'Failed to connect to DB ' . $conn->connect_error);
    die("Connection to DB failed");
}


// CORS config
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

// Get params
$eventid = $_GET['eventid'] ?? null;

if ($eventid) {
    // Collect heats / freqs
    $sql = "SELECT id, name, pilot1, pilot2, pilot3, pilot4, freq1, freq2, freq3, freq4 FROM heats WHERE eventid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $eventid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $heats = [];
    $heats_by_id = [];  # I need that for laps later
    while ($row = $result->fetch_assoc()) {
        // Select positions for this heat
        $sql = "SELECT id, pilot, position FROM rounds WHERE eventid = ? AND heat_id = ? ORDER BY id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $eventid, $row['id']);
        $stmt->execute();
        $result_pos = $stmt->get_result();
        $stmt->close();

        $positions = [
            $row['pilot1'] => "",
            $row['pilot2'] => "",
            $row['pilot3'] => "",
            $row['pilot4'] => "",
        ];
        while ($round = $result_pos->fetch_assoc()) {
            $positions[$round['pilot']] .= "${round['position']}|";
        }

        $heats_by_id[$row['id']] = $row['name'];
        $heats[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'pilot1' => $row['pilot1'],
            'pilot2' => $row['pilot2'],
            'pilot3' => $row['pilot3'],
            'pilot4' => $row['pilot4'],
            'freq1' => $row['freq1'],
            'freq2' => $row['freq2'],
            'freq3' => $row['freq3'],
            'freq4' => $row['freq4'],
            'position1' => $positions[$row['pilot1']],
            'position2' => $positions[$row['pilot2']],
            'position3' => $positions[$row['pilot3']],
            'position4' => $positions[$row['pilot4']],
        ];
    }

    // Collect pilots ranks
    $sql = "SELECT pilot, position, extra FROM ranks WHERE eventid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $eventid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $ranks = [];
    while ($row = $result->fetch_assoc()) {
        // Select laps for this pilot
        $sql = "SELECT id, heat_id, laps FROM rounds WHERE eventid = ? AND pilot = ? ORDER BY heat_id";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $eventid, $row['pilot']);
        $stmt->execute();
        $result_laps = $stmt->get_result();
        $stmt->close();

        $laps = [];
        while ($round = $result_laps->fetch_assoc()) {
            $heat_name = $heats_by_id[$round['heat_id']];

            $round_laps = json_decode($round['laps']);
            foreach ($round_laps as $lap) {
                $laps[] = [ "${heat_name}/Round ${round['id']}: ${lap}" ];
            }
        }

        $ranks[] = [
            'pilot' => $row['pilot'],
            'position' => $row['position'],
            'extra' => $row['extra'],
            'laps' => $laps,
        ];
    }

    # Current heat
    $sql = "SELECT current_heat_id FROM events WHERE id = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param("s", $eventid);
    $stm->execute();
    $result = $stm->get_result();
    $stm->close();

    $current_heat_id = $result->fetch_assoc()['current_heat_id'];

    // Send results as JSON
    echo json_encode(["ranks" => $ranks, "heats" => $heats, "current_heat_id" => $current_heat_id]);
} else {
    // If params are missing
    http_response_code(400);  // Bad Request
    echo json_encode(['error' => 'eventid is required']);
}

$conn->close();
