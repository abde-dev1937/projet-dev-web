<?php
include('dbconnect.php');
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT type_objet, action, valeur, id_user FROM historique");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
