<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == 'false') {
    echo "<script>
        alert('Vous ,'êtes pas connecté !');
        window.location.href = '/dev/';
    </script>";
}

if ($_SESSION['statut'] != 'admin') {
    echo "<script>
        alert('Vous êtes déjà connecté !');
        window.location.href = '/';
    </script>";
}


include 'dbconnect.php';

// Récup les demandes en attente
$stmt = $pdo->prepare("SELECT * FROM demandes");
$stmt->execute();
$demandes = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_demande = $_POST['id'];
    $email = $_POST['email'];
    $action = $_POST['action'];

    // Récup les infos de la demande
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

        $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $subject = "Demande d'inscription refusée";
        $message = "Bonjour,\n\nNous sommes désolés, votre demande a été refusée.";
        $headers = "From: mailing@localhost.com";
        // mail($email, $subject, $message, $headers);

        header("Location: gestion_demande.php");
        exit;

    } elseif ($action === 'accepter') {
        $role = $_POST['role'] ?? 'utilisateur';
        $statut = $_POST['statut'] ?? 'actif';
        $code = strval(rand(100000, 999999));

        // Vérifie si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Mise à jour
            $stmt = $pdo->prepare("UPDATE utilisateur SET role = :role, statut = :statut, code_validation = :code, verifie = 0, demande_validee = 1 WHERE email = :email");
            $stmt->execute([
                ':role' => $role,
                ':statut' => $statut,
                ':code' => $code,
                ':email' => $email
            ]);
        } else {
            // Insertion
            $stmt = $pdo->prepare("INSERT INTO utilisateur (email, role, statut, code_validation, verifie, demande_validee) 
                                   VALUES (:email, :role, :statut, :code, 0, 1)");
            $stmt->execute([
                ':email' => $email,
                ':role' => $role,
                ':statut' => $statut,
                ':code' => $code
            ]);
        }

        // Supprimer la demande
        $pdo->prepare("DELETE FROM demandes WHERE id = :id")->execute([':id' => $id_demande]);

        $subject = "Votre code d’activation";
        $message = "Bonjour,\n\nVotre compte a été accepté. Voici votre code de vérification : $code";
        $headers = "From: mailing@localhost.com";
        // mail($email, $subject, $message, $headers);

        echo "Code de vérification (affiché ici en attendant SMTP) : $code";
        header("Location: gestion_demande.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
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
		    <th>Rôle souhaité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr>
                        <td><?= htmlspecialchars($demande['pseudonyme']) ?></td>
                        <td><?= htmlspecialchars($demande['last_name']) ?></td>
                        <td><?= htmlspecialchars($demande['first_name']) ?></td>
                        <td><?= htmlspecialchars($demande['email']) ?></td>
			<td><?= htmlspecialchars($demande['rs']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $demande['id'] ?>">
                                <input type="hidden" name="email" value="<?= $demande['email'] ?>">

                                <select name="action" required>
                                    <option value="">Sélectionner</option>
                                    <option value="accepter">Accepter</option>
                                    <option value="refuser">Refuser</option>
                                </select>

                                <div id="roleStatut" style="display: none;">
                                    <label>Rôle :</label>
                                    <select name="role" required>
                                        <option value="admin">enfant</option>
                                        <option value="adulte">adulte</option>
					<option value="ado">adolescent</option>
					<option value="visiteur">visiteur</option>
                                    </select><br>

                                    <label>Statut :</label>
                                    <select name="statut" required>
                                        <option value="admin">administrateur</option>
                                        <option value="inter">intermediaire</option>
					<option value="simple">simple</option>
                                    </select>
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
        // Afficher rôle/statut si "accepter" sélectionné
        const actionSelects = document.querySelectorAll('select[name="action"]');

        actionSelects.forEach(select => {
            select.addEventListener('change', function () {
                const roleStatutDiv = this.closest('form').querySelector('#roleStatut');
                roleStatutDiv.style.display = (this.value === 'accepter') ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
