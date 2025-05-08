<?php
session_start();
include 'dbconnect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'], $data['code'])) {
    echo json_encode(['success' => false, 'error' => 'Email ou code manquant.']);
    exit;
}

$email = $data['email'];
$code = $data['code'];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email AND code_validation = :code");
$stmt->execute([':email' => $email, ':code' => $code]);
$user = $stmt->fetch();

if ($user) {
    $update = $pdo->prepare("UPDATE utilisateur SET verifie = 1, code_validation = NULL WHERE email = :email");
    $update->execute([':email' => $email]);

    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['pseudo'] = $user['pseudonyme'];

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Code incorrect.']);
}
?>
