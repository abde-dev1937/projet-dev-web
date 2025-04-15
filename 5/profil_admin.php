<?php
if (!isset($_GET['id'])) {
    echo "Aucun utilisateur spécifié.";
    exit;
}
$id = htmlspecialchars($_GET['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Profil Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script>
    const userId = "<?php echo $id; ?>";

    function loadProfile() {
      fetch(`get_profile.php?id=${userId}`)
        .then(res => res.json())
        .then(data => {
          if (data.error) {
            alert(data.error);
            return;
          }

          document.getElementById("pseudo").value = data.pseudonyme || "";
          document.getElementById("type").value = data.role || "";
          document.getElementById("age").value = data.age || "";
          document.getElementById("genre").value = data.gender || "";
          document.getElementById("dob").value = data.dateNaissance?.split("T")[0] || "";
          document.getElementById("nom").value = data.last_name || "";
          document.getElementById("prenom").value = data.first_name || "";

          if (data.avatar) {
            document.getElementById("profile-pic").src = data.avatar;
          } else {
            document.getElementById("profile-pic").src = `pfp/${data.pseudonyme}.png`;
          }
        });
    }

    function updateProfile() {
      const payload = {
        id_user: userId,
        age: document.getElementById("age").value,
        gender: document.getElementById("genre").value,
        dateNaissance: document.getElementById("dob").value,
        last_name: document.getElementById("nom").value,
        first_name: document.getElementById("prenom").value,
        role: document.getElementById("type").value
      };

      fetch("update_profile.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      })
        .then(res => res.json())
        .then(data => {
          alert(data.success || data.error);
        });
    }

    window.addEventListener("DOMContentLoaded", loadProfile);
  </script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">
  <h1 class="text-2xl font-bold text-red-600 mb-6">Profil d’un utilisateur</h1>
  <img id="profile-pic" src="" alt="Photo de profil" class="w-32 h-32 object-cover rounded-full mb-6 border-4 border-red-500">

  <form class="space-y-4 max-w-lg" onsubmit="event.preventDefault(); updateProfile();">
    <div><label>Pseudo</label><input id="pseudo" readonly class="w-full p-2 border rounded bg-gray-100"></div>
    <div><label>Type</label><input id="type" class="w-full p-2 border rounded"></div>
    <div><label>Âge</label><input id="age" type="number" class="w-full p-2 border rounded"></div>
    <div><label>Genre</label><input id="genre" class="w-full p-2 border rounded"></div>
    <div><label>Date de naissance</label><input id="dob" type="date" class="w-full p-2 border rounded"></div>
    <div><label>Nom</label><input id="nom" class="w-full p-2 border rounded"></div>
    <div><label>Prénom</label><input id="prenom" class="w-full p-2 border rounded"></div>

    <button type="submit" class="btn-epure btn-green">Enregistrer les modifications</button>
  </form>
</body>
</html>
