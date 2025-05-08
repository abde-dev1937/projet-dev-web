 <?php
include('dbconnect.php'); // Inclusion du fichier de configuration pour la connexion à la base de données

// Vérifier si un identifiant utilisateur est passé dans l'URL
if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    // Récupérer les informations de l'utilisateur par son ID
    $query = "SELECT * FROM utilisateur WHERE id_user = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_utilisateur]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'utilisateur existe
    if ($utilisateur) {
        // Gestion du formulaire pour modifier les points
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nouveaux_points = $_POST['points'];
            
            // Mise à jour des points de l'utilisateur
            $update_query = "UPDATE utilisateur SET points = :points WHERE id_user = :id";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->execute(['points' => $nouveaux_points, 'id' => $id_utilisateur]);
            echo "Points mis à jour avec succès!";
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
} else {
    echo "Aucun identifiant utilisateur spécifié.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les Points</title>
</head>
<body>
    <header>
        <h1>Modifier les Points de <?php echo htmlspecialchars($utilisateur['pseudonyme']); ?></h1>
    </header>

    <form method="POST">
        <label for="points">Points:</label>
        <input type="number" name="points" value="<?php echo $utilisateur['points']; ?>" required>
        <button type="submit">Mettre à jour les Points</button>
    </form>

    <footer>
     
    </footer>
</body>
</html>
