<?php
include('dbconnect.php');
$query = "SELECT * FROM historique_connexions ORDER BY date_connexion DESC";
$stmt = $pdo->query($query);
$connexions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Connexions</title>
    <link rel="stylesheet" href="hassoul.css">
</head>
<body>
    <h1>Historique des Connexions</h1>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Date</th>
                <th>Adresse IP</th>
                <th>Succès</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($connexions as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['id']) ?></td>
                <td><?= htmlspecialchars($log['email']) ?></td>
                <td><?= htmlspecialchars($log['date_connexion']) ?></td>
                <td><?= htmlspecialchars($log['ip']) ?></td>
                <td><?= $log['succes'] ? "" : "" ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
