<?php
include 'dbconnect.php';
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_user'])) {
    echo json_encode(['error' => 'ID manquant']);
    exit;
}

$stmt = $pdo->prepare("UPDATE utilisateur SET 
    age = :age,
    gender = :gender,
    dateNaissance = :dob,
    last_name = :last_name,
    first_name = :first_name,
    role = :role
    WHERE id_user = :id");

$success = $stmt->execute([
    ':age' => $data['age'],
    ':gender' => $data['gender'],
    ':dob' => $data['dateNaissance'],
    ':last_name' => $data['last_name'],
    ':first_name' => $data['first_name'],
    ':role' => $data['role'],
    ':id' => $data['id_user']
]);

echo json_encode($success ? ['success' => 'Profil mis à jour.'] : ['error' => 'Erreur lors de la mise à jour.']);
?>
