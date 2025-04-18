<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Sauvegarde de la plateforme</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-center text-red-600">Sauvegarde de la plateforme</h1>

    <form method="post" action="sauv_action.php">
      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Lancer une sauvegarde</button>
    </form>

    <h2 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Sauvegardes existantes :</h2>
    <ul class="list-disc list-inside bg-gray-50 p-4 rounded">
      <?php
        $dir = 'sauv';
        if (is_dir($dir)) {
          $saves = array_diff(scandir($dir, SCANDIR_SORT_DESCENDING), ['.', '..']);
          if (count($saves) > 0) {
            foreach ($saves as $saveFolder) {
              echo "<li class='mb-2'><a class='text-blue-600 underline' href='sauv/$saveFolder/' target='_blank'>$saveFolder</a></li>";
            }
          } else {
            echo "<li>Aucune sauvegarde trouvée.</li>";
          }
        } else {
          echo "<li>Le dossier de sauvegarde n'existe pas.</li>";
        }
      ?>
    </ul>
  </div>
</body>
</html>