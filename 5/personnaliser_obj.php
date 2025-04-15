<?php
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonPath = 'device_config.json';

    $type = $_POST['type'];
    $names = $_POST['name'];
    $inputTypes = $_POST['inputType'];
    $editables = $_POST['editable'] ?? [];
    $askeds = $_POST['asked'] ?? [];
    $actions = $_POST['actions'] ?? [];
    $hasTimer = isset($_POST['timer']);
    $defaultRoom = $_POST['default_room'] ?? 'salon';

    $fields = [];

    for ($i = 0; $i < count($names); $i++) {
        $fields[] = [
            'name' => $names[$i],
            'inputType' => $inputTypes[$i],
            'editable' => in_array($i, $editables),
            'asked' => in_array($i, $askeds),
            'actions' => isset($actions[$i]) ? array_map('trim', explode(',', $actions[$i])) : []
        ];
    }

    // Champs obligatoires système
    $fields[] = [
        'name' => 'nom',
        'inputType' => 'text',
        'editable' => true,
        'asked' => true,
        'actions' => ['changement_nom']
    ];
    $fields[] = [
        'name' => 'id_connection',
        'inputType' => 'text',
        'editable' => false,
        'asked' => true,
        'actions' => []
    ];
    $fields[] = [
        'name' => 'nom_salle',
        'inputType' => 'text',
        'editable' => false,
        'asked' => false,
        'actions' => [],
        'default' => $defaultRoom // 👈 AJOUT : pour s’assurer que la valeur est utilisée plus tard si nécessaire
    ];
    $fields[] = [
        'name' => 'fonctionnement',
        'inputType' => 'checkbox',
        'editable' => false,
        'asked' => false,
        'actions' => []
    ];

    // Mise à jour du JSON avec la bonne salle
    $existing = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];
    $existing[$type] = [
        'timer' => $hasTimer,
        'default_room' => $defaultRoom,
        'fields' => $fields
    ];
    file_put_contents($jsonPath, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Création de la table SQL
    creerTableSQL($pdo, $type, $fields);
}

function creerTableSQL($pdo, $type, $fields) {
    $columns = [];
    $primaryKey = "id_$type INT PRIMARY KEY AUTO_INCREMENT";
    $columns[] = $primaryKey;

    foreach ($fields as $field) {
        $name = $field['name'];
        switch ($field['inputType']) {
            case 'text':
            case 'color':
            case 'select':
                $sqlType = "VARCHAR(255)";
                break;
            case 'number':
            case 'range':
                $sqlType = "FLOAT";
                break;
            case 'checkbox':
                $sqlType = "BOOLEAN";
                break;
            default:
                $sqlType = "TEXT";
        }
        $columns[] = "`$name` $sqlType";
    }

    $columnsSQL = implode(", ", $columns);
    $sql = "CREATE TABLE IF NOT EXISTS `$type` ($columnsSQL)";
    $pdo->exec($sql);

    $sqlDump = "DROP TABLE IF EXISTS `$type`;\n$sql;\n\n";
    file_put_contents('db3.sql', $sqlDump, FILE_APPEND);
}
?>
