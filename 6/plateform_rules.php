<?php
$configPath = 'device_config.json';
$rulesPath = 'rules_config.json';

$config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newRules = [];

    foreach ($_POST['rules'] as $device => $values) {
        $newRules[$device] = [
            'points' => (int)$values['points'],
            'roles' => $values['roles'] ?? []
        ];
    }

    file_put_contents($rulesPath, json_encode($newRules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "<div style='background:#d4edda;padding:10px;color:#155724;margin:10px 0;border:1px solid #c3e6cb;border-radius:5px;'>Règles enregistrées avec succès !</div>";
}

$savedRules = file_exists($rulesPath) ? json_decode(file_get_contents($rulesPath), true) : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Configuration des rôles</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
  <h1 class="text-3xl text-center font-bold text-red-600 mb-8">Configuration des rôles autorisés</h1>

  <form method="post" class="space-y-8">
    <?php foreach ($config as $deviceName => $fields): ?>
      <?php $saved = $savedRules[$deviceName] ?? ['points' => '', 'roles' => []]; ?>
      <div class="bg-white p-6 rounded shadow-md border border-red-400">
        <h2 class="text-xl font-semibold text-red-500 mb-4"><?= htmlspecialchars($deviceName) ?></h2>

        <div class="mb-4">
          <label class="block font-medium">Nombre de points :</label>
          <input type="number" name="rules[<?= $deviceName ?>][points]" value="<?= htmlspecialchars($saved['points']) ?>" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div>
          <label class="block font-medium">Rôles autorisés :</label>
          <select name="rules[<?= $deviceName ?>][roles][]" multiple class="mt-1 block w-full border rounded px-3 py-2 h-32">
            <?php foreach (["enfant", "adulte", "ado", "visiteur"] as $role): ?>
              <option value="<?= $role ?>" <?= in_array($role, (array)($saved['roles'] ?? [])) ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="text-center">
      <button type="submit" class="btn-epure bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-full">
        Enregistrer les règles
      </button>
    </div>
  </form>
</body>
</html>
