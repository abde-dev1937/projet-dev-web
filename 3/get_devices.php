<?php
// Inclure la connexion à la base de données (ajuster en fonction de ta configuration)
include('db_connection.php');  // Assure-toi que ce fichier contient la connexion à ta DB

// Requête pour récupérer les appareils depuis la base de données
$query = "SELECT * FROM appareils";
$result = $conn->query($query);

// Vérifier si des appareils existent dans la base de données
$devices = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ajouter chaque appareil à un tableau associatif
        $devices[] = $row;
    }
}

// Fermer la connexion à la base de données
$conn->close();

// Retourner la réponse en format JSON
echo json_encode($devices);
?>
