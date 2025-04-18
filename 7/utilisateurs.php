<?php
include('dbconnect.php'); // Inclusion de la configuration de la base de données

// Récupérer tous les utilisateurs
$query = "SELECT * FROM utilisateur"; // Assurez-vous que la table 'utilisateurs' existe et a les bonnes colonnes
$stmt = $pdo->query($query);
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="hassoul.css">
    <style>
        /* Styles ajoutés pour afficher les utilisateurs */
        .user-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
        }
        .user-card {
            width: 220px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .user-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .user-card button {
            padding: 10px 15px;
            font-size: 14px;
            width: 100%;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Gestion des Utilisateurs</h1>
    </header>

   <div class="user-container">
    <?php if (empty($utilisateurs)): ?>
        <p style="color: red; font-weight: bold; font-size: 18px;">Aucun utilisateur trouvé.</p>
    <?php else: ?>
        <?php foreach ($utilisateurs as $user): ?>
            <div class="user-card">
                <h3><?php echo htmlspecialchars($user['pseudonyme']); ?></h3>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Points :</strong> <?php echo htmlspecialchars($user['points']); ?></p>
                <button onclick="window.location.href='profil_admin.php?id=<?php echo $user['id_user']; ?>'">Voir/Éditer Profil</button>
		<button onclick="window.location.href='modif_niveau.php?id=<?php echo $user['id_user']; ?>'">Modifier Niveau d'Accès</button>
		<button onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) { window.location.href='ajouter_supprimer.php?supprimer_id=<?php echo $user['id_user']; ?>'; }">Supprimer</button>

                <button onclick="window.location.href='modifier_points.php?id=<?php echo $user['id_user']; ?>'">Modifier Points</button>
                <button onclick="window.location.href='historique.php?id=<?php echo $user['id_user']; ?>'">Consulter Historique</button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <footer>
        <p>&copy; 2025 Maison Design - Gestion des Utilisateurs</p>
    </footer>
</body>
</html>
