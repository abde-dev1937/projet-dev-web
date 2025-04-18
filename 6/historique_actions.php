<?php
include('dbconnect.php');
$query = "SELECT * FROM logs ORDER BY timestamp DESC";
$stmt = $pdo->query($query);
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Actions</title>
    <link rel="stylesheet" href="hassoul.css">
</head>
<body>
    <h1>Historique des Actions</h1>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID Log</th>
                <th>Type</th>
                <th>ID Appareil</th>
                <th>Action</th>
                <th>Valeur</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actions as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['id']) ?></td>
                <td><?= htmlspecialchars($log['type']) ?></td>
                <td><?= htmlspecialchars($log['deviceId']) ?></td>
                <td><?= htmlspecialchars($log['action']) ?></td>
                <td><?= htmlspecialchars($log['valeur']) ?></td>
                <td><?= htmlspecialchars($log['timestamp']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
