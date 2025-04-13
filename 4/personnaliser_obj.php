<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonPath = 'device_config.json';

    $type = $_POST['type'];
    $names = $_POST['name'];
    $inputTypes = $_POST['inputType'];
    $editables = $_POST['editable'] ?? [];
    $askeds = $_POST['asked'] ?? [];
    $actions = array_filter(array_map('trim', explode("\n", $_POST['actions'])));

    $fields = [];
    for ($i = 0; $i < count($names); $i++) {
        $fields[] = [
            'name' => $names[$i],
            'inputType' => $inputTypes[$i],
            'editable' => in_array($i, $editables),
            'asked' => in_array($i, $askeds)
        ];
    }

    // Injection automatique du champ conso_en
    $fields[] = [
        'name' => 'conso_en',
        'inputType' => 'number',
        'editable' => false,
        'asked' => false
    ];

    // Charger l'existant
    $existing = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];

    $existing[$type] = $fields;

    // Enregistrer dans le fichier
    file_put_contents($jsonPath, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Enregistrer les actions
    file_put_contents("actions_{$type}.json", json_encode($actions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    echo "Le type d'objet <strong>$type</strong> a bien été ajouté avec <strong>" . count($fields) . "</strong> attributs + conso_en.";
    exit;
}
?>
