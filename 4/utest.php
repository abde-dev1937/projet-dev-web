<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "Vous n'êtes pas connecté.";
} else {
    echo "<h2>Variables de session actuelles :</h2>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}
?>
