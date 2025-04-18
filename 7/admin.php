<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == 'false') {
    echo "<script>
        alert('Vous ,'êtes pas connecté !');
        window.location.href = '/dev/';
    </script>";
}

if (!isset($_SESSION['statut']) || $_SESSION['statut'] !== 'admin') {
    echo "<script>
        alert('Accès réservé aux administrateurs.');
        window.location.href = '/dev';
    </script>";
    exit;
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="hassoul.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sections-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
        }
        .section {
            width: 45%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        .buttons-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            width: 100%;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel d'Administration</h1>
    </header>
    <div class="content">
        <div class="sections-container">
            <div class="section">
                <h2>Gestion des Utilisateurs</h2>
                <div class="buttons-container">
                    <button onclick="window.location.href='utilisateurs.php'">Gestion des Utilisateurs</button>
                    <button onclick="window.location.href='plateform_rules.php'">Règles de Fonctionnement</button>
		    <button onclick="window.location.href='personnaliser_obj.html'">Personnaliser objets</button>
                    <button onclick="window.location.href='gestion_demande.php'">Demandes de Users</button> <!-- Nouveau bouton -->
                	<button onclick="window.location.href='ajouter_supprimer.php#ajout'">Ajouter un utilisateur</button>
</div>
            </div>

            <div class="section">
                <h2>Sécurité & Maintenance</h2>
                <div class="buttons-container">
		    <button onclick="window.location.href='get_updates.html'">MAJ des objets</button>
                    <button onclick="window.location.href='securite.html'">Sécurité & Maintenance</button> //securite & maintenance suppr tout users / obj enregistre / verif histo conn
                    <button onclick="window.location.href='sauvegardes.html'">Sauvegardes</button>// afficher dates bdd // creer nouvelle sauv bdd
                    <button onclick="window.location.href='integrite.html'">Vérification des Données</button> //gpt ?
                </div>
            </div>

            <div class="section">
                <h2>Personnalisation de la Plateforme</h2>
                <div class="buttons-container">
                    <button onclick="window.location.href='personnalisation.html'">Personnalisation</button>
                    <button onclick="window.location.href='validation.html'">Règles de Validation</button>
                </div>
            </div>

            <div class="section">
                <h2>Rapports Avancés</h2>
    <div class="buttons-container">
    <button onclick="window.location.href='rapports.html'">Rapports Avancés</button>
    <button onclick="window.location.href='statistiques.html'">Statistiques</button>
    <button onclick="window.location.href='niveaux.html'">Gestion des Niveaux</button>
    <button onclick="window.location.href='historique_connexions.php'">Historique des Connexions</button>
    <button onclick="window.location.href='historique_actions.php'">Historique des Actions</button> <!-- 👈 Ajout -->
</div>
            </div>

            <div class="section">
                <h2>Gestion des Objets & Services</h2>
                <div class="buttons-container">
                    <button onclick="window.location.href='objets.html'">Gestion des Objets & Services</button>
                    <button onclick="window.location.href='categories.html'">Catégories d'Objets</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Maison Design - Administration</p>
    </footer>
</body>
</html>
