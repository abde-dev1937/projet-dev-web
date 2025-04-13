<?php
include 'dbconnect.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$table = $data['table'] ?? '';
$column = $data['column'] ?? '';
$value = $data['value'] ?? '';
$idField = $data['idField'] ?? '';
$id = $data['id'] ?? '';

if (!$table || !$column || !$idField || !$id) {
    echo json_encode(['success' => false, 'error' => 'Données manquantes.']);
    exit;
}

// Préparer la requête d’UPDATE
$sql = "UPDATE `$table` SET `$column` = :val WHERE `$idField` = :id";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([':val' => $value, ':id' => $id]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
