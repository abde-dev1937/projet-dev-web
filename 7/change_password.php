<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion à la BDD
$host = 'localhost';
$dbname = 'devweb';
$user = 'root';
$password = '1234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur de connexion à la base de données."]);
    exit;
}

// Lire les données JSON
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['username'], $data['current_password'], $data['new_password'])) {
    http_response_code(400);
    echo json_encode(["error" => "Champs requis manquants."]);
    exit;
}

$username = htmlspecialchars($data['username']);
$current = $data['current_password'];
$new = $data['new_password'];

// Étape 1 : Récupérer le mot de passe actuel en base
$stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateur WHERE pseudonyme = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["error" => "Utilisateur non trouvé."]);
    exit;
}

// Étape 2 : Vérifier le mot de passe actuel (non hashé ici)
if ($user['mot_de_passe'] !== $current) {
    echo json_encode(["error" => "Mot de passe actuel incorrect."]);
    exit;
}

// Étape 3 : Mettre à jour le mot de passe
$stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = :new WHERE pseudonyme = :username");
$stmt->execute([
    ':new' => $new,
    ':username' => $username
]);

echo json_encode(["success" => true]);
