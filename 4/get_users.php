<?php
include 'dbconnect.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT pseudo FROM utilisateur");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la récupération des utilisateurs.']);
}
