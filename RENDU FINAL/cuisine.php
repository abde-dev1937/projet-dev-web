<?php
session_start();
$statut = $_SESSION['statut'] ?? 'visiteur'; // 'visiteur' si non connecté
$readonly = ($statut === 'visiteur') ? 'readonly' : '';
$disabled = ($statut === 'visiteur') ? 'disabled' : '';
$role = $_SESSION['role'] ?? 'visiteur'; // rôle comme enfant, adulte, etc.
$points = $_SESSION['points'] ?? 0;
if($points == 0) {

}

?>





<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cuisine - Appareils</title>
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

  .device-card .progress-bar {
    height: 8px;
    background-color: #4CAF50;
    margin-top: 5px;
    border-radius: 4px;
    transition: width 1s linear;
  }

  .btn-epure:hover { opacity: 0.8; }
  .btn-green { background-color: #4CAF50; }
  .btn-red { background-color: #1E90FF; } /* changé de rouge à bleu */
  .btn-yellow { background-color: #FFEB3B; }

  .container {
    display: flex;
    height: 80vh;
    width: 100%;
  }

  .device-selection {
    width: 25%;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    background-color: #f9f9f9;
    border-right: 2px solid #1E90FF; /* changé */
    overflow-y: auto;
  }

  input[disabled], button[disabled] {
    background-color: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    opacity: 0.7;
  }

  .device-display {
    width: 75%;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    background-color: white;
    overflow-y: auto;
  }

  .device-item {
    padding: 10px;
    margin-bottom: 10px;
    background-color: #f0f0f0;
    border-radius: 10px;
    cursor: pointer;
    border: 1px solid #1E90FF; /* changé */
    margin-right: 10px;
  }

  .device-item:hover { background-color: #ddd; }

  h3 { color: #1E90FF; } /* changé */

  .device-card {
    background-color: white;
    border: 1px solid #1E90FF; /* changé */
    border-radius: 12px;
    padding: 8px;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 30%;
    margin-right: 10px;
    margin-bottom: 20px;
  }

  .device-card h4 {
    color: #1E90FF; /* changé */
    font-size: 1rem;
  }

  .device-card input[type="range"] {
    width: 100%;
  }

  .device-card input[type="color"], 
  .device-card input[type="number"], 
  .device-card input[type="text"], 
  .device-card select {
    width: 100%;
    margin-bottom: 5px;
  }

  .device-card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 10px;
  }
</style>

<script> //pour mettre les var php principales

 const phpUserRole = "<?php echo $role; ?>";
  const phpUserStatut = "<?php echo $statut; ?>";
window.userPoints = <?php echo json_encode($points); ?>;

console.log("Points JS =", userPoints);



  let deviceConfig = {};
const activeTimers = {}; // clé = cardId, valeur = { interval, remaining, etc. }

  let deviceRules = {};
window.addEventListener('DOMContentLoaded', () => {
  Promise.all([
fetch('device_config.json?v=' + Date.now())

      .then(res => {
        if (!res.ok) throw new Error("device_config.json introuvable !");
        return res.json();
      }),
    fetch('rules_config.json?v=' + Date.now())
      .then(res => {
        if (!res.ok) throw new Error("rules_config.json introuvable !");
        return res.json();
      })
  ])
  .then(([config, rules]) => {
    console.log(" Fichier device_config.json bien chargé.");
    console.log(" Contenu brut de deviceConfig :", config);

    const keys = Object.keys(config);
    console.log(" Types d'appareils trouvés :", keys); // Affiche ['console', 'console2', ...]

    if (!keys.includes("console2") || !keys.includes("seche_linge")) {
      console.warn(" Certains types sont manquants dans le JSON !");
    }

    deviceConfig = config;
    deviceRules = rules;

    window.userRole = phpUserRole;
    window.userStatut = phpUserStatut;
    window.isReadOnly = userStatut === 'visiteur';
    window.isLimited = userStatut === 'simple';

    window.isRoleAllowedForType = function (type) {
      const rule = deviceRules[type];
      return rule?.roles?.includes(userRole) || false;
    };

    loadDevices();
    showDeviceOptions(); 
  })
  .catch(err => {
    console.error("❌ Erreur lors du chargement des fichiers JSON :", err);
    alert("Impossible de charger les fichiers de configuration.");
  });
});

</script>

</head>
<body class="bg-gray-100">
  <!-- Bandeau de navigation -->
  <div class="header bg-gray-800 text-white p-4 text-center">
    <a href="pa.html" class="text-white">Retour à l'accueil</a> | 
    <a href="login.php" class="text-white">Connexion</a> | 
    <a href="reg.html" class="text-white">Inscription</a>
  </div>

  <header>
    <h1 class="text-center text-3xl font-bold mt-4" style="color: #F44336;">Bienvenue dans la Cuisine !</h1>
  </header>

  <div class="container">
    <!-- Zone de sélection des appareils à gauche (25% de la page) -->
    <div class="device-selection">
      <button class="btn-epure btn-green" onclick="showDeviceOptions()"<?= $disabled ?>>Ajouter un appareil</button>
      <div id="device-options" class="mt-4"></div>
    </div>

    <!-- Zone d'affichage des appareils à droite (75% de la page) -->
    <div class="device-display">
      <h3 class="text-2xl font-bold mb-4">Appareils Affichés</h3>
      <div id="device-cards" class="device-card-container"></div>
      <div id="error-message" class="text-red-600 mt-4"></div>
    </div>
  </div>

  <script>
    // Fonction pour charger les appareils depuis la base de données
function loadDevices() {
document.getElementById("device-cards").innerHTML = "";

    fetch('get_devices.php?salle=cuisine')
      .then(response => response.json())
      .then(devices => {
        if (devices.length === 0) {
          document.getElementById('error-message').innerHTML = 'Aucun appareil trouvé dans la base de données.';
          return;
        }
        devices.forEach(device => {
          renderDeviceFromDB(device);
        });
      })
      .catch(error => {
        console.error('Erreur lors du chargement des appareils:', error);
        alert("Erreur lors du chargement des appareils");
      });
  }

  function showDeviceOptions() {
  const options = Object.entries(deviceConfig)
    .filter(([key, config]) => config.default_room === "cuisine")
    .map(([key]) => {
      return `<button class="btn-epure btn-yellow w-full mb-2" onclick="promptDeviceData('${key}')">Ajouter ${key}</button>`;
    })
    .join('');
console.log("HTML des options :", options)

  document.getElementById('device-options').innerHTML = `
    <h4 class="text-lg font-semibold mb-2">Choisissez un type :</h4>
    <div class="flex flex-wrap gap-4">${options}</div>
  `;
}

window.isRoleAllowedForType = function(type) {
  const rules = deviceRules[type]; 
  if (!rules || !Array.isArray(rules.roles)) return false;
  return rules.roles.includes(userRole);
};




// Fonction pour ouvrir le modal avec les options de programmation
function openProgrammingModal(deviceId, deviceName) {
  const modal = document.getElementById('programming-modal');
  const dynamicOptions = document.getElementById('dynamic-options');

  // Réinitialiser le contenu du formulaire
  dynamicOptions.innerHTML = '';

  // Ajouter les champs de base (heure de début, heure de fin)
  dynamicOptions.innerHTML += `
    <label for="start-time">Heure de début :</label>
    <input type="time" id="start-time" required>
    <label for="end-time">Heure de fin :</label>
    <input type="time" id="end-time" required>
  `;

  // Ajouter les options spécifiques à l'appareil
  const device = deviceOptions[deviceName];
  if (device) {
    device.forEach(option => {
      if (option.type === "select") {
        dynamicOptions.innerHTML += `
          <label for="${option.label}">${option.label}:</label>
          <select id="${option.label}">
            ${option.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
          </select>
        `;
      } else if (option.type === "range") {
        dynamicOptions.innerHTML += `
          <label for="${option.label}">${option.label}:</label>
          <input type="range" id="${option.label}" min="${option.min}" max="${option.max}" value="${option.value}">
        `;
      } else if (option.type === "checkbox") {
        dynamicOptions.innerHTML += `
          <label for="${option.label}">${option.label}:</label>
          <input type="checkbox" id="${option.label}" ${option.value ? 'checked' : ''}>
        `;
      }
    });
  }

  // Ouvrir le modal
  modal.style.display = "block";
  currentDeviceId = deviceId; // Stocker l'ID de l'appareil pour soumettre le formulaire
}

function verifierPrivileges(action, deviceId) {
  // Cette fonction retournera true si l'utilisateur a le droit d'effectuer l'action
  // Pour l’instant, on accepte tout
  return true;
}


// Fonction pour fermer le modal
function closeModal() {
  document.getElementById('programming-modal').style.display = "none";
}

// Fonction pour soumettre les informations du formulaire
function submitProgramming() {
  const startTime = document.getElementById('start-time').value;
  const endTime = document.getElementById('end-time').value;

  if (!startTime || !endTime) {
    alert("Veuillez remplir toutes les informations.");
    return;
  }

  let runDuration = prompt("Combien de minutes doit durer cette programmation ?");
  if (runDuration === null || isNaN(runDuration) || runDuration <= 0) {
    alert("Durée invalide ou annulée.");
    return;
  }

  const optionsValues = {};
  document.getElementById('dynamic-options').querySelectorAll('input, select').forEach(input => {
    optionsValues[input.id] = input.type === 'checkbox' ? input.checked : input.value;
  });

  const card = document.getElementById(currentDeviceId);
  const timerId = `timer-${currentDeviceId}-${Date.now()}`;
  const timerHTML = `
    <div class="mt-2 text-sm text-gray-700">
      <span id="${timerId}">Temps restant : ${runDuration} minute(s)</span>
      <button class="btn-epure btn-red ml-2" onclick="stopTimer('${timerId}')">Arrêter</button>
    </div>
  `;
  card.insertAdjacentHTML("beforeend", timerHTML);

  setTimeout(() => {
    const el = document.getElementById(timerId);
    if (el) el.innerText = "Appareil arrêté automatiquement.";
  }, runDuration * 60 * 1000);

  console.log("Programmation soumise pour l'appareil:", currentDeviceId);
  console.log("Heure de début:", startTime);
  console.log("Heure de fin:", endTime);
  console.log("Options:", optionsValues);

logAction("programmation", currentDeviceId, `Programmation de ${startTime} à ${endTime} pendant ${runDuration}min avec options ${JSON.stringify(optionsValues)}`);


  closeModal(); // Bien clôturer ici !
}
function stopTimer(timerId, cardId) {
  const el = document.getElementById(timerId);
  if (el) {
    const card = document.getElementById(cardId);
    if (card && card.dataset[timerId]) {
      clearInterval(card.dataset[timerId]);
    }
    el.innerHTML = "Appareil arrêté manuellement.";
  }
}

const userId = <?php echo json_encode($_SESSION['id'] ?? null); ?>;

function logAction(type, deviceId, action, valeur = null) {
  if (!type || !deviceId) {
    console.warn("logAction appelé avec type/deviceId invalide", { type, deviceId, action });
    return;
  }

  console.log("appel logAction avec :", "TYPE:", type, "| DEVICEID:", deviceId, "| ACTION:", action, "| VALEUR:", valeur, "|USER ID:", userId);

  fetch("log_action.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      type,
      deviceId,
      action,
      valeur,
      userId, 
      timestamp: new Date().toISOString().slice(0, 19).replace('T', ' ')
    })
  })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      console.warn("Erreur logAction :", data.error);
    }
  })
  .catch(err => {
    console.error("Erreur réseau logAction :", err);
  });
}


/*

function addDevice(deviceType) {
  const deviceContainer = document.getElementById("device-cards");

  // Vérifie si l'appareil existe dans la liste des options
  if (deviceOptions[deviceType]) {
    const device = {
      name: deviceType,
      controls: deviceOptions[deviceType]
    };

    // Créer la carte de l'appareil
    const card = document.createElement("div");
    card.className = "device-card";
    card.id = `${device.name}-${Date.now()}`;
    
    let cardHTML = `<h4>${device.name}</h4>`;

// Types d'appareils nécessitant un timer
const timedTypes = ["tv", "chauffage", "aspirateur", "seche-linge", "lave-vaisselle", "console"];
if (timedTypes.includes(deviceType)) {
  cardHTML += `
    <button class="btn-epure btn-green mt-2" onclick="startTimer('${card.id}', '${deviceType}')">Démarrer</button>
    <div class="mt-2 text-sm text-gray-700" id="timer-${card.id}"></div>
  `;
}


    // Ajouter les contrôles de l'appareil
    device.controls.forEach(control => {
      if (control.type === "range") {
        cardHTML += `
          <label>${control.label}:</label>
          <input type="range" min="${control.min}" max="${control.max}" value="${control.value}" onchange="updateControl('${card.id}', '${control.label}', this.value)" />
        `;
      }
      if (control.type === "number") {
        cardHTML += `
          <label>${control.label}:</label>
          <input type="number" value="${control.value}" onchange="updateControl('${card.id}', '${control.label}', this.value)" />
        `;
      }
      if (control.type === "select") {
        cardHTML += `
          <label>${control.label}:</label>
          <select onchange="updateControl('${card.id}', '${control.label}', this.value)">
            ${control.options.map(option => `<option value="${option}">${option}</option>`).join("")}
          </select>
        `;
      }
      if (control.type === "button") {
        cardHTML += `
          <button class="btn-epure btn-green" onclick="triggerAction('${card.id}', '${control.action}')">${control.label}</button>
        `;
      }
      if (control.type === "checkbox") {
        cardHTML += `
          <label>${control.label}:</label>
          <input type="checkbox" ${control.value ? "checked" : ""} onchange="updateControl('${card.id}', '${control.label}', this.checked)" />
        `;
      }
      if (control.type === "color") {
        cardHTML += `
          <label>${control.label}:</label>
          <input type="color" value="${control.value}" onchange="updateControl('${card.id}', '${control.label}', this.value)" />
        `;
      }
      if (control.type === "text") {
        cardHTML += `
          <label>${control.label}:</label>
          <input type="text" value="${control.value}" onchange="updateControl('${card.id}', '${control.label}', this.value)" />
        `;
      }
    });
if (!isReadOnly) {
    // Ajouter le bouton pour retirer l'appareil
    cardHTML += `<button class="btn-epure btn-red mt-2" onclick="removeDevice('${card.id}')">Retirer</button>`;
}
    card.innerHTML = cardHTML;
    deviceContainer.appendChild(card);

    // Fermer l'option de sélection
    document.getElementById('device-options').innerHTML = '';
  } else {
    alert("Erreur : L'appareil sélectionné n'existe pas.");
  }
}
*/


    // Fonction pour mettre à jour les contrôles
function updateControl(id, label, value) {
  const el = document.getElementById(id);
  el.dataset[label] = value;
  console.log(`${label} mis à jour à ${value}`);
  logAction("update", id, `${label} => ${value}`);
}


    // Fonction pour retirer un appareil
function removeDevice(cardId) {
  const card = document.getElementById(cardId);
  if (!card) return;

  const [type, id] = cardId.split('-');
  const config = deviceConfig[type];
  if (!config) {
    console.error("Type non reconnu :", type);
    return;
  }

  // On suppose que la table = type et que l'idField = "id_" + type
  const table = type;
  const idField = `id_${type}`;

  if (!confirm("Confirmer la suppression de l'appareil ?")) return;

  fetch("suppr_device.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ table, idField, id })
  })
    .then(res => res.json())
    .then(response => {
      if (response.success) {
        card.remove(); // Supprime la carte HTML
        logAction("suppression", cardId, `Suppression de ${type}`);
      } else {
        alert("Erreur de suppression : " + response.error);
      }
    })
    .catch(err => {
      console.error("Erreur réseau lors de la suppression :", err);
      alert("Erreur réseau lors de la suppression.");
    });
}


function enregistrerAction(type, deviceId, action) {
  // Exemple d’appel à un script PHP
  fetch('log_action.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      type: type,
      deviceId: deviceId,
      action: action,
      timestamp: new Date().toISOString()
    })
  }).then(res => {
    if (!res.ok) {
      console.warn("Erreur lors de l'enregistrement de l'action.");
    }
  }).catch(err => {
    console.error("Erreur de communication avec log_action.php", err);
  });
}


    // Fonction pour simuler un changement d'action (ex: Enregistrer, Pause, etc.)
function triggerAction(id, action) {
  if (!verifierPrivileges(action, id)) {
    alert("Vous n’avez pas les droits pour effectuer cette action.");
    return;
  }

  let runDuration = prompt(`Combien de minutes pour l'action "${action}" ?`);
  if (runDuration === null || isNaN(runDuration) || runDuration <= 0) {
    alert("Durée invalide ou annulée.");
    return;
  }

logAction("action", id, `${action} pendant ${runDuration} minutes`);

  runDuration = parseInt(runDuration);
  const card = document.getElementById(id);
  const timerId = `timer-${id}-${Date.now()}`;
  const timerSpanId = `${timerId}-span`;

  const timerHTML = `
    <div class="mt-2 text-sm text-gray-700" id="${timerId}">
      <span id="${timerSpanId}">Temps restant : ${runDuration}m 0s</span>
      <button class="btn-epure btn-red ml-2" onclick="stopTimer('${timerId}', '${id}')">Arrêter</button>
    </div>
  `;
  card.insertAdjacentHTML("beforeend", timerHTML);

  let totalSeconds = runDuration * 60;
  const timerSpan = document.getElementById(timerSpanId);

  const interval = setInterval(() => {
    totalSeconds--;

    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    timerSpan.innerText = `Temps restant : ${minutes}m ${seconds}s`;

    if (totalSeconds <= 0) {
      clearInterval(interval);
      timerSpan.innerText = "Appareil arrêté automatiquement.";
      enregistrerAction("Fin automatique", id, action);
    }
  }, 1000);

  if (!card.dataset) card.dataset = {};
  card.dataset[timerId] = interval;

  // Enregistre l’action dans la base de données
  enregistrerAction("Lancement", id, action);

  alert(`${action} lancé pour l'appareil ${id}`);
}


  </script>
<script>

function updateFromDB(type, table, column, value, idField, id, deviceId) {
  const config = deviceConfig[type];

  if (!config || !Array.isArray(config.fields)) {
    console.warn(`Aucune config valide pour ${type}`);
    return;
  }

  const field = config.fields.find(f => f.name === column);
  const actions = field?.actions || [];

  fetch('update_device.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ table, column, value, idField, id })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      actions.forEach(action => {
        logAction(type, deviceId, action, value);
      });
    } else {
      console.error("Erreur de mise à jour :", data.error);
      alert("Erreur de mise à jour.");
    }
  })
  .catch(err => {
    console.error("Erreur réseau :", err);
    alert("Erreur de communication avec le serveur.");
  });
}
function buttAllumer(table, column, idField, id, cardId, actions, isOn, type) {
  const newState = !isOn;
  const button = document.getElementById(`${column}-${id}`);
  const actionToLog = newState ? "allumage" : "extinction";

  fetch('update_device.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      table,
      column,
      value: newState ? 1 : 0,
      idField,
      id
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      logAction(type, `${type}-${id}`, actionToLog, newState);

      // Met à jour le bouton
      button.classList.toggle('btn-green', !newState);
      button.classList.toggle('btn-red', newState);
      button.innerText = newState ? "Éteindre" : "Allumer";

      //  Met à jour dynamiquement la fonction onclick avec le nouvel état
      button.setAttribute("onclick", `buttAllumer("${table}", "${column}", "${idField}", "${id}", "${cardId}", ${JSON.stringify(actions)}, ${newState}, "${type}")`);
    } else {
      console.warn("Erreur update_device :", data.error);
      alert("Erreur de mise à jour.");
    }
  })
  .catch(err => {
    console.error("Erreur réseau :", err);
    alert("Erreur de communication.");
  });
}





  // Fonction pour afficher un appareil depuis la base
function renderDeviceFromDB(device) {
  const container = document.getElementById("device-cards");
  const card = document.createElement("div");

  const type = device.source_table;
  const idField = `id_${type}`;
  const id = device[idField] || Date.now();
  const cardId = `${type}-${id}`;

  const config = deviceConfig[type];
  const roleAllowed = isRoleAllowedForType(type);

  if (!config || !Array.isArray(config.fields)) {
    console.warn(`Pas de configuration valide pour ${type}`);
    return;
  }

  card.className = "device-card";
  card.id = cardId;
  card.setAttribute("data-device-id", cardId);

  let html = `
    <h4 class="text-xl font-bold text-red-600 mb-2 uppercase">${type}</h4>
    <p><strong>Salle :</strong> ${device.nom_salle || "Inconnue"}</p>
    <p><strong>Nom :</strong> ${device.nom || "Inconnu"}</p>
    <p><strong>ID Connexion :</strong> ${device.id_connection || "non défini"}</p>
  `;

  const alreadyDisplayed = ["nom_salle", "nom", "id_connection"];

  config.fields.forEach(field => {
  if (alreadyDisplayed.includes(field.name)) return;

  let val = device[field.name];
  if (field.inputType === 'color' && (!val || !/^#[0-9A-Fa-f]{6}$/.test(val))) {
    val = '#ffffff';
  } else if (val === null || val === undefined) {
    val = '';
  }

  const fieldId = `${field.name}-${id}`;
  const label = `<label for="${fieldId}"><strong>${field.name.replace(/_/g, ' ')}:</strong></label>`;
  const commonParams = `'${type}', '${device.source_table}', '${field.name}', this.value, '${idField}', '${id}', '${cardId}'`;

  html += `<div class="mb-2">`;


  if (field.name === "fonctionnement") {
    const isOn = val == 1;
    const safeActions = JSON.stringify(field.actions || []).replace(/"/g, '&quot;');

    if (roleAllowed && (!isReadOnly || userStatut === 'simple')) {
      html += `
        <button 
          class="btn-epure ${isOn ? 'btn-red' : 'btn-green'} mt-2" 
          id="${field.name}-${id}" 
          onclick="buttAllumer('${device.source_table}', '${field.name}', '${idField}', '${id}', '${cardId}', ${safeActions}, ${isOn}, '${type}')">
          ${isOn ? "Éteindre" : "Allumer"}
        </button>
      `;
    } else {
      html += `<p><strong>Statut :</strong> ${isOn ? "Allumé" : "Éteint"} (lecture seule)</p>`;
    }

  } else if (!roleAllowed || !field.editable || userStatut === 'simple') {
//Utilisateurs non autorisés ou simple (sauf fonctionnement) = lecture seule
    html += `<p>${label} ${val}</p>`;

  } else {
 
    switch (field.inputType) {
      case 'text':
      case 'number':
      case 'range':
        html += `${label}<br><input type="${field.inputType}" id="${fieldId}" value="${val}" 
        onchange="updateFromDB(${commonParams})">`;
        break;

      case 'color':
        html += `${label}<br><input type="color" id="${fieldId}" value="${val}" 
        onchange="updateFromDB(${commonParams})">`;
        break;

      case 'select':
        html += `${label}<br><select id="${fieldId}" onchange="updateFromDB(${commonParams})">`;
        (field.options || []).forEach(option => {
          html += `<option value="${option}" ${val === option ? "selected" : ""}>${option}</option>`;
        });
        html += `</select>`;
        break;

      default:
        html += `<p>${label} Champ non supporté</p>`;
    }
  }

  html += `</div>`;
});

  html += `<div class="mt-3 flex flex-wrap items-center gap-2">`;

  if (config.timer && roleAllowed && (!isReadOnly || userStatut === 'simple')) {

    html += `
      <button class="btn-epure btn-green mt-2" onclick="startTimer('${cardId}', '${type}', '${device.source_table}', '${idField}', '${id}')">Démarrer</button>
      <div id="timer-${cardId}" class="mt-2 text-sm text-gray-700"></div>
    `;
  }

  if (roleAllowed && !isReadOnly && userStatut !== 'simple') {
    html += `<button class="btn-epure btn-red" onclick="removeDevice('${cardId}')">Retirer</button>`;
  }

  html += `</div>`;

  card.innerHTML = html;
  container.appendChild(card);
}


function filtrerParHash() {
  const hash = window.location.hash;
  if (hash.startsWith('#')) {
    const targetId = hash.slice(1); // ex: "console-2"
    const allCards = document.querySelectorAll('[data-device-id]');
    
    allCards.forEach(card => {
      if (card.id === targetId) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }
}async function startTimer(cardId, type, table, idField, id) {
  const timerDiv = document.getElementById(`timer-${cardId}`);
  const button = document.querySelector(`#${cardId} .btn-epure.btn-green, #${cardId} .btn-epure.btn-red`);

  // Si un timer est déjà actif : le stopper
  if (activeTimers[cardId]) {
    clearInterval(activeTimers[cardId].interval);
    delete activeTimers[cardId];
    timerDiv.innerText = "Timer arrêté.";
    if (button) {
      button.classList.remove("btn-red");
      button.classList.add("btn-green");
      button.innerText = "Démarrer";
    }
    logAction(type, `${type}-${id}`, "arrêt manuel timer");
    return;
  }

  // Sinon, demander durée et lancer le timer
  let duration = parseInt(prompt("Entrez la durée du timer en secondes :", "10"));
  if (isNaN(duration) || duration <= 0) {
    alert("Durée invalide.");
    return;
  }

  // Vérification des points
  const pointsParHeure = deviceRules[type]?.points || 0;
  const dureeHeure = duration / 3600;
  const pointsRequis = Math.ceil(pointsParHeure * dureeHeure);

  if (window.userPoints < pointsRequis) {
    alert("Vous n'avez pas assez de points.");
    return;
  }

  // Déduction des points
  const res = await fetch('deduct_points.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ points: pointsRequis })
  });
  const data = await res.json();
  if (!data.success) {
    alert("Erreur de points : " + data.error);
    return;
  }
  window.userPoints = data.newPoints;

  // Lancer le timer
  let remaining = duration;
  timerDiv.innerText = ` Temps restant : ${remaining}s`;

  const interval = setInterval(() => {
    remaining--;
    timerDiv.innerText = `Temps restant : ${remaining}s`;

    if (remaining <= 0) {
      clearInterval(interval);
      delete activeTimers[cardId];
      timerDiv.innerText = " Terminé.";
      logAction(type, `${type}-${id}`, "fin timer");

      // éteindre l’appareil automatiquement
      fetch('update_device.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          table,
          column: 'fonctionnement',
          value: 0,
          idField,
          id
        })
      }).then(res => res.json()).then(data => {
        if (data.success) loadDevices();
      });

      // réinitialiser le bouton
      if (button) {
        button.classList.remove("btn-red");
        button.classList.add("btn-green");
        button.innerText = "Démarrer";
      }
    }
  }, 1000);

  activeTimers[cardId] = { interval };

  // changer le bouton
  if (button) {
    button.classList.remove("btn-green");
    button.classList.add("btn-red");
    button.innerText = "Arrêter";
  }

  logAction(type, `${type}-${id}`, "lancement timer");
}





  // Appel Ajax pour charger les objets de la base
//let deviceConfig = {}; // déclaré globalement pour être utilisé dans renderDeviceFromDB()

function loadDevices() {
  const container = document.getElementById("device-cards");
  container.innerHTML = "";

  fetch('get_devices.php?salle=cuisine')
    .then(response => response.json())
    .then(devices => {
      if (devices.length === 0) {
        document.getElementById('error-message').innerHTML = 'Aucun appareil trouvé dans la base de données.';
        return;
      }

      devices.forEach(device => {
        renderDeviceFromDB(device); // à définir
      });

      filtrerParHash();

      const urlParams = new URLSearchParams(window.location.search);
      const search = urlParams.get('search');

      if (search) {
        const cards = document.querySelectorAll('.device-card');
        let found = false;

        cards.forEach(card => {
          if (card.id.toLowerCase().includes(search.toLowerCase())) {
            card.style.display = "block";
            found = true;
          } else {
            card.style.display = "none";
          }
        });

        if (!found) {
          document.getElementById('device-cards').innerHTML = `
            <div class="text-red-600 font-bold text-center mt-8">Aucun objet correspondant trouvé.</div>`;
        }
      }
    })
    .catch(error => {
      console.error('Erreur lors du chargement des appareils:', error);
      alert("Erreur lors du chargement des appareils");
    });
}










function submitDevice(type) {
const allFields = deviceConfig[type]?.fields || [];
const data = { type, nom_salle: "Cuisine" };

allFields.forEach(field => {
  if (field.asked) {
    const input = document.getElementById(`input-${field.name}`);
    if (input) {
      data[field.name] = input.value || null;
    }
  } else if ('default' in field) {
    data[field.name] = field.default;
  }
});

  fetch('add_device.php?salle=cuisine', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  .then(res => res.json())
  .then(response => {
    if (response.success) {
      logAction("ajout", type, `Ajout de ${type} avec ${JSON.stringify(data)}`);
      loadDevices();
      document.getElementById('device-options').innerHTML = '';
    } else {
      alert("Erreur : " + response.error);
    }
  })
  .catch(err => {
    console.error("Erreur réseau :", err);
    alert("Erreur réseau lors de l'ajout.");
  });
}
const isReadOnly = "<?php echo ($_SESSION['statut'] ?? 'visiteur') ?>" === "visiteur";


function promptDeviceData(type) {
  const rawFields = deviceConfig[type]?.fields || [];
  const fields = rawFields.filter(f => f.asked);

  if (!fields.length) return alert("Aucun champ à saisir pour ce type.");

  let formHTML = `<h4 class="text-lg font-semibold mb-2">Détails pour ${type}</h4>`;

  fields.forEach(field => {
    const label = field.name.replace(/_/g, ' ');
    formHTML += `
      <label class="block mt-2">${label} :</label>
      <input 
        type="${field.inputType || 'text'}" 
        id="input-${field.name}" 
        class="border rounded px-2 py-1 w-full"
        ${(isReadOnly && userStatut !== 'simple') ? 'readonly disabled' : ''}

      />
    `;
  });

  if (!isReadOnly|| userStatut === 'simple') {
    formHTML += `
      <button class="btn-epure btn-green mt-4" onclick="submitDevice('${type}')">Valider</button>
    `;
  } else {
    formHTML += `
      <p class="text-gray-500 mt-4 italic">Vous n’avez pas les droits pour ajouter cet objet.</p>
    `;
  }

  document.getElementById('device-options').innerHTML = formHTML;
}




//a completer + type d'input ?
const userStatut = "<?php echo $_SESSION['statut'] ?? 'visiteur'; ?>";
const userRole = "<?php echo $_SESSION['role'] ?? 'visiteur'; ?>";

  console.log("Statut de l'utilisateur :", userStatut);
  console.log("Rôle de l'utilisateur :", userRole);
	console.log("Rôle utilisateur :", userRole);
console.log("Role autorisé pour console ?", isRoleAllowedForType("console"));


  window.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash;
    if (hash.startsWith('#')) {
      const targetId = hash.slice(1); // ex: "console-1"
      const allCards = document.querySelectorAll('[data-device-id]');

      allCards.forEach(card => {
        if (card.id === targetId) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }
  });
</script>




</body>
</html>
