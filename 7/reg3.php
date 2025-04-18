<?php
include 'dbconnect.php';
session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
    echo "<script>
        alert('Vous êtes déjà connecté !');
        window.location.href = '/dev/';
    </script>";
}

$email = $_SESSION['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $ddn = $_POST['ddn'];
    $genre = $_POST['genre'];
    $age = $_POST['age'];
    $pfp = $_FILES['photo'];
    $pass = $_POST['pass']; 
    $pseudo = $_POST['pseudo']; 
    $rs = $_POST['rs'];
    $code_validation = rand(100000, 999999);
    $verifie = 0;
    $demande_validee = 0;

    if ($pfp['error'] == 0) {
        $dir = 'pfp/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $ext = pathinfo($pfp['name'], PATHINFO_EXTENSION);
        $fname = strtolower($pseudo . '.' . $ext);
        $fpath = $dir . $fname;

        if (move_uploaded_file($pfp['tmp_name'], $fpath)) {
            $query = "INSERT INTO utilisateur (pseudonyme, age, gender, dateNaissance, avatar, email, last_name, first_name, mot_de_passe, points, code_validation, verifie, demande_validee) 
                      VALUES (:pseudo, :age, :gender, :ddn, :pfp, :email, :nom, :prenom, :pass, :points, :code_validation, :verifie, :demande_validee)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':ddn' => $ddn,
                ':pseudo' => $pseudo,
                ':email' => $email,
                ':pass' => $pass,
                ':gender' => $genre,
                ':points' => 0,
                ':age' => $age,
                ':pfp' => $fpath,
                ':code_validation' => $code_validation,
                ':verifie' => $verifie,
                ':demande_validee' => $demande_validee
            ]);

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
            echo "<script>alert('Erreur lors de l’upload de la photo.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f3f4f6;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .form-container {
      background-color: white;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
    }

    h2 {
      text-align: center;
      color: #b91c1c;
    }

    label {
      display: block;
      margin-top: 15px;
      color: #b91c1c;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="password"],
    input[type="file"],
    select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      margin-top: 30px;
      width: 100%;
      padding: 12px;
      background-color: #10b981;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #059669;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Inscription</h2>
    <form method="POST" enctype="multipart/form-data">
      <label for="nom">Nom :</label>
      <input type="text" name="nom" required>

      <label for="prenom">Prénom :</label>
      <input type="text" name="prenom" required>

      <label for="pseudo">Pseudonyme :</label>
      <input type="text" name="pseudo" required>

      <label for="ddn">Date de Naissance :</label>
      <input type="date" name="ddn" required>

      <label for="genre">Genre :</label>
      <select name="genre" required>
        <option value="">-- Sélectionner --</option>
        <option value="Homme">Homme</option>
        <option value="Femme">Femme</option>
        <option value="Autre">Autre</option>
      </select>

      <label for="age">Âge :</label>
      <input type="number" name="age" required>

      <label for="rs">Rôle souhaité :</label>
      <input type="text" name="rs" required>

      <label for="pass">Mot de passe :</label>
      <input type="password" name="pass" required>

      <label for="photo">Photo de profil :</label>
      <input type="file" name="photo" accept="image/*" required>

      <button type="submit">S'inscrire</button>
    </form>
  </div>
</body>
</html>
