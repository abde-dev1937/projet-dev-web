<?php
$host = "localhost";  
$user = "root";        
$pass = "";           
$dbname = "devweb";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
    exit();
}
?>

