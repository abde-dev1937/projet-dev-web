<?php
include 'dbconnect.php'; // Connexion à la base de données
session_start(); // Démarrer la session

// Si l'utilisateur est déjà connecté, le rediriger vers la page d'accueil
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
    echo "<script>
        alert('Vous êtes déjà connecté !');
        window.location.href = '/';
    </script>";
}

$email = $_SESSION['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $ddn = $_POST['ddn'];
    $genre = $_POST['genre'];
    $age = $_POST['age'];
    $pfp = $_FILES['photo'];
    $pass = $_POST['pass']; 
    $pseudo = $_POST['pseudo']; 
    $rs = $_POST['rs'];

    // Vérification de l'upload de la photo
    if ($pfp['error'] == 0) {
        $dir = 'pfp/';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);    
        }

        $ext = pathinfo($pfp['name'], PATHINFO_EXTENSION);
        $fname = strtolower($prenom . '_' . $nom . '.' . $ext);  // Créer un nom de fichier unique
        $fpath = $dir . $fname;

        // Déplacer la photo téléchargée
        if (move_uploaded_file($pfp['tmp_name'], $fpath)) {
            // Insérer les informations dans la base de données (table utilisateurs)
            $query = "INSERT INTO utilisateur (pseudonyme, age, gender, dateNaissance, avatar, email, last_name, first_name, mot_de_passe, points, statut, role) 
                      VALUES (:pseudo, :age, :gender, :ddn, :pfp, :email, :nom, :prenom, :pass, 0, 'statut en attente', 'role en attente')";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':ddn' => $ddn,
                ':pseudo' => $pseudo,
                ':email' => $email,
                ':pass' => $pass,
                ':gender' => $genre,
                ':age' => $age,
                ':pfp' => $fpath
            ]);

            // Deuxième requête pour insérer dans la table utilisateurs
            $query2 = "INSERT INTO demandes (pseudonyme, age, gender, rs, email, last_name, first_name) 
                       VALUES (:pseudo, :age, :gender, :rs, :email, :nom, :prenom)";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->execute([
                ':pseudo' => $pseudo,
                ':age' => $age,
                ':gender' => $genre,
                ':rs' => $rs,
                ':email' => $email,
                ':nom' => $nom,
                ':prenom' => $prenom
            ]);
            
            echo "<script>
                alert('Demande envoyée avec succès. Vous serez notifié après approbation.');
                window.location.href = '/';
            </script>";
        } else {
            echo "Erreur lors de l'upload de la photo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required><br>

        <label for="pseudo">Pseudonyme :</label>
        <input type="text" name="pseudo" required><br>

        <label for="ddn">Date de Naissance :</label>
        <input type="date" name="ddn" required><br>

        <label for="genre">Genre :</label>
        <select name="genre" required>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
            <option value="Autre">Autre</option>
        </select><br>

        <label for="age">Âge :</label>
        <input type="number" name="age" required><br>

        <label for="rs">Indiquez le rôle souhaité :</label>
        <input type="text" name="rs" required><br>

        <label for="pass">Mot de passe :</label>
        <input type="password" name="pass" required><br>

        <label for="photo">Photo de profil :</label>
        <input type="file" name="photo" accept="image/*" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>