<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'dbconnect.php';

$tables = [
    'tv_connectee' => 'tv',
    'chauffage_connecte' => 'chauffage',
    'lumiere_connectee' => 'lumiere',
    'assistant_voix' => 'assistant',
    'enceinte_connectee' => 'enceinte',
    'aspirateur' => 'aspirateur',
    'plaque_chauffante' => 'plaque_chauffante',
    'tondeuse' => 'tondeuse',
    'machine_laver' => 'machine_laver',
    'seche_linge' => 'seche_linge',
    'lave_vaisselle' => 'lave_vaisselle',
    'gestion_eau' => 'gestion_eau',
    'gestion_chauffage' => 'gestion_chauffage',
    'ouverture_portes' => 'porte'
];

$devices = [];

foreach ($tables as $table => $type) {
    try {
        $stmt = $pdo->prepare("SELECT *, :type AS type_objet FROM $table");
        $stmt->execute([':type' => $type]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $row['source_table'] = $table;
            $devices[] = $row;
        }
    } catch (PDOException $e) {
        // En cas d'erreur sur une table, on continue sans planter tout
        error_log("Erreur avec la table $table : " . $e->getMessage());
    }
}

echo json_encode($devices);