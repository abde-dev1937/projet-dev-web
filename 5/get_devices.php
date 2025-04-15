<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'dbconnect.php';

// Récupérer la salle depuis les paramètres GET (ex: ?salle=salon)
$salle = $_GET['salle'] ?? null;

if (!$salle) {
    echo json_encode(['success' => false, 'error' => 'Paramètre salle manquant']);
    exit;
}

// Charger les types d'objets depuis le fichier JSON
$configPath = __DIR__ . '/device_config.json';
if (!file_exists($configPath)) {
    echo json_encode(['success' => false, 'error' => 'Fichier de configuration introuvable.']);
    exit;
}

$config = json_decode(file_get_contents($configPath), true);

$devices = [];

foreach ($config as $type => $info) {
    try {
        $stmt = $pdo->prepare("SELECT *, :type AS type_objet FROM `$type` WHERE nom_salle = :salle");
        $stmt->execute([':type' => $type, ':salle' => $salle]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $row['source_table'] = $type; // nécessaire pour le rendu
            $devices[] = $row;
        }
    } catch (PDOException $e) {
        error_log("Erreur avec la table $type : " . $e->getMessage());
        // on ignore les erreurs pour les tables inexistantes
    }
}

echo json_encode($devices);
