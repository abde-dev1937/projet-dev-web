<?php
include 'dbconnect.php';

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
    echo "<script>
        alert('Vous êtes déjà connecté !');
        window.location.href = '/';
    </script>";
}


$db = new SQLITE3('database.db');


if($_SERVER["REQUEST_METHOD"] == "POST") {

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$ddn = $_POST['ddn'];
$genre = $_POST['genre'];
$age = $_POST['age'];
$pfp = $_FILES['photo'];

if(}

if($stmt->execute()) {
header("Localisation: reg2.php");
exit();
} else {
echo "erreur lors de l'inscription";
}

if($pfp['error'] == 0) {
$dir = 'pfp/';

if(!is_dir($dir)) {
mkdir($pfp, 0777, true);	
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$fname = strtolower($prenom, '_', $nom, '.', $ext);
$fpath = $dir . $fname;

if (move_uploaded_file($file['tmp_name'], $filePath)) {
            echo "La photo a été téléchargée avec succès : <a href='$filePath'>Voir l'image</a>";
        } else {
            echo "Erreur lors de l'upload de la photo.";
        }
}



}