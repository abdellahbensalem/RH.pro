<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';
require_once '../models/StatsModel.php';

// Connexion PDO
$stats = new StatsModel($PDO);

// Récupérer les données
$by_sex       = $stats->getBySex();
$by_situation = $stats->getBySituationFamiliale();
$by_age       = $stats->getByAgeGroup();
$sex_by_age   = $stats->getSexByAge();

// Définir les en-têtes Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=statistiques_CNFEPD_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
echo "\xEF\xBB\xBF"; // BOM UTF-8

echo "<html><head><meta charset='UTF-8'></head><body>";

// TITRE PRINCIPAL
echo "<h2 style='text-align:center;color:#2980b9;'>📊 Statistiques CNFEPD</h2><br><br>";

// Style pour centrer le contenu dans toutes les cellules
$tdStyle = "text-align:center; vertical-align:middle;";

// ===========================
//  Feuille 1 : Sexe
// ===========================
echo "<table border='1'>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th colspan='2' style='$tdStyle'>Sexe</th></tr>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th style='$tdStyle'>Sexe</th><th style='$tdStyle'>Total</th></tr>";
if($by_sex){
    foreach($by_sex as $s){
        echo "<tr><td style='$tdStyle'>{$s['sexe']}</td><td style='$tdStyle'>{$s['total']}</td></tr>";
    }
}else{
    echo "<tr><td colspan='2' style='$tdStyle'>Aucune donnée</td></tr>";
}
echo "</table><br><br>";

// ===========================
//  Feuille 2 : Situation familiale
// ===========================
echo "<table border='1'>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th colspan='2' style='$tdStyle'>Situation familiale</th></tr>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th style='$tdStyle'>Situation familiale</th><th style='$tdStyle'>Total</th></tr>";
if($by_situation){
    foreach($by_situation as $s){
        echo "<tr><td style='$tdStyle'>{$s['situation_familiale']}</td><td style='$tdStyle'>{$s['total']}</td></tr>";
    }
}else{
    echo "<tr><td colspan='2' style='$tdStyle'>Aucune donnée</td></tr>";
}
echo "</table><br><br>";

// ===========================
//  Feuille 3 : Tranches d'âge
// ===========================
echo "<table border='1'>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th colspan='2' style='$tdStyle'>Tranches d'âge</th></tr>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th style='$tdStyle'>Tranche d'âge</th><th style='$tdStyle'>Total</th></tr>";
if($by_age){
    foreach($by_age as $a){
        echo "<tr><td style='$tdStyle'>{$a['age_group']}</td><td style='$tdStyle'>{$a['total']}</td></tr>";
    }
}else{
    echo "<tr><td colspan='2' style='$tdStyle'>Aucune donnée</td></tr>";
}
echo "</table><br><br>";

// ===========================
//  Feuille 4 : Sexe par tranche d'âge
// ===========================
$age_groups = ['19-25','26-30','31-35','36-40','41-45','46-50','51+'];

$sexes = [];
$data_sex_age = [];
foreach ($sex_by_age as $r) {
    $s = $r['sexe'] ?? 'Inconnu';
    $g = $r['age_group'];
    $t = (int)$r['total'];
    if (!in_array($s, $sexes)) $sexes[] = $s;
    $data_sex_age[$s][$g] = $t;
}
foreach ($sexes as $s) {
    foreach ($age_groups as $g) {
        if (!isset($data_sex_age[$s][$g])) $data_sex_age[$s][$g] = 0;
    }
}

echo "<table border='1'>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th colspan='".(count($age_groups)+1)."' style='$tdStyle'>Sexe par tranche d'âge</th></tr>";
echo "<tr style='background:#2980b9;color:white;font-weight:bold'><th style='$tdStyle'>Sexe \\ Tranche</th>";
foreach($age_groups as $g) echo "<th style='$tdStyle'>$g</th>";
echo "</tr>";

foreach($sexes as $s){
    echo "<tr>";
    echo "<td style='$tdStyle'>$s</td>";
    foreach($age_groups as $g){
        echo "<td style='$tdStyle'>{$data_sex_age[$s][$g]}</td>";
    }
    echo "</tr>";
}

echo "</table>";

echo "</body></html>";
?>



