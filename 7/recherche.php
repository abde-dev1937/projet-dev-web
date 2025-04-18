<?php
header('Content-Type: application/json');
include 'dbconnect.php';

$configPath = __DIR__ . '/device_config.json';
$config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];

$results = [];

foreach ($config as $type => $info) {
    try {
        $stmt = $pdo->query("SELECT * FROM `$type`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            // Vérifie que la salle n’est pas vide ou nulle
            if (!empty($row['nom_salle'])) {
                // Génère l'ID basé sur le type
                $idField = "id_$type";
                if (isset($row[$idField])) {
                    $results[] = [
                        'type' => $type,
                        'id' => $row[$idField],
                        'nom_salle' => $row['nom_salle'],
			'nom' =>$row['nom'],
                    ];
                }
            }
        }
    } catch (PDOException $e) {
        // Ignore les erreurs de tables inexistantes
        continue;
    }
}

echo json_encode($results);
