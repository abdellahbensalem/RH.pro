<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';

// Définir les en-têtes Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=promotions_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Connexion base
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Requête pour récupérer les promotions
$sql = "SELECT p.id, e.prenom, e.nom, p.ancien_poste, p.nouveau_poste, 
        p.ancien_grade, p.nouveau_grade, p.date_promotion, p.motif
        FROM promotions p
        JOIN employees e ON p.employee_id = e.id
        ORDER BY p.date_promotion DESC";

$result = $conn->query($sql);

// Afficher le tableau
echo "<table border='1'>";
echo "<tr style='background:#2980b9;color:white'>
<th>ID</th><th>Employé</th><th>Ancien poste</th><th>Nouveau poste</th>
<th>Ancien grade</th><th>Nouveau grade</th><th>Date</th><th>Motif</th></tr>";

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['prenom']} {$row['nom']}</td>";
        echo "<td>{$row['ancien_poste']}</td>";
        echo "<td>{$row['nouveau_poste']}</td>";
        echo "<td>{$row['ancien_grade']}</td>";
        echo "<td>{$row['nouveau_grade']}</td>";
        echo "<td>{$row['date_promotion']}</td>";
        echo "<td>{$row['motif']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Aucune donnée trouvée</td></tr>";
}
echo "</table>";

$conn->close();
