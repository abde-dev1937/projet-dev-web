<?php
session_start();
include 'connexion_bdd.php'; // $pdo

$data = json_decode(file_get_contents("php://input"), true);
$currentUserId = $_SESSION['id_user'] ?? null;

if (!$currentUserId) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié.']);
    exit;
}

$pseudo = $data['pseudo'];
$newFirstName = $data['prenom'];
$newLastName = $data['nom'];
$newPassword = $data['new_password'] ?? '';
$currentPassword = $data['current_password'] ?? '';

$stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateur WHERE id_user = ?");
$stmt->execute([$currentUserId]);
$user = $stmt->fetch();

if (!$user || $user['mot_de_passe'] !== $currentPassword) {
    echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect.']);
    exit;
}

// Update du profil
if ($newPassword) {
    $stmt = $pdo->prepare("UPDATE utilisateur SET first_name = ?, last_name = ?, mot_de_passe = ? WHERE id_user = ?");
    $stmt->execute([$newFirstName, $newLastName, $newPassword, $currentUserId]);
} else {
    $stmt = $pdo->prepare("UPDATE utilisateur SET first_name = ?, last_name = ? WHERE id_user = ?");
    $stmt->execute([$newFirstName, $newLastName, $currentUserId]);
}

echo json_encode(['success' => true]);
