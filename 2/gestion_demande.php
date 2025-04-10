<?php
session_start();
include 'dbconnect.php';

// Vérification si l'utilisateur est connecté
/*if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}*/

// Récupérer les demandes en attente
$stmt = $pdo->prepare("SELECT * FROM demandes");
$stmt->execute();
$demandes = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_demande = $_POST['id'];
    $email = $_POST['email'];
    $action = $_POST['action'];

    // Récupère les infos de la demande
    $stmt = $pdo->prepare("SELECT * FROM demandes WHERE id = :id");
    $stmt->execute([':id' => $id_demande]);
    $demande = $stmt->fetch();

    if (!$demande) {
        echo "Demande introuvable.";
        exit;
    }

    if ($action === 'refuser') {
        // Supprimer la demande
        $stmt = $pdo->prepare("DELETE FROM demandes WHERE id = :id");
        $stmt->execute([':id' => $id_demande]);

        // Envoyer l'email de refus
        $subject = "Demande d'inscription refusée";
        $message = "Bonjour,\n\nNous sommes désolés, votre demande a été refusée.";
        $headers = "From: noreply@tonsite.com";

        mail($email, $subject, $message, $headers);

    } elseif ($action === 'accepter') {
        $role = $_POST['role'] ?? 'utilisateur';
        $statut = $_POST['statut'] ?? 'actif';

        // Vérifie si l'utilisateur existe déjà dans `users`
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Mettre à jour l’utilisateur existant
            $stmt = $pdo->prepare("UPDATE utilisateurs SET role = :role, statut = :statut WHERE email = :email");
            $stmt->execute([
                ':role' => $role,
                ':statut' => $statut,
                ':email' => $email
            ]);
        } else {
            // Insérer l'utilisateur dans la table utilisateurs
            $stmt = $pdo->prepare("INSERT INTO utilisateur (email, role, statut) VALUES (:email, :role, :statut)");
            $stmt->execute([
                ':role' => $role,
                ':statut' => $statut,
                ':email' => $email
            ]);
        }

        // Supprimer la demande
        $stmt = $pdo->prepare("DELETE FROM demandes WHERE id = :id");
        $stmt->execute([':id' => $id_demande]);

        // Envoyer l'email de confirmation
        $subject = "Demande d'inscription acceptée";
        $message = "Bonjour,\n\nVotre demande a été acceptée ! Vous pouvez maintenant vous connecter.";
        $headers = "From: noreply@tonsite.com";

        mail($email, $subject, $message, $headers);
    }

    header("Location: demande.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des demandes</title>
    <link rel="stylesheet" href="hassoul.css">
</head>
<body>
    <header>
        <h1>Gestion des demandes d'inscription</h1>
    </header>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Pseudonyme</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($demande['pseudonyme']); ?></td>
                        <td><?php echo htmlspecialchars($demande['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($demande['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($demande['email']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $demande['id']; ?>">
                                <input type="hidden" name="email" value="<?php echo $demande['email']; ?>">
                                
                                <!-- Sélection de l'action -->
                                <select name="action" required>
                                    <option value="">Sélectionner</option>
                                    <option value="accepter">Accepter</option>
                                    <option value="refuser">Refuser</option>
                                </select>

                                <!-- Si l'action est "accepter", afficher le rôle et le statut -->
                                <div id="roleStatut" style="display: none;">
                                    <label>Rôle :</label><br>
                                    <select name="role" required>
                                        <option value="admin">Admin</option>
                                        <option value="utilisateur">Utilisateur</option>
                                    </select><br>

                                    <label>Statut :</label><br>
                                    <select name="statut" required>
                                        <option value="actif">Actif</option>
                                        <option value="inactif">Inactif</option>
                                    </select><br>
                                </div>

                                <button type="submit">Valider</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Mon Application - Tous droits réservés
    </footer>

    <script>
        // Afficher les champs pour le rôle et le statut seulement si l'action est "accepter"
        const actionSelects = document.querySelectorAll('select[name="action"]');
        
        actionSelects.forEach(select => {
            select.addEventListener('change', function() {
                const roleStatutDiv = this.closest('form').querySelector('#roleStatut');
                if (this.value === 'accepter') {
                    roleStatutDiv.style.display = 'block';
                } else {
                    roleStatutDiv.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
