<?php
include 'dbconnect.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT pseudonyme FROM utilisateur WHERE pseudonyme IS NOT NULL");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as &$user) {
        $pseudo = $user['pseudonyme'];
        $path = "pfp/$pseudo.png";
        $user['avatar'] = file_exists($path) ? $path : "pfp/default.png";
    }

    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage()]);
}
