<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'dbconnect.php';

$tables = [
    'tv_connectee' => 'TV',
    'chauffage_connecte' => 'Chauffage',
    'lumiere_connectee' => 'Lumière',
    'assistant_voix' => 'Assistant',
    'enceinte_connectee' => 'Enceinte',
    'aspirateur' => 'Aspirateur',
    'plaque_chauffante' => 'Plaque chauffante',
    'tondeuse' => 'Tondeuse',
    'machine_laver' => 'Machine à laver',
    'seche_linge' => 'Seche-linge',
    'lave_vaisselle' => 'Lave-vaisselle',
    'gestion_eau' => 'Gestion de l\'eau',
    'gestion_chauffage' => 'Gestion du chauffage',
    'ouverture_portes' => 'Porte'
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