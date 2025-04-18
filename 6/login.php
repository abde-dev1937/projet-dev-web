<?php
session_start();
include 'dbconnect.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
    echo "<script>
        alert('Vous êtes déjà connecté !');
        window.location.href = '/';
    </script>";
}

$erreur = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//echo("etat verifie : ". $user['verifie']);
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && $user['mot_de_passe'] === $password) {

	if (!$user['demande_validee']) {
    	echo json_encode(["success" => false, "error" => "Votre demande d'inscription est en attente de validation."]);
   	exit;
	}
	
if (!$user['verifie']) {
        $code = $user['code_validation'];
        echo "<script>
          let input = prompt('Entrez le code de validation reçu par email ou affiché : $code');
          if (input === '$code') {
            fetch('verification_code.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ email: '{$user['email']}', code: input })
            }).then(() => {
              alert('Compte validé ! Bienvenue.');
              window.location.href='/dev/';
            });
          } else {
            alert('Code incorrect. Connexion refusée.');
            window.location.href='login.php';
          }
        </script>";
        exit;
    }


        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $user['email'];
        $_SESSION['pseudo'] = $user['pseudonyme'];
	$_SESSION['statut'] = $user['statut'];
	$_SESSION['role'] = $user['role'];
	$_SESSION['points'] = $user['points'];
	$_SESSION['id'] = $user['id_user'];

echo "<script>
  sessionStorage.setItem('username', '{$_SESSION['pseudo']}');
  alert('SessionStorage mis à jour pour l\'affichage du mot de passe');
  window.location.href = '/dev/';
</script>";
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<!-- HTML EN DESSOUS -->

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    .btn-epure {
      padding: 10px 20px;
      border-radius: 20px;
      color: white;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1rem;
      margin-top: 10px;
    }
    .btn-green { background-color: #4CAF50; }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-red-600">Connexion</h2>
    
    <?php if ($erreur): ?>
      <p class="text-red-600 mb-4"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Email :</label>
        <input type="email" name="email" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Mot de passe :</label>
        <input type="password" name="password" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
      </div>

      <button type="submit" class="btn-epure btn-green w-full">Se connecter</button>
    </form>
  </div>

</body>
</html>
