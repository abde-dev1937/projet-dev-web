<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include 'dbconnect.php';

$q = strtolower($_GET['q'] ?? '');

if (!$q) {
    echo json_encode([]);
    exit;
}

$like = '%' . $q . '%';

$tables = [
    'tv_connectee' => ['type' => 'TV', 'fields' => ['source_tv', 'connexion', 'nom_salle']],
    'chauffage_connecte' => ['type' => 'Chauffage', 'fields' => ['mode', 'connexion', 'nom_salle']],
    'lumiere_connectee' => ['type' => 'Lumière', 'fields' => ['couleur', 'connexion', 'nom_salle']],
    'assistant_voix' => ['type' => 'Assistant', 'fields' => ['nom', 'type_assistant', 'connexion']],
    'enceinte_connectee' => ['type' => 'Enceinte', 'fields' => ['connexion', 'nom_salle']],
    'aspirateur' => ['type' => 'Aspirateur', 'fields' => ['programme', 'nom_salle']],
    'plaque_chauffante' => ['type' => 'Plaque', 'fields' => ['nom_salle']],
    'tondeuse' => ['type' => 'Tondeuse', 'fields' => ['programme', 'nom_salle']],
    'machine_laver' => ['type' => 'Machine à laver', 'fields' => ['programme', 'nom_salle']],
    'seche_linge' => ['type' => 'Sèche-linge', 'fields' => ['programme', 'nom_salle']],
    'lave_vaisselle' => ['type' => 'Lave-vaisselle', 'fields' => ['programme', 'nom_salle']],
    'gestion_eau' => ['type' => 'Gestion Eau', 'fields' => ['mode_fonctionnement', 'nom_salle']],
    'gestion_chauffage' => ['type' => 'Gestion Chauffage', 'fields' => ['mode_fonctionnement', 'nom_salle']],
    'ouverture_portes' => ['type' => 'Porte', 'fields' => ['porte', 'connexion', 'nom_salle']]
];

$results = [];

foreach ($tables as $table => $config) {
    $typeLower = strtolower($config['type']);

    if (strpos($typeLower, $q) !== false) {
        // L'utilisateur a recherché un type ("tv", "aspirateur", etc.)
        $sql = "SELECT *, '{$config['type']}' AS objet_type FROM $table";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        // Recherche dans les champs spécifiques
        $where = implode(" OR ", array_map(fn($field) => "$field LIKE :search", $config['fields']));
        $sql = "SELECT *, '{$config['type']}' AS objet_type FROM $table WHERE $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':search' => $like]);
    }

    $found = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($found) {
        $results = array_merge($results, $found);
    }
}

echo json_encode($results);
