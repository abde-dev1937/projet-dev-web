<?php
$host = 'localhost'; // Adresse de votre serveur MySQL
$dbname = 'db1'; // Nom de la base de données
$username = 'root'; // Utilisateur MySQL
$password = ''; // Mot de passe MySQL

try {
    // Connexion à la base de données MySQL via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
    exit();
}
?>
