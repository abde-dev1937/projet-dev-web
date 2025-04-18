<?php
include('dbconnect.php'); // Connexion à la BDD

$historique = [];

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    // Requête SQL avec tri par date décroissante
    $query = "SELECT timestamp, action, type_objet, id_objet, valeur FROM historique WHERE id_user = :id ORDER BY timestamp DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_utilisateur]);
    $historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Aucun identifiant utilisateur spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Actions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Historique des Actions de l'Utilisateur</h1>
    </header>

    <h2>Historique</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Objet</th>
                <th>Type</th>
                <th>Action</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($historique)): ?>
                <?php foreach ($historique as $action): ?>
                    <tr>
                        <td><?= htmlspecialchars($action['timestamp']) ?></td>
                        <td><?= htmlspecialchars($action['id_objet']) ?></td>
                        <td><?= htmlspecialchars($action['type_objet']) ?></td>
                        <td><?= htmlspecialchars($action['action']) ?></td>
                        <td><?= $action['valeur'] !== null ? htmlspecialchars($action['valeur']) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucune action trouvée pour cet utilisateur.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2025 Maison Design - Historique des Actions</p>
    </footer>
</body>
</html>
