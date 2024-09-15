<?php
require_once("../passwords.php");

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialiser les messages d'erreur et de succès
$error_message = "";
$success_message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le nom de l'événement depuis le formulaire
    $event_name = trim($_POST['event_name']);

    // Valider le champ event_name
    if (empty($event_name)) {
        $error_message = "Event name is required.";
    } else {
        // Générer un ID unique pour l'événement
        $event_id = uniqid('gfpvlink-');

        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO events (id, name, locked) VALUES (?, ?, 'no')");
        $stmt->bind_param("ss", $event_id, $event_name);

        // Exécuter la requête
        if ($stmt->execute()) {
            $success_message = "Event successfully registered! You event is: $event_id";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        // Fermer la requête
        $stmt->close();
    }
}

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        input[type="text"], input[type="submit"] {
            width: 100px;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type="text"]{
            width: 700px;
        }
        input[type="submit"] {
            width: 150px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #4CAF50;
            color: white;
        }
        .error {
            background-color: #f44336;
            color: white;
        }
        .button {
            width: 150px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            text-decoration: none;
            font-size: 12px;
        }
        
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Register New Event</h2>
    <form method="POST" action="index.php">
        <label for="event_name">Event Name:</label>
        <input type="text" id="event_name" name="event_name" placeholder="Enter event name" required>
        <input type="submit" value="Register Event">
        <a href="/" class="button">Go back</a>
    </form>

    <?php if (!empty($success_message)): ?>
        <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

</div>

</body>
</html>

