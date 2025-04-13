<?php
session_start();
include 'dbconnect.php'; // Connexion à la base de données

// Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
/*if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}*/

// Liste des objets pour lesquels définir les règles
$objects = [
    'thermostat', 'lumiere', 'television', 'enceinte', 'portail',
    'laveLinge', 'chauffeEau', 'arrosage', 'console', 'chauffage', 'eclairage'
];

// Traitement du formulaire pour modifier les règles
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mettre à jour les règles dans la base de données
    foreach ($objects as $object) {
        if (isset($_POST[$object . '_points']) && isset($_POST[$object . '_role']) && isset($_POST[$object . '_statut'])) {
            $points = $_POST[$object . '_points'];
            $role = $_POST[$object . '_role'];
            $statut = $_POST[$object . '_statut'];

            // Vérifier si la règle existe déjà dans la base de données
            $stmt = $pdo->prepare("SELECT * FROM platform_rules WHERE object_name = :object");
            $stmt->execute([':object' => $object]);
            $rule = $stmt->fetch();

            if ($rule) {
                // Mettre à jour la règle existante
                $stmt = $pdo->prepare("UPDATE platform_rules SET points_per_hour = :points, required_role = :role, statut = :statut WHERE object_name = :object");
                $stmt->execute([
                    ':points' => $points,
                    ':role' => $role,
                    ':statut' => $statut,
                    ':object' => $object
                ]);
            } else {
                // Insérer une nouvelle règle
                $stmt = $pdo->prepare("INSERT INTO platform_rules (object_name, points_per_hour, required_role, statut) VALUES (:object, :points, :role, :statut)");
                $stmt->execute([
                    ':object' => $object,
                    ':points' => $points,
                    ':role' => $role,
                    ':statut' => $statut
                ]);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les règles de la plateforme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des règles de la plateforme</h1>
    </header>

    <div class="content">
        <form method="POST">
            <table border="1">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Objet</th>
                        <th>Points par heure</th>
                        <th>Rôle requis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer les règles actuelles pour chaque objet
                    foreach ($objects as $object) {
                        // Récupérer les règles actuelles pour l'objet
                        $stmt = $pdo->prepare("SELECT * FROM platform_rules WHERE object_name = :object");
                        $stmt->execute([':object' => $object]);
                        $rule = $stmt->fetch();
                        ?>
                        <tr>
                            <td>
                                <select name="<?php echo $object; ?>_statut" required>
                                    <option value="actif" <?php echo ($rule && $rule['statut'] == 'actif') ? 'selected' : ''; ?>>Actif</option>
                                    <option value="inactif" <?php echo ($rule && $rule['statut'] == 'inactif') ? 'selected' : ''; ?>>Inactif</option>
                                    <option value="en_attente" <?php echo ($rule && $rule['statut'] == 'en_attente') ? 'selected' : ''; ?>>En attente</option>
                                </select>
                            </td>
                            <td><?php echo ucfirst($object); ?></td>
                            <td><input type="number" name="<?php echo $object; ?>_points" value="<?php echo $rule ? $rule['points_per_hour'] : ''; ?>" required></td>
                            <td>
                                <select name="<?php echo $object; ?>_role" required>
                                    <option value="admin" <?php echo ($rule && $rule['required_role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="utilisateur" <?php echo ($rule && $rule['required_role'] == 'utilisateur') ? 'selected' : ''; ?>>Utilisateur</option>
                                    <option value="moderateur" <?php echo ($rule && $rule['required_role'] == 'moderateur') ? 'selected' : ''; ?>>Modérateur</option>
                                    <option value="guest" <?php echo ($rule && $rule['required_role'] == 'guest') ? 'selected' : ''; ?>>Invité</option>
                                </select>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Mon Application - Tous droits réservés
    </footer>
</body>
</html>
