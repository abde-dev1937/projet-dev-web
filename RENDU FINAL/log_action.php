<?php
session_start();
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

if (!isset($data['type'], $data['deviceId'], $data['action'], $data['timestamp'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants.']);
    exit;
}

$type = htmlspecialchars($data['type']);
$deviceId = htmlspecialchars($data['deviceId']);
$action = htmlspecialchars($data['action']);
$timestamp = htmlspecialchars($data['timestamp']);
$timestamp2 = isset($data['timestamp2']) ? htmlspecialchars($data['timestamp2']) : null;
$valeur = isset($data['valeur']) && is_numeric($data['valeur']) ? intval($data['valeur']) : null;
$id_user = $_SESSION['id'] ?? $data['userId'] ?? null;


if (str_contains(strtolower($action), 'timer') && $timestamp2) {
    $start = strtotime($timestamp);
    $end = strtotime($timestamp2);
    if ($end && $start && $end > $start) {
        $valeur = round(($end - $start) / 60); // durée en minutes
    }
}

$sql = "INSERT INTO historique (timestamp, timestamp2, id_objet, type_objet, action, valeur, id_user)
        VALUES (:timestamp, :timestamp2, :id_objet, :type_objet, :action, :valeur, :id_user)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':timestamp' => $timestamp,
        ':timestamp2' => $timestamp2,
        ':id_objet' => $deviceId,
        ':type_objet' => $type,
        ':action' => $action,
        ':valeur' => $valeur,
        ':id_user' => $id_user
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()]);
}
?>
