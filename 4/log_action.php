<?php
$host = 'localhost';
$dbname = 'devweb';
$user = 'root';
$password = '1234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connexion à la base de données échouée.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validation
if (!isset($data['type'], $data['deviceId'], $data['action'], $data['timestamp'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants.']);
    exit;
}

// Sécurisation
$type = htmlspecialchars($data['type']);
$deviceId = htmlspecialchars($data['deviceId']);
$action = htmlspecialchars($data['action']);
$timestamp = htmlspecialchars($data['timestamp']);
$timestamp2 = isset($data['timestamp2']) ? htmlspecialchars($data['timestamp2']) : null;
$valeur = isset($data['valeur']) && is_numeric($data['valeur']) ? intval($data['valeur']) : null;
$id_utilisateur = isset($data['id_utilisateur']) ? intval($data['id_utilisateur']) : null;

// Requête d'insertion complète
$sql = "INSERT INTO historique (timestamp, timestamp2, id_objet, type_objet, action, valeur, id_utilisateur)
        VALUES (:timestamp, :timestamp2, :id_objet, :type_objet, :action, :valeur, :id_utilisateur)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':timestamp' => $timestamp,
        ':timestamp2' => $timestamp2,
        ':id_objet' => $deviceId,
        ':type_objet' => $type,
        ':action' => $action,
        ':valeur' => $valeur,
        ':id_utilisateur' => $id_utilisateur
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()]);
}
?>
