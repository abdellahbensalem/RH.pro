<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once '../config.php';

/* 🔹 Headers Excel */
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=liste_employes_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

/* 🔹 UTF-8 BOM (important pour l’arabe) */
echo "\xEF\xBB\xBF";

/* 🔹 En-tête */
echo '
<table width="100%" border="0">
<tr>
  <td colspan="40" align="center">
    <h2>CNFEPD</h2>
    <h3>Liste complète des employés</h3>
    <p>Exporté le : '.date("d/m/Y à H:i").'</p>
  </td>
</tr>
</table><br>
';

/* 🔹 Connexion */
$conn = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $user,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

/* 🔹 Requête complète (MIROIR du tableau) */
$sql = "
SELECT 
 e.id, e.matricule, e.nom, e.prenom, e.`الاسم`, e.`اللقب`,
 e.cni_numero, e.nif, e.numero_assurance, e.rib_ccp,
 e.date_naissance, e.lieu_naissance, e.sexe, e.situation_familiale,
 e.nombre_enfants, e.adresse, e.email, e.telephone,
 e.poste, d.nom AS departement_nom,
 e.salaire, e.solde_conge, e.statut,
 e.type_contrat, e.type_statut_contrat,
 e.diplome, e.specialite_diplome, e.niveau_etudes,
 e.structure, e.categorie, e.section, e.echelon,
 e.superieur_hierarchique,
 e.date_embauche, e.date_sortie, e.date_promotion, e.date_derniere_promotion
FROM employees e
LEFT JOIN departements d ON e.departement_id = d.id
ORDER BY e.id ASC
";

$stmt = $conn->query($sql);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* 🔹 Tableau Excel */
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '
<tr style="background:#D9E1F2;font-weight:bold;text-align:center;">
<th>ID</th><th>Matricule</th><th>Nom</th><th>Prénom</th>
<th>الاسم</th><th>اللقب</th>
<th>CNI</th><th>NIF</th><th>Assurance</th><th>RIB</th>
<th>Date naissance</th><th>Lieu naissance</th><th>Sexe</th>
<th>Situation familiale</th><th>Enfants</th><th>Adresse</th>
<th>Email</th><th>Téléphone</th><th>Poste</th><th>Département</th>
<th>Salaire</th><th>Solde congé</th><th>Statut</th>
<th>Type contrat</th><th>Statut contrat</th>
<th>Diplôme</th><th>Spécialité</th><th>Niveau études</th>
<th>Structure</th><th>Catégorie</th><th>Section</th><th>Échelon</th>
<th>Supérieur</th>
<th>Date embauche</th><th>Date sortie</th>
<th>Date promotion</th><th>Dernière promotion</th>
</tr>
';

foreach ($employees as $e) {
    echo "<tr>
<td>{$e['id']}</td>
<td>{$e['matricule']}</td>
<td>{$e['nom']}</td>
<td>{$e['prenom']}</td>
<td>{$e['الاسم']}</td>
<td>{$e['اللقب']}</td>
<td>{$e['cni_numero']}</td>
<td>{$e['nif']}</td>
<td>{$e['numero_assurance']}</td>
<td>{$e['rib_ccp']}</td>
<td>{$e['date_naissance']}</td>
<td>{$e['lieu_naissance']}</td>
<td>{$e['sexe']}</td>
<td>{$e['situation_familiale']}</td>
<td>{$e['nombre_enfants']}</td>
<td>{$e['adresse']}</td>
<td>{$e['email']}</td>
<td>{$e['telephone']}</td>
<td>{$e['poste']}</td>
<td>{$e['departement_nom']}</td>
<td>{$e['salaire']}</td>
<td>{$e['solde_conge']}</td>
<td>{$e['statut']}</td>
<td>{$e['type_contrat']}</td>
<td>{$e['type_statut_contrat']}</td>
<td>{$e['diplome']}</td>
<td>{$e['specialite_diplome']}</td>
<td>{$e['niveau_etudes']}</td>
<td>{$e['structure']}</td>
<td>{$e['categorie']}</td>
<td>{$e['section']}</td>
<td>{$e['echelon']}</td>
<td>{$e['superieur_hierarchique']}</td>
<td>{$e['date_embauche']}</td>
<td>{$e['date_sortie']}</td>
<td>{$e['date_promotion']}</td>
<td>{$e['date_derniere_promotion']}</td>
</tr>";
}

echo '</table>';
exit;





