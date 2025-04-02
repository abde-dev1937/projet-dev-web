<?php
include('config.php'); // Inclusion du fichier de configuration pour la connexion à la base de données

// Vérifier si un identifiant utilisateur est passé dans l'URL
if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $query = "SELECT * FROM historique WHERE utilisateur_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_utilisateur]);
    $historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Historique des Actions</title>
</head>
<body>
    <header>
        <h1>Historique des Actions de l'Utilisateur</h1>
    </header>

    <h2>Historique des Actions</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($historique): ?>
                <?php foreach ($historique as $action): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($action['date']); ?></td>
                        <td><?php echo htmlspecialchars($action['action']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Aucune action trouvée pour cet utilisateur.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2025 Maison Design - Historique des Actions</p>
    </footer>
</body>
</html>
