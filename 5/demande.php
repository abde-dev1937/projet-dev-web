<?php
include 'dbconnect.php'; // Connexion à la base de données
session_start();

// Vérifier si l'admin est connecté (ajoutez votre logique ici)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 'true') {
    header("Location: login.php");
    exit();
}

// Récupérer les demandes
$query = "SELECT * FROM demandes WHERE statut = 'en attente'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$demandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Demandes</title>
</head>
<body>
    <h1>Gestion des Demandes d'Inscription</h1>

    <?php foreach ($demandes as $demande): ?>
        <div class="demande">
            <h2><?php echo htmlspecialchars($demande['prenom']) . " " . htmlspecialchars($demande['nom']); ?></h2>
            <p>Date de naissance: <?php echo htmlspecialchars($demande['ddn']); ?></p>
            <p>Genre: <?php echo htmlspecialchars($demande['genre']); ?></p>
            <p>Âge: <?php echo htmlspecialchars($demande['age']); ?></p>
            <p><img src="<?php echo htmlspecialchars($demande['pfp']); ?>" alt="Photo de profil" width="100"></p>

            <!-- Options pour accepter ou refuser -->
            <form method="POST" action="gestion_demande.php">
                <input type="hidden" name="id" value="<?php echo $demande['id']; ?>">
                <button type="submit" name="action" value="accepter">Accepter</button>
                <button type="submit" name="action" value="refuser">Refuser</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
