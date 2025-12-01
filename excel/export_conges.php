<?php
session_start();
require_once '../config.php';

// ✅ Vérifie si l’utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

// 🔹 Prépare les en-têtes pour Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=liste_conges_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 🔹 BOM UTF-8 (pour caractères accentués)
echo "\xEF\xBB\xBF";

// 🔹 En-tête avec titre centré
echo '
<table border="0" width="100%" style="border:none; text-align:center;">
<tr>
    <td colspan="7" style="text-align:center; vertical-align:middle;">
        <h2>CNFEPD - Centre National de Formation et d’Enseignement Professionnel à Distance</h2>
        <h3>Liste des Congés</h3>
        <p style="font-size:12px;">Exporté le : ' . date("d/m/Y à H:i") . '</p>
    </td>
</tr>
</table>
<br>
';

// 🔹 Connexion à la base
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT c.id, e.prenom, e.nom, c.date_debut, c.date_fin, c.raison, c.statut
        FROM conges c
        JOIN employees e ON c.employee_id = e.id
        ORDER BY c.id ASC
    ";
    $stmt = $conn->query($sql);
    $conges = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<table border="1" cellspacing="0" cellpadding="5">';
    echo '<tr style="background-color:#D9E1F2; text-align:center; font-weight:bold;">
            <th>ID</th>
            <th>Employé</th>
            <th>Date début</th>
            <th>Date fin</th>
            <th>Raison</th>
            <th>Statut</th>
          </tr>';

    if (count($conges) > 0) {
        foreach ($conges as $c) {
            echo "<tr>
                    <td>{$c['id']}</td>
                    <td>" . htmlspecialchars(trim($c['prenom'] . ' ' . $c['nom'])) . "</td>
                    <td>{$c['date_debut']}</td>
                    <td>{$c['date_fin']}</td>
                    <td style='text-align:left;'>" . htmlspecialchars($c['raison']) . "</td>
                    <td>{$c['statut']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center;'>Aucun congé trouvé.</td></tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
exit;
