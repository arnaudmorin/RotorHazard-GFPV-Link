<?php
require_once("passwords.php");

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer tous les événements
$sql = "SELECT id FROM events";
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
                <a href="fai16de/?id=<?php echo htmlspecialchars($event['id']); ?>">
                    <h2><?php echo htmlspecialchars($event['id']); ?></h2>
                    <p>Click to view details</p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

