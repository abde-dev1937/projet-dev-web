<?php
session_start();
include('dbconnect.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || !isset($_POST)) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté ou données manquantes']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['id'];
$pointsToDeduct = intval($data['points']);

if ($pointsToDeduct <= 0) {
    echo json_encode(['success' => false, 'error' => 'Points invalides']);
    exit;
}

$stmt = $pdo->prepare("SELECT points FROM utilisateur WHERE id_user = ?");
$stmt->execute([$userId]);
$current = $stmt->fetchColumn();

if ($current === false || $current < $pointsToDeduct) {
    echo json_encode(['success' => false, 'error' => 'Points insuffisants']);
    exit;
}

// Mise à jour des points
$stmt = $pdo->prepare("UPDATE utilisateur SET points = points - ? WHERE id_user = ?");
$success = $stmt->execute([$pointsToDeduct, $userId]);

if ($success) {
    $_SESSION['points'] -= $pointsToDeduct; // maj session aussi
    echo json_encode(['success' => true, 'newPoints' => $_SESSION['points']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Échec de la mise à jour']);
}
