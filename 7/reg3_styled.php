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
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md"><form method="POST" enctype="multipart/form-data">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required><br>

        <label for="pseudo">Pseudonyme :</label>
        <input type="text" name="pseudo" required><br>

        <label for="ddn">Date de Naissance :</label>
        <input type="date" name="ddn" required><br>

        <label for="genre">Genre :</label>
        <select name="genre" required>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
            <option value="Autre">Autre</option>
        </select><br>

        <label for="age">Âge :</label>
        <input type="number" name="age" required><br>

        <label for="rs">Indiquez le rôle souhaité :</label>
        <input type="text" name="rs" required><br>

        <label for="pass">Mot de passe :</label>
        <input type="password" name="pass" required><br>

        <label for="photo">Photo de profil :</label>
        <input type="file" name="photo" accept="image/*" required><br>

        <button type="submit">S'inscrire</button>
    </form>
  </div>
</body>
</html>