<?php
session_start();

// Vérifie que la variable $allowedStatus a bien été définie dans la page appelante
if (!isset($allowedStatus) || !is_array($allowedStatus)) {
    die("Erreur de configuration d'accès : aucune règle définie.");
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['statut'])) {
    die("Accès refusé : vous n'êtes pas connecté.");
}

$userStatus = $_SESSION['statut'];

if (!in_array($userStatus, $allowedStatus)) {
    die("Accès interdit pour le statut : <strong>$userStatus</strong>.");
}


//$allowedStatus = ['admin', 'avance']; utilisation