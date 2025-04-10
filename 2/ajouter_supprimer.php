<?php
include('config.php'); // Inclusion du fichier de configuration pour la connexion à la base de données

// Ajouter un utilisateur
if (isset($_POST['ajouter'])) {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT); // Hashage du mot de passe pour sécurité
    $niveau = $_POST['niveau'];

    // Insertion du nouvel utilisateur dans la base de données
    $query = "INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, niveau) VALUES (:nom_utilisateur, :mot_de_passe, :niveau)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nom_utilisateur' => $nom_utilisateur, 'mot_de_passe' => $mot_de_passe, 'niveau' => $niveau]);

    echo "Utilisateur ajouté avec succès!";
}

// Supprimer un utilisateur
if (isset($_GET['supprimer_id'])) {
    $id_utilisateur = $_GET['supprimer_id'];

    // Suppression de l'utilisateur de la base de données
    $query = "DELETE FROM utilisateurs WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_utilisateur]);

    echo "Utilisateur supprimé avec succès!";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter/Supprimer Utilisateur</title>
<link rel="stylesheet" href="style.css">

</head>
<body>
    <header>
        <h1>Ajouter ou Supprimer un Utilisateur</h1>
    </header>

    <h2>Ajouter un Utilisateur</h2>
    <form method="POST">
        <label for="nom_utilisateur">Nom d'Utilisateur:</label>
        <input type="text" name="nom_utilisateur" required>
        <label for="mot_de_passe">Mot de Passe:</label>
        <input type="password" name="mot_de_passe" required>
        <label for="niveau">Niveau d'Accès:</label>
        <select name="niveau" required>
            <option value="admin">Admin</option>
            <option value="user">Utilisateur</option>
            <option value="guest">Invité</option>
        </select>
        <button type="submit" name="ajouter">Ajouter Utilisateur</button>
    </form>

    <h2>Supprimer un Utilisateur</h2>
    <form method="GET">
        <label for="supprimer_id">ID de l'utilisateur à supprimer:</label>
        <input type="text" name="supprimer_id" required>
        <button type="submit">Supprimer Utilisateur</button>
    </form>

    <footer>
        <p>&copy; 2025 Maison Design - Gestion des Utilisateurs</p>
    </footer>
</body>
</html>
