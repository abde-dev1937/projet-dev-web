<?php
session_start();
include 'dbconnect.php'; // Connexion à la base de données

// Si l'utilisateur est déjà connecté, on le redirige
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
    echo "<script>
        alert('Vous êtes déjà connecté !');
        window.location.href = '/';
    </script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'email du formulaire
    $email = $_POST['email'];

    // Préparer la requête pour vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Si l'email existe déjà dans la base de données
    if ($stmt->rowCount() > 0) {
        echo "Vous êtes déjà inscrit.";
        exit; // Stopper le script si l'email est trouvé
    } else {
        // Si l'email n'existe pas, rediriger vers reg3.php
        $_SESSION['email'] = $email; // Sauvegarder l'email dans la session
        header("Location: reg3.php");
        exit();
    }
}
?>
