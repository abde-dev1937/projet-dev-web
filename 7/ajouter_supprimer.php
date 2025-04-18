<?php
include('dbconnect.php');

// Ajout d’un utilisateur
if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare("INSERT INTO utilisateur (
        pseudonyme, age, gender, dateNaissance, avatar, email,
        last_name, first_name, mot_de_passe, points,
        statut, code_validation, verifie, role, demande_validee
    ) VALUES (
        :pseudonyme, :age, :gender, :dateNaissance, :avatar, :email,
        :last_name, :first_name, :mot_de_passe, :points,
        :statut, :code_validation, :verifie, :role, :demande_validee
    )");

    $stmt->execute([
        ':pseudonyme' => $_POST['pseudonyme'],
        ':age' => $_POST['age'],
        ':gender' => $_POST['gender'],
        ':dateNaissance' => $_POST['dateNaissance'],
        ':avatar' => $_POST['avatar'],
        ':email' => $_POST['email'],
        ':last_name' => $_POST['last_name'],
        ':first_name' => $_POST['first_name'],
        ':mot_de_passe' => $_POST['mot_de_passe'],
        ':points' => $_POST['points'] ?? 0,
        ':statut' => $_POST['statut'],
        ':code_validation' => $_POST['code_validation'] ?? null,
        ':verifie' => isset($_POST['verifie']) ? 1 : 0,
        ':role' => $_POST['role'],
	':demande_validee' => 1
    ]);

    echo "<script>alert('Utilisateur ajouté avec succès !');</script>";
    header("Refresh:0"); // Recharge la page après ajout
    exit;
}

// Suppression d’un utilisateur
if (isset($_GET['supprimer_id'])) {
    $id = $_GET['supprimer_id'];
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_user = :id");
    $stmt->execute(['id' => $id]);

    echo "<script>alert('❌ Utilisateur supprimé avec succès !');</script>";
    header("Location: utilisateurs.php"); // Redirection vers la liste
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter ou Supprimer un Utilisateur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Ajouter un Utilisateur</h1>
</header>

<h2 id="ajout">Formulaire d'Ajout</h2>
<form method="POST">
    <label>Pseudonyme :</label>
    <input type="text" name="pseudonyme" required>

    <label>Âge :</label>
    <input type="number" name="age" required>

    <label>Genre :</label>
    <select name="gender" required>
        <option value="Homme">Homme</option>
        <option value="Femme">Femme</option>
        <option value="Autre">Autre</option>
    </select>

    <label>Date de Naissance :</label>
    <input type="date" name="dateNaissance" required>

    <label>Avatar :</label>
    <input type="text" name="avatar" required>

    <label>Email :</label>
    <input type="email" name="email" required>

    <label>Nom :</label>
    <input type="text" name="last_name" required>

    <label>Prénom :</label>
    <input type="text" name="first_name" required>

    <label>Mot de passe :</label>
    <input type="password" name="mot_de_passe" required>

    <label>Points :</label>
    <input type="number" name="points" value="0" min="0">

    <label>Code de validation (optionnel) :</label>
    <input type="text" name="code_validation">

    <label>Vérifié :</label>
    <input type="checkbox" name="verifie">

    <label>statut :</label>
    <select name="statut" required>
        <option value="admin">Admin</option>
        <option value="simple">simple</option>
        <option value="inter">intermediaire</option>
        <option value="visiteur">visiteur</option>
    </select>

    <label>role :</label>
    <select name="role" required>
        <option value="adulte">Adulte</option>
        <option value="enfant">Enfant</option>
        <option value="ado">ado</option>
        <option value="visiteur">visiteur</option>
    </select>

    <button type="submit" name="ajouter">Ajouter</button>
</form>

<footer>
    <p>&copy; 2025 Maison Design - Gestion Utilisateurs</p>
</footer>
</body>
</html>
