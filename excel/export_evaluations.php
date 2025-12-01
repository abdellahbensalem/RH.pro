<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../config.php';

// ✅ Vérifie la session
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

// 🔹 En-têtes Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=evaluations_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 🔹 Ajouter le BOM UTF-8 pour les accents
echo "\xEF\xBB\xBF";

// 🔹 En-tête centré avec titre
echo '
<table border="0" width="100%" style="border:none; text-align:center;">
<tr>
    <td colspan="7" style="text-align:center;">
        <h2>CNFEPD - Centre National de Formation et d’Enseignement Professionnel à Distance</h2>
        <h3>Liste des Évaluations</h3>
        <p style="font-size:12px;">Exporté le : ' . date("d/m/Y à H:i") . '</p>
    </td>
</tr>
</table>
<br>
';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔹 Requête principale
    $sql = "
        SELECT e.id, emp.prenom AS emp_prenom, emp.nom AS emp_nom,
               e.date_eval, e.note, e.commentaire,
               ev.prenom AS eval_prenom, ev.nom AS eval_nom
        FROM evaluations e
        JOIN employees emp ON e.employee_id = emp.id
        JOIN employees ev ON e.evaluateur_id = ev.id
        ORDER BY e.id ASC
    ";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🔹 Tableau Excel
    echo '<table border="1" cellspacing="0" cellpadding="5">';
    echo '<tr style="background-color:#dbe5f1; text-align:center; font-weight:bold;">
            <th>ID</th>
            <th>Employé</th>
            <th>Date</th>
            <th>Note</th>
            <th>Commentaire</th>
            <th>Évaluateur</th>
          </tr>';

    if ($rows) {
        foreach ($rows as $r) {
            echo "<tr>
                    <td>{$r['id']}</td>
                    <td>" . htmlspecialchars($r['emp_prenom'] . ' ' . $r['emp_nom']) . "</td>
                    <td>{$r['date_eval']}</td>
                    <td style='text-align:center;'>" . htmlspecialchars($r['note']) . "/20</td>
                    <td style='text-align:left;'>" . htmlspecialchars($r['commentaire']) . "</td>
                    <td>" . htmlspecialchars($r['eval_prenom'] . ' ' . $r['eval_nom']) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center;'>Aucune évaluation trouvée.</td></tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
exit;
