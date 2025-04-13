<?php
include 'dbconnect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'] ?? null;

if (!$type) {
    echo json_encode(['success' => false, 'error' => 'Type manquant']);
    exit;
}

// Nettoyer l’objet
unset($data['type']);

// Générer dynamiquement les colonnes et placeholders
$columns = array_keys($data);
$placeholders = array_map(fn($k) => ":$k", $columns);

$sql = "INSERT INTO `$type` (" . implode(",", $columns) . ") VALUES (" . implode(",", $placeholders) . ")";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute($data);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
