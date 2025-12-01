<?php
session_start();

// 🔹 Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once '../config.php'; // Connexion à la base de données

// 🔹 En-tête HTTP pour Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=liste_employes_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 🔹 BOM UTF-8 pour Excel
echo "\xEF\xBB\xBF";

// 🔹 En-tête centré
echo '
<table border="0" width="100%" style="border:none; text-align:center;">
<tr>
    <td colspan="12" style="text-align:center; vertical-align:middle;">
        <h2>CNFEPD - Centre National de Formation et d’Enseignement Professionnel à Distance</h2>
        <h3>Liste des employés</h3>
        <p style="font-size:12px;">Exporté le : ' . date("d/m/Y à H:i") . '</p>
    </td>
</tr>
</table>
<br>
';

// 🔹 Table des employés
echo '<table border="1" cellspacing="0" cellpadding="5">';
echo '<tr style="background-color:#D9E1F2; text-align:center; font-weight:bold;">
<th>ID</th><th>Matricule</th><th>Nom</th><th>Prénom</th><th>Date de naissance</th><th>Email</th>
<th>Téléphone</th><th>Poste</th><th>Département</th><th>Salaire (DA)</th><th>Solde Congé</th>
</tr>';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT e.id, e.matricule, e.nom, e.prenom, e.date_naissance,
               e.email, e.telephone, e.poste,
               d.nom AS departement_nom,
               e.salaire,  e.solde_conge
        FROM employees e
        LEFT JOIN departements d ON e.departement_id = d.id
        ORDER BY e.id ASC
    ";
    $stmt = $conn->query($sql);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($employees) > 0) {
        foreach ($employees as $emp) {
            echo "<tr>
                    <td>{$emp['id']}</td>
                    <td>{$emp['matricule']}</td>
                    <td>{$emp['nom']}</td>
                    <td>{$emp['prenom']}</td>
                    <td>{$emp['date_naissance']}</td>
                    <td>{$emp['email']}</td>
                    <td>{$emp['telephone']}</td>
                    <td>{$emp['poste']}</td>
                    <td>{$emp['departement_nom']}</td>
                    <td>{$emp['salaire']}</td>
                    <td>{$emp['solde_conge']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='12' style='text-align:center;'>Aucun employé trouvé.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='12'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}

echo '</table>';
exit;




