<?php
include 'dbconnect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$table = $data['table'] ?? null;
$column = $data['column'] ?? null;
$value = $data['value'] ?? null;
$idField = $data['idField'] ?? null;
$id = $data['id'] ?? null;

if (!$table || !$column || !$idField || !$id) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
    exit;
}

// Correction de valeur vide pour colonnes numériques ou booléennes
if ($value === '') {
    $value = null; // ou 0 si tu veux forcer à zéro
}

// Si c’est un booléen (checkbox dans ton UI), on peut aussi forcer à 0 ou 1
if ($value === true) $value = 1;
if ($value === false) $value = 0;

try {
    $stmt = $pdo->prepare("UPDATE `$table` SET `$column` = :value WHERE `$idField` = :id");
    $stmt->execute(['value' => $value, 'id' => $id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
