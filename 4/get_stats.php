<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=maison_connectee;charset=utf8', 'root', '');

$sql = "SELECT * FROM stats_utilisation ORDER BY timestamp DESC LIMIT 100";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($stats);
