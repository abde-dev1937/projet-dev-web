<?php
include 'dbconnect.php'; // Assure-toi que ce fichier initialise bien $pdo

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Sécurité : on vérifie les champs
$table = $data['table'] ?? '';
$idField = $data['idField'] ?? '';
$id = $data['id'] ?? '';

if (!$table || !$idField || !$id) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM `$table` WHERE `$idField` = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
