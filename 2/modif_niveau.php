<?php
include('config.php'); // Inclusion du fichier de configuration

// Vérifier si un identifiant utilisateur est passé dans l'URL
if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];
    
    // Récupérer les informations de l'utilisateur par son ID
    $query = "SELECT * FROM utilisateurs WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_utilisateur]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'utilisateur existe
    if ($utilisateur) {
        // Gestion du formulaire pour modifier le niveau d'accès
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nouveau_niveau = $_POST['niveau'];
            
            // Mettre à jour le niveau d'accès
            $update_query = "UPDATE utilisateurs SET niveau = :niveau WHERE id = :id";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->execute(['niveau' => $nouveau_niveau, 'id' => $id_utilisateur]);
            echo "Niveau d'accès mis à jour avec succès!";
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
    <title>Modifier Niveau d'Accès</title>
</head>
<body>
    <header>
        <h1>Modifier le Niveau d'Accès de <?php echo htmlspecialchars($utilisateur['nom_utilisateur']); ?></h1>
    </header>

    <form method="POST">
        <label for="niveau">Niveau d'Accès:</label>
        <select name="niveau" id="niveau">
            <option value="admin" <?php if ($utilisateur['niveau'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="user" <?php if ($utilisateur['niveau'] == 'user') echo 'selected'; ?>>Utilisateur</option>
            <option value="guest" <?php if ($utilisateur['niveau'] == 'guest') echo 'selected'; ?>>Invité</option>
        </select>
        <button type="submit">Mettre à jour</button>
    </form>

    <footer>
        <p>&copy; 2025 Maison Design - Modification du Niveau d'Accès</p>
    </footer>
</body>
</html>
