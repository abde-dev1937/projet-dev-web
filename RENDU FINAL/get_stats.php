<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=devweb;charset=utf8", "root", "1234");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM historique");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultats = [];

    foreach ($logs as $log) {
        $id_objet = $log['id_objet'];
        $valeur = is_numeric($log['valeur']) ? (int)$log['valeur'] : 0;
        $user = $log['id_user'] ?? 'inconnu';
        $action = trim($log['action']);
        $date = substr($log['timestamp'], 0, 10); // YYYY-MM-DD

        if (!isset($resultats[$id_objet])) {
            $resultats[$id_objet] = [
                'id_objet' => $id_objet,
                'nom' => $id_objet,
                'total_valeur' => 0,
                'actions' => [],
                'utilisateurs' => [],
                'par_jour' => []
            ];
        }

        // Ajouter action
        if (!isset($resultats[$id_objet]['actions'][$action])) {
            $resultats[$id_objet]['actions'][$action] = 0;
        }
        $resultats[$id_objet]['actions'][$action]++;

        // Ajouter utilisateur
        if (!isset($resultats[$id_objet]['utilisateurs'][$user])) {
            $resultats[$id_objet]['utilisateurs'][$user] = 0;
        }
        $resultats[$id_objet]['utilisateurs'][$user]++;

        // Somme conso
        $resultats[$id_objet]['total_valeur'] += $valeur;

        // Par jour
        if (!isset($resultats[$id_objet]['par_jour'][$date])) {
            $resultats[$id_objet]['par_jour'][$date] = 0;
        }
        $resultats[$id_objet]['par_jour'][$date] += $valeur;
    }

    echo json_encode([
        'success' => true,
        'data' => array_values($resultats)
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
