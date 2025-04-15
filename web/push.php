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
 * Heat
 * Contain pilot and frequency
 */

if (isset($data['heat']) and isset($data['action']) and $data['action'] == 'alter') {
    dolog($eventid, '--> Received a new heat');
    $heat = $data['heat'];

    // Heat
    $select_race_sql = "SELECT COUNT(*) FROM heats WHERE id = ? AND eventid = ?";
    $insert_race_sql = "INSERT INTO heats (id, pilot1, pilot2, pilot3, pilot4, freq1, freq2, freq3, freq4, name, eventid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $update_race_sql = "UPDATE heats SET pilot1 = ?, pilot2 = ?, pilot3 = ?, pilot4 = ?, freq1 = ?, freq2 = ?, freq3 = ?, freq4 = ?, name = ? WHERE id = ? AND eventid = ?";

    $stmt_select_race = $conn->prepare($select_race_sql);
    $stmt_insert_race = $conn->prepare($insert_race_sql);
    $stmt_update_race = $conn->prepare($update_race_sql);

    // Check if heat already exists
    $stmt_select_race->bind_param("ss", $heat['id'], $eventid);
    $stmt_select_race->execute();
    $stmt_select_race->store_result();
    $stmt_select_race->bind_result($count);
    $stmt_select_race->fetch();

    $pilots = $heat['pilots'];
    while (count($pilots) < 4) {
        $pilots[] = ['', '']; // Ajoute un pilote placeholder
    }

    if ($count > 0) {
        // Update
        $stmt_update_race->bind_param("sssssssssss", $pilots[0][0], $pilots[1][0], $pilots[2][0], $pilots[3][0], $pilots[0][1], $pilots[1][1], $pilots[2][1], $pilots[3][1], $heat['name'], $heat['id'], $eventid);
        if ($stmt_update_race->execute()) {
            dolog($eventid, "Heat updated: ${heat['name']} " . json_encode($pilots));
        } else {
            dolog($eventid, "Error while updating heat: " . $stmt_update_race->error);
        }
    } else {
        // Insert
        $stmt_insert_race->bind_param("sssssssssss", $heat['id'], $pilots[0][0], $pilots[1][0], $pilots[2][0], $pilots[3][0], $pilots[0][1], $pilots[1][1], $pilots[2][1], $pilots[3][1], $heat['name'], $eventid);
        if ($stmt_insert_race->execute()) {
            dolog($eventid, "Heat added: ${heat['name']} " . json_encode($pilots));
        } else {
            dolog($eventid, "Error while adding heat: " . $stmt_insert_race->error);
        }
    }

    $stmt_select_race->close();
    $stmt_insert_race->close();
    $stmt_update_race->close();

    # Update the event to set the current heat
    $sql = "UPDATE events SET current_heat_id = ? WHERE id = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param("ss", $heat['id'], $eventid);
    if ($stm->execute()) {
        dolog($eventid, "Current heat set to ${heat['id']}");
    } else {
        dolog($eventid, "Unable to update current heat: " . $stm->error);
    }
    $stm->close();
}

if (isset($data['heat']) and isset($data['action']) and $data['action'] == 'delete') {
    dolog($eventid, '--> Deleted heat');
    $heat = $data['heat'];

    // Heat
    $select_race_sql = "SELECT COUNT(*) FROM heats WHERE id = ? AND eventid = ?";
    $delete_race_sql = "DELETE FROM heats WHERE id = ? AND eventid = ?";

    $stmt_select_race = $conn->prepare($select_race_sql);

    // Check if heat exists
    $stmt_select_race->bind_param("ss", $heat['id'], $eventid);
    $stmt_select_race->execute();
    $stmt_select_race->store_result();
    $stmt_select_race->bind_result($count);
    $stmt_select_race->fetch();

    if ($count > 0) {
        // Delete
        $stmt_delete_race = $conn->prepare($delete_race_sql);
        $stmt_delete_race->bind_param("ss", $heat['id'], $eventid);
        if ($stmt_delete_race->execute()) {
            dolog($eventid, "Heat deleted: ${heat['id']} ");
        } else {
            dolog($eventid, "Error while deleting heat: " . $stmt_delete_race->error);
        }
    } else {
        // Nothing to do, let's log that anyway
        dolog($eventid, "Heat already deleted: ${heat['id']} ");
    }

    $stmt_select_race->close();
    $stmt_delete_race->close();
}

/*
 * Round
 * Contain round results (laps and eventual position)
 */

if (isset($data['round'])) {
    dolog($eventid, '--> Received a new round');
    $round = $data['round'];

    # First delete all previous results
    $sql = "DELETE FROM rounds WHERE id = ? AND heat_id = ? AND pilot = ? AND eventid = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param("ssss", $round['id'], $round['heat_id'], $round['pilot'], $eventid);
    if ($stm->execute()) {
        $stm->close();
        # Now insert the new results
        $laps = json_encode($round['laps']);
        $sql = "INSERT INTO rounds (id, heat_id, eventid, pilot, laps, position) VALUES (?, ?, ?, ?, ?, ?)";
        $stm = $conn->prepare($sql);
        $stm->bind_param("ssssss", $round['id'], $round['heat_id'], $eventid, $round['pilot'], $laps, $round['position']);
        if ($stm->execute()) {
            dolog($eventid, "Round updated: ${round['id']} - ${round['pilot']} - ${laps}");
        } else {
            dolog($eventid, "Error while inserting round: " . $stm->error);
        }
    } else {
        dolog($eventid, "Error while deleting round: " . $stm->error);
    }
    $stm->close();
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

$conn->close();
