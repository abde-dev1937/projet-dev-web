<?php
include('dbconnect.php'); // Inclusion du fichier de configuration

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];
    
    $query = "SELECT * FROM utilisateur WHERE id_user = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_utilisateur]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nouveau_statut = $_POST['statut'];
            $nouveau_role = $_POST['role'];

            $update_query = "UPDATE utilisateur SET statut = :statut, role = :role WHERE id_user = :id";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->execute([
                'statut' => $nouveau_statut,
                'role' => $nouveau_role,
                'id' => $id_utilisateur
            ]);

            echo "<p style='color:green;'>Niveau d'accès et rôle mis à jour avec succès !</p>";
        }
    } else {
        echo "<p style='color:red;'>Utilisateur non trouvé.</p>";
    }
} else {
    echo "<p style='color:red;'>Aucun identifiant utilisateur spécifié.</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier Niveau d'Accès</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Modifier l'utilisateur : <?php echo htmlspecialchars($utilisateur['pseudonyme']); ?></h1>
  </header>

  <form method="POST">
    <label for="statut">Niveau d'accès :</label>
    <select name="statut" id="statut">
      <option value="admin" <?php if ($utilisateur['statut'] == 'admin') echo 'selected'; ?>>Admin</option>
      <option value="simple" <?php if ($utilisateur['statut'] == 'simple') echo 'selected'; ?>>Simple</option>
      <option value="inter" <?php if ($utilisateur['statut'] == 'inter') echo 'selected'; ?>>Intermédiaire</option>
    </select>

    <br><br>

    <label for="role">Rôle utilisateur :</label>
    <select name="role" id="role">
      <option value="enfant" <?php if ($utilisateur['role'] == 'enfant') echo 'selected'; ?>>Enfant</option>
      <option value="ado" <?php if ($utilisateur['role'] == 'ado') echo 'selected'; ?>>Adolescent</option>
      <option value="adulte" <?php if ($utilisateur['role'] == 'adulte') echo 'selected'; ?>>Adulte</option>
      <option value="visiteur" <?php if ($utilisateur['role'] == 'visiteur') echo 'selected'; ?>>Visiteur</option>
    </select>

    <br><br>

    <button type="submit">Mettre à jour</button>
  </form>

  <footer>
    <p>&copy; 2025 Maison Design</p>
  </footer>
</body>
</html>
