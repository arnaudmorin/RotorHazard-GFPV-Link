<?php
require_once("passwords.php");

if (isset($_GET['eventid']) && !empty($_GET['eventid'])) {
    $eventid = htmlspecialchars($_GET['eventid']);
} else {
    die("Missing eventid");
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if this event exists in DB
$check_event_sql = "SELECT * FROM events WHERE id = ?";
$stmt_check_event = $conn->prepare($check_event_sql);
$stmt_check_event->bind_param("s", $eventid);
$stmt_check_event->execute();
$result = $stmt_check_event->get_result();

if ($result->num_rows == 0) {
    $stmt_check_event->close();
    die('Wrong eventid');
} else {
    $row = $result->fetch_assoc();
    // Get event type
    $eventtype = $row['type'];
}
$stmt_check_event->close();

switch ($eventtype) {
    case 'fai64de':
        $title = 'Double Elimination - 64 pilots';
        $initial_scale = 0.5;
        break;
    case 'fai64':
        $title = 'Simple Elimination - 64 pilots';
        $initial_scale = 0.5;
        break;
    case 'fai32de':
        $title = 'Double Elimination - 32 pilots';
        $initial_scale = 0.5;
        break;
    case 'fai32':
        $title = 'Simple Elimination - 32 pilots';
        $initial_scale = 0.5;
        break;
    case 'fai16de':
        $title = 'Double Elimination - 16 pilots';
        $initial_scale = 0.5;
        break;
    case 'fai16':
        $title = 'Simple Elimination - 16 pilots';
        $initial_scale = 0.5;
        break;
    case 'fai8de':
        $title = 'Double Elimination - 8 pilots';
        $initial_scale = 0.5;
        break;
    case 'fai8':
        $title = 'Simple Elimination - 8 pilots';
        $initial_scale = 0.5;
        break;
    case 'qualifier':
        $title = 'Qualifier';
        $initial_scale = 1;
        break;
    default:
	// If we dont know...
        $title = 'Elimination Bracket';
	$eventtype = 'default';
        $initial_scale = 1;
        break;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=<?php echo $initial_scale; ?>">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="bracket.css">
</head>
<body>
    <header>
        <h1><?php echo $title ?></h1>
        <a href="/" class="button">Back to event list</a>
    </header>
    
    <main>
<?php include("bracket-$eventtype.php") ?>
    </main>
<div id='keep-hl'></div>
</body>
</html>

