<?php
require_once("passwords.php");
require_once("utils.php");

// Read JSON from POST
$json = file_get_contents('php://input');
#if (json_last_error() === JSON_ERROR_NONE) {
#    dolog('', $json);
#}

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    dolog('', 'Failed to connect to DB ' . $conn->connect_error);
    die("Connection to DB failed");
}

// Decode received JSON
$data = json_decode($json, true);

// Collect data
$eventid = $data['eventid'];

if (empty($eventid)) {
    // Exit
    die('Missing eventid');
}

/*
 * Event
 */

// Check if this event exists
$check_event_sql = "SELECT * FROM events WHERE id = ?";
$stmt_check_event = $conn->prepare($check_event_sql);
$stmt_check_event->bind_param("s", $eventid);
$stmt_check_event->execute();
$result = $stmt_check_event->get_result();

if ($result->num_rows == 0) {
    // The event does not exist
    // Redirect the user to register
    dolog('', "Unregistered event: $eventid");
    $stmt_check_event->close();
    die('Wrong eventid');
} else {
    $row = $result->fetch_assoc();
    // Check if event is locked
    if ($row['locked'] == 'yes') {
        dolog($eventid, "Event is locked");
        $stmt_check_event->close();
        die("Event $eventid is locked");
    }
}
$stmt_check_event->close();

/*
 * Heats
 * Contain pilot and frequency
 */

if (isset($data['heats'])) {
    dolog($eventid, '--> Received some heats');
    // Heats are races not yet started, so we do not have any position yet
    $heats = $data['heats'];
    $all_races_name = array_keys($heats);

    // Check if this is a qualifier race
    $qualifier = false;
    foreach ($all_races_name as $race){
        if (stripos($race, 'qualifier') !== false) {
            $qualifier = true;
            break;
        }
    }

    // If it's not a qualifier
    $type = null;
    if ($qualifier) {
        $type = "qualifier";
    } else {
        // Determine type based on number of races we received
        dolog($eventid, "Number of heats = " . count($heats));
        switch (count($heats)) {
            case 62:
                $type = 'fai64de';
                break;
            case 32:
                $type = 'fai64';
                break;
            case 30:
                $type = 'fai32de';
                break;
            case 16:
                $type = 'fai32';
                break;
            case 14:
                $type = 'fai16de';
                break;
            case 8:
                $type = 'fai16';
                break;
            case 6:
                $type = 'fai8de';
                break;
            case 4:
                $type = 'fai8';
                break;
        }
    }
    if (isset($type)) {
        // Update the event
        $update_event_sql = "UPDATE events SET type = ? WHERE id = ?";
        $stmt_update_event = $conn->prepare($update_event_sql);
        $stmt_update_event->bind_param("ss", $type, $eventid);
        if ($stmt_update_event->execute()) {
            dolog($eventid, "Event type updated: $eventid -- $type");
        } else {
            dolog($eventid, "Error while updating event: " . $stmt_update_event->error);
        }
        $stmt_update_event->close();
    }

    // Races
    $select_race_sql = "SELECT COUNT(*) FROM races WHERE name = ? AND eventid = ?";
    $insert_race_sql = "INSERT INTO races (pilot1, pilot2, pilot3, pilot4, freq1, freq2, freq3, freq4, name, eventid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $update_race_sql = "UPDATE races SET pilot1 = ?, pilot2 = ?, pilot3 = ?, pilot4 = ?, freq1 = ?, freq2 = ?, freq3 = ?, freq4 = ? WHERE name = ? AND eventid = ?";

    $stmt_select_race = $conn->prepare($select_race_sql);
    $stmt_insert_race = $conn->prepare($insert_race_sql);
    $stmt_update_race = $conn->prepare($update_race_sql);

    foreach ($heats as $race_name => $pilots) {
        // Check if race already exists
        $stmt_select_race->bind_param("ss", $race_name, $eventid);
        $stmt_select_race->execute();
        $stmt_select_race->store_result();
        $stmt_select_race->bind_result($count);
        $stmt_select_race->fetch();

        while (count($pilots) < 4) {
            $pilots[] = ['', '']; // Ajoute un pilote placeholder
        }

        if ($count > 0) {
            // Update
            $stmt_update_race->bind_param("ssssssssss", $pilots[0][0], $pilots[1][0], $pilots[2][0], $pilots[3][0], $pilots[0][1], $pilots[1][1], $pilots[2][1], $pilots[3][1], $race_name, $eventid);
            if ($stmt_update_race->execute()) {
                dolog($eventid, "Heat updated: $race_name " . json_encode($pilots));
            } else {
                dolog($eventid, "Error while updating heat: " . $stmt_update_race->error);
            }
        } else {
            // Insert
            $stmt_insert_race->bind_param("ssssssssss", $pilots[0][0], $pilots[1][0], $pilots[2][0], $pilots[3][0], $pilots[0][1], $pilots[1][1], $pilots[2][1], $pilots[3][1], $race_name, $eventid);
            if ($stmt_insert_race->execute()) {
                dolog($eventid, "Heat added: $race_name " . json_encode($pilots));
            } else {
                dolog($eventid, "Error while adding heat: " . $stmt_insert_race->error);
            }
        }
    }

    $stmt_select_race->close();
    $stmt_insert_race->close();
    $stmt_update_race->close();

    // Let's clean races which are not necessary anymore
    // RH is sending heats from a class, if we add / delete heats, it will send
    // heats again, and we dont want to keep the one which are deleted
    $sql = "SELECT name FROM races WHERE eventid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $eventid);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($rows as $row){
        if (! in_array($row['name'], $all_races_name)){
            dolog($eventid, "Deleting " . $row['name']);
            $sql = "DELETE FROM races WHERE name = ? AND eventid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $row['name'], $eventid);
            $stmt->execute();
            $stmt->close();
        }
    }
}

/*
 * Races
 * contain pilots and position in races
 */

if (isset($data['races'])) {
    dolog($eventid, '--> Received some races');
    $races = $data['races'];

    // Races
    $select_race_sql = "SELECT pilot1, pilot2, pilot3, pilot4 FROM races WHERE name = ? AND eventid = ?";
    $insert_race_sql = "INSERT INTO races (pilot1, pilot2, pilot3, pilot4, position1, position2, position3, position4, name, eventid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $update_race_sql = "UPDATE races SET position1 = ?, position2 = ?, position3 = ?, position4 = ? WHERE name = ? AND eventid = ?";

    $stmt_select_race = $conn->prepare($select_race_sql);
    $stmt_insert_race = $conn->prepare($insert_race_sql);
    $stmt_update_race = $conn->prepare($update_race_sql);

    foreach ($races as $race_name => $pilots) {
        // Check if race already exists
        $stmt_select_race->bind_param("ss", $race_name, $eventid);
        $stmt_select_race->execute();
        $result = $stmt_select_race->get_result();

        if ($result->num_rows > 0) {
            // Update
            $row = $result->fetch_assoc();
            $pilot1 = $row['pilot1'];
            $pilot2 = $row['pilot2'];
            $pilot3 = $row['pilot3'];
            $pilot4 = $row['pilot4'];
            $position1 = '';
            $position2 = '';
            $position3 = '';
            $position4 = '';
            // Set each pilot position, with respect to the order in DB
            foreach ($pilots as $pilot) {
                switch ($pilot[0]) {
                    case $pilot1:
                        $position1 = $pilot[1];
                        break;
                    case $pilot2:
                        $position2 = $pilot[1];
                        break;
                    case $pilot3:
                        $position3 = $pilot[1];
                        break;
                    case $pilot4:
                        $position4 = $pilot[1];
                        break;
                }
            }
            $stmt_update_race->bind_param("ssssss", $position1, $position2, $position3, $position4, $race_name, $eventid);
            if ($stmt_update_race->execute()) {
                dolog($eventid, "Race updated: $race_name " . json_encode($pilots));
            } else {
                dolog($eventid, "Error while updating race: " . $stmt_update_race->error);
            }
        } else {
            // Insert
            $stmt_insert_race->bind_param("ssssssssss", $pilots[0][0], $pilots[0][1], $pilots[0][2], $pilots[0][3], $pilots[1][0], $pilots[1][1], $pilots[1][2], $pilots[1][3], $race_name, $eventid);
            if ($stmt_insert_race->execute()) {
                dolog($eventid, "Race added: $race_name " . json_encode($pilots));
            } else {
                dolog($eventid, "Error while adding race: " . $stmt_insert_race->error);
            }
        }
    }

    $stmt_select_race->close();
    $stmt_insert_race->close();
    $stmt_update_race->close();
}


/*
 * Ranks
 * contain pilot ranks for an event
 */

if (isset($data['ranks'])) {
    dolog($eventid, '--> Received some ranks');
    $ranks = $data['ranks'];

    // We first delete all already registered ranks for this event
    $sql = "DELETE FROM ranks WHERE eventid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $eventid);
    $stmt->execute();
    $stmt->close();

    // Now we add every received rank
    $sql = "INSERT INTO ranks (eventid, pilot, position, extra) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $position = 0;
    foreach ($ranks as $rank) {
        $position++;
        $stmt->bind_param("ssss", $eventid, $rank[0], $position, $rank[1]);
        if ($stmt->execute()) {
            dolog($eventid, "Rank added: $position: " . json_encode($rank));
        } else {
            dolog($eventid, "Error while adding rank: " . $stmt->error);
        }
    }
    $stmt->close();
}


/*
 * Laps
 * contain pilot laps for a qualifier event
 */

if (isset($data['laps'])) {
    dolog($eventid, '--> Received some laps');
    $all_laps = $data['laps'];

    $select_sql = "SELECT count(1) FROM laps WHERE pilot = ? AND eventid = ?";
    $insert_sql = "INSERT INTO laps (eventid, pilot, laps) VALUES (?, ?, ?)";
    $update_sql = "UPDATE laps SET laps = ? WHERE pilot = ? AND eventid = ?";

    $stmt_select = $conn->prepare($select_sql);
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_update = $conn->prepare($update_sql);

    foreach ($all_laps as $pilot => $laps) {
        $json_laps = json_encode($laps);
        // Check if laps already exists
        $stmt_select->bind_param("ss", $pilot, $eventid);
        $stmt_select->execute();
        $stmt_select->store_result();
        $stmt_select->bind_result($count);
        $stmt_select->fetch();

        if ($count > 0) {
            // Update
            $stmt_update->bind_param("sss", $json_laps, $pilot, $eventid);
            if ($stmt_update->execute()) {
                dolog($eventid, "Laps updated: $pilot " . $json_laps);
            } else {
                dolog($eventid, "Error while updating laps: " . $stmt_update->error);
            }
        } else {
            // Insert
            $stmt_insert->bind_param("sss", $eventid, $pilot, $json_laps);
            if ($stmt_insert->execute()) {
                dolog($eventid, "Laps added: $pilot " . $json_laps);
            } else {
                dolog($eventid, "Error while adding laps: " . $stmt_insert->error);
            }
        }
    }
    $stmt_select->close();
    $stmt_insert->close();
    $stmt_update->close();
}

$conn->close();
