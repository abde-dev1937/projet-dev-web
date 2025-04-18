<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'dbconnect.php';

$configPath = __DIR__ . '/device_config.json';
if (!file_exists($configPath)) {
    echo json_encode(['success' => false, 'error' => 'Fichier de configuration introuvable.']);
    exit;
}

$config = json_decode(file_get_contents($configPath), true);
$devices = [];

$salle = $_GET['salle'] ?? null; // facultatif maintenant

foreach ($config as $type => $info) {
    try {
        // Prépare la requête selon que la salle est filtrée ou pas
        $sql = $salle ? "SELECT *, :type AS type_objet FROM `$type` WHERE nom_salle = :salle"
                      : "SELECT *, :type AS type_objet FROM `$type`";

        $stmt = $pdo->prepare($sql);

        // Applique les bons paramètres
        if ($salle) {
            $stmt->execute([':type' => $type, ':salle' => $salle]);
        } else {
            $stmt->execute([':type' => $type]);
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $row['source_table'] = $type;
            $devices[] = $row;
        }
    } catch (PDOException $e) {
        error_log("Erreur avec la table $type : " . $e->getMessage());
        // Continue même si une table échoue
    }
}

echo json_encode($devices);
