<?php
include 'dbconnect.php';
header('Content-Type: application/json');

$user = $_GET['user'] ?? null;
$id = $_GET['id'] ?? null;

try {
    if ($id) {
        // Accès par ID (admin)
        $stmt = $pdo->prepare("SELECT pseudonyme, age, gender, dateNaissance, email, last_name, first_name, role FROM utilisateur WHERE id_user = :id");
        $stmt->execute(['id' => $id]);
    } elseif ($user) {
        // Accès par pseudonyme (utilisateur)
        $stmt = $pdo->prepare("SELECT pseudonyme, age, gender, dateNaissance, email, last_name, first_name, role FROM utilisateur WHERE pseudonyme = :user");
        $stmt->execute(['user' => $user]);
    } else {
        echo json_encode(['error' => 'Aucun identifiant fourni']);
        exit;
    }

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode(['error' => 'Utilisateur non trouvé']);
        exit;
    }

    // Déterminer le chemin de l’avatar
    $pseudo = $data['pseudonyme'];
    $data['avatar'] = file_exists("pfp/{$pseudo}.png") ? "pfp/{$pseudo}.png" : "pfp/default.png";

    echo json_encode($data);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur interne : ' . $e->getMessage()]);
}
?>
