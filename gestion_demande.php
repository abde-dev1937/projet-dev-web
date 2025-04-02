<?php
include 'dbconnect.php'; // Connexion à la base de données

// Vérifier si l'admin est connecté (ajoutez votre logique ici)
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 'true') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_demande = $_POST['id'];
    $action = $_POST['action'];

    // Mettre à jour le statut de la demande
    if ($action == 'accepter') {
        $query = "UPDATE demandes SET statut = 'acceptée' WHERE id = :id";
    } elseif ($action == 'refuser') {
        $query = "UPDATE demandes SET statut = 'refusée' WHERE id = :id";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_demande]);

    header("Location: demande.php"); // Rediriger vers la page des demandes après traitement
    exit();
}
