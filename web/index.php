<?php
require_once("passwords.php");

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer tous les événements
$sql = "SELECT id, name, type FROM events where archived='no'";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    // Stocker les résultats dans un tableau
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>GFPV Link Events</title>
</head>
<body>
    <h1>Events</h1>
    <div class="container">
        <?php foreach ($events as $event): ?>
            <div class="event-box">
                <a href="view-<?php echo $event['type'] ?>?eventid=<?php echo htmlspecialchars($event['id']); ?>">
                    <h2><?php echo htmlspecialchars($event['name']); ?></h2>
                    <p>Click to view races</p>
                </a>
            </div>
        <?php endforeach; ?>
        <div class="event-box new">
            <a href="register">
                <h2>Create a new event</h2>
                <p>Click to register un new event</p>
            </a>
        </div>
    </div>
</body>
</html>

