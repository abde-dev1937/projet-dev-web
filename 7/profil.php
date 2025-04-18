<?php
session_start();
$loggedInUser = $_SESSION['pseudo'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Profil Utilisateur</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script>
    const loggedInUser = <?= json_encode($loggedInUser) ?>;
    let currentUser = "";

    function loadUsers() {
      fetch("get_list_profiles.php")
        .then(response => response.json())
        .then(users => {
          const list = document.getElementById("userList");
          list.innerHTML = users.map(user => `
            <li class="flex items-center gap-2">
              <img src="${user.avatar}" alt="pfp" class="w-6 h-6 rounded-full border">
              <button onclick="loadProfile('${user.pseudonyme}')" class="text-left text-blue-600 hover:underline">${user.pseudonyme}</button>
            </li>
          `).join('');

          // Auto-charger son propre profil
          if (loggedInUser) {
            loadProfile(loggedInUser);
          }
        })
        .catch(err => console.error("Erreur lors du chargement des utilisateurs :", err));
    }

    function loadProfile(username) {
      fetch(`get_profile.php?user=${encodeURIComponent(username)}`)
        .then(res => res.json())
        .then(data => {
          if (data.error) {
            alert(data.error);
            return;
          }

          currentUser = username;
          document.getElementById("pseudo").value = data.pseudonyme || "";
          document.getElementById("type").value = data.role || "";
          document.getElementById("age").value = data.age || "";
          document.getElementById("genre").value = data.gender || "";
          document.getElementById("dob").value = data.dateNaissance ? data.dateNaissance.split("T")[0] : "";
          document.getElementById("nom").value = data.last_name || "";
          document.getElementById("prenom").value = data.first_name || "";

          document.getElementById("profile-pic").src = data.avatar || `pfp/${username}.png`;

          document.getElementById("password-section").style.display = (loggedInUser === username) ? "block" : "none";
        });
    }

    function changePassword() {
      const oldPass = document.getElementById("current-pass").value;
      const newPass = document.getElementById("new-pass").value;

      if (!oldPass || !newPass) {
        alert("Veuillez remplir les deux champs.");
        return;
      }

      fetch("change_password.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          username: currentUser,
          current_password: oldPass,
          new_password: newPass
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("Mot de passe modifié avec succès.");
          document.getElementById("current-pass").value = "";
          document.getElementById("new-pass").value = "";
        } else {
          alert(data.error || "Erreur lors du changement de mot de passe.");
        }
      })
      .catch(err => console.error("Erreur réseau :", err));
    }

    window.addEventListener("DOMContentLoaded", loadUsers);
  </script>
  <style>
    .btn-epure {
      padding: 10px 20px;
      border-radius: 20px;
      color: white;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1rem;
    }
    .btn-green { background-color: #4CAF50; }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="flex h-screen">
    <!-- Colonne gauche -->
    <aside class="w-1/4 bg-white p-6 border-r overflow-y-auto">
      <h2 class="text-xl font-bold mb-4 text-red-600">Autres utilisateurs</h2>
      <ul id="userList" class="space-y-2"></ul>
    </aside>

    <!-- Colonne droite -->
    <main class="flex-1 p-8">
      <h1 class="text-2xl font-bold text-red-600 mb-6">Profil Utilisateur</h1>
      <img id="profile-pic" src="" alt="Photo de profil" class="w-32 h-32 object-cover rounded-full mb-6 border-4 border-red-500">

      <form class="space-y-4 max-w-lg">
        <div><label class="block font-semibold">Pseudo</label><input id="pseudo" readonly class="w-full p-2 border rounded bg-gray-100"></div>
        <div><label class="block font-semibold">Type</label><input id="type" readonly class="w-full p-2 border rounded bg-gray-100"></div>
        <div><label class="block font-semibold">Âge</label><input id="age" readonly class="w-full p-2 border rounded bg-gray-100"></div>
        <div><label class="block font-semibold">Genre</label><input id="genre" readonly class="w-full p-2 border rounded bg-gray-100"></div>
        <div><label class="block font-semibold">Date de naissance</label><input id="dob" type="date" readonly class="w-full p-2 border rounded bg-gray-100"></div>
        <div><label class="block font-semibold">Nom</label><input id="nom" readonly class="w-full p-2 border rounded bg-gray-100"></div>
        <div><label class="block font-semibold">Prénom</label><input id="prenom" readonly class="w-full p-2 border rounded bg-gray-100"></div>

        <div id="password-section" style="display: none;">
          <h3 class="font-semibold text-lg mt-6 mb-2">Modifier le mot de passe</h3>
          <label for="current-pass">Mot de passe actuel :</label>
          <input type="password" id="current-pass" class="w-full p-2 border rounded">
          <label for="new-pass" class="mt-2 block">Nouveau mot de passe :</label>
          <input type="password" id="new-pass" class="w-full p-2 border rounded">
          <button type="button" onclick="changePassword()" class="btn-epure btn-green mt-2">Changer le mot de passe</button>
        </div>
      </form>
    </main>
  </div>
</body>
</html>
