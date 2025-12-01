<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user'])) {
    die("Accès refusé : veuillez vous connecter.");
}

$user = $_SESSION['user'];
$role = $user['role'];
$employee_id = ($role === 'admin' && isset($_GET['id'])) ? (int)$_GET['id'] : (int)$user['employee_id'];
$lang = $_GET['lang'] ?? 'fr';

$fonction_ar = [
 'Directeur Général' => 'المدير العام',
    'Directeur Central' => 'المدير المركزي',
    'Directeur Adjoint' => 'المدير المساعد',
    'Directeur Régional' => 'المدير الجهوي',
    'Chef de Service' => 'رئيس المصلحة',
    'Cadre Adjoint Pédagogique' => 'إطار بيداغوجي مساعد',
    'Cadre Adjoint Financier et Comptable' => 'إطار مالي ومحاسبي مساعد',
    'Cadre Adjoint Technique et Commercial' => 'إطار تقني وتجاري مساعد',
    'Cadre Adjoint Administratif' => 'إطار إداري مساعد',
    'Agent Administratif Principal' => 'عون إداري رئيسي',
    'Assistant Principal Pédagogique' => 'مساعد بيداغوجي رئيسي',
    'Assistant Principal Financier et Comptable' => 'مساعد مالي ومحاسبي رئيسي',
    'Assistant Principal Technique et Commercial' => 'مساعد تقني وتجاري رئيسي',
    'Assistant Principal Administratif' => 'مساعد إداري رئيسي',
    'Assistant Pédagogique' => 'مساعد بيداغوجي',
    'Assistant Financier et Comptable' => 'مساعد مالي ومحاسبي',
    'Assistant Technique et Commercial' => 'مساعد تقني وتجاري',
    'Assistant Administratif' => 'مساعد إداري',
    'Agent Pédagogique' => 'عون بيداغوجي',
    'Agent Financier et Comptable' => 'عون مالي ومحاسبي',
    'Agent Technique et Commercial' => 'عون تقني وتجاري',
    'Agent Administratif' => 'عون إداري',
    'Chauffeur' => 'سائق',
    'Standardiste' => 'مشغل الهاتف',
    'Agent Polyvalent' => 'عون متعدد المهام',
    'Agent de Contrôle' => 'عون مراقبة',
    'Agent de Nettoyage' => 'عامل تنظيف',
    'Cadre Pédagogique' => 'إطار بيداغوجي',
    'Cadre Financier et Comptable' => 'إطار مالي ومحاسبي',
    'Cadre Technique et Commercial' => 'إطار تقني وتجاري',
    'Cadre Administratif' => 'إطار إداري',
    'Chef de Section' => 'رئيس مصلحة',
    'Chef de Branche' => 'رئيس فرع'
    // ... ajouter toutes les fonctions nécessaires
];

$sql = "SELECT e.*, f.nom_fonction FROM employees e LEFT JOIN fonctions f ON e.fonction_id=f.id WHERE e.id=?";
$stmt = $PDO->prepare($sql);
$stmt->execute([$employee_id]);
$emp = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$emp) die($lang=='ar' ? "الموظف غير موجود." : "Employé introuvable.");

$sql_prom = "SELECT f.nom_fonction, p.date_promotion FROM promotions p JOIN fonctions f ON p.nouvelle_fonction_id=f.id WHERE p.employee_id=? ORDER BY p.date_promotion ASC";
$stmt_prom = $PDO->prepare($sql_prom);
$stmt_prom->execute([$employee_id]);
$history = $stmt_prom->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['default_font'=>'dejavusans','mode'=>'utf-8','format'=>'A4']);

if($lang=='ar'){
    $dir="rtl"; $align="right"; $title="شهادة عمل"; $company="CNFEPD";
    $text_intro="تشهد إدارة شركة <b>$company</b> بموجب هذه الوثيقة أن:";
    $label_nom="الاسم"; $label_prenom="اللقب"; $label_fonction="الوظيفة الحالية";
    $label_date="تاريخ الالتحاق"; $text_final="قد كان/ت موظفًا لدينا إلى غاية يومنا هذا. سلمت هذه الشهادة للمعني بالأمر لإستعمالها عند الحاجة.";
    $label_history="الوظائف السابقة في الشركة"; $date_now_label="التاريخ"; $signature="المدير العام";
} else {
    $dir="ltr"; $align="left"; $title="ATTESTATION DE TRAVAIL"; $company="CNFEPD";
    $text_intro="Nous, Direction Générale de <b>$company</b>, attestons que :";
    $label_nom="Nom"; $label_prenom="Prénom"; $label_fonction="Fonction actuelle";
    $label_date="Date d'entrée"; $text_final="Travaille au sein de l’établissement jusqu’à ce jour. Attestation délivrée sur demande.";
    $label_history="Fonctions précédentes dans l'entreprise"; $date_now_label="Date"; $signature="Le Directeur Général";
}

$html='
<style>
body { font-family: DejaVu Sans; direction: '.$dir.'; text-align: '.$align.'; margin: 40px; }
.header { text-align:center; border-bottom:3px solid #000; padding-bottom:10px; margin-bottom:20px; }
.header img { height:80px; margin-bottom:10px; }
.title { font-size:24px; font-weight:bold; margin-bottom:20px; text-align:center; }
.card { border:1px solid #000; padding:20px; border-radius:8px; }
.section { margin-top:15px; }
.table { width:100%; border-collapse:collapse; margin-top:10px; }
.table th, .table td { border:1px solid #000; padding:6px; font-size:12px; text-align:'.$align.'; }
.footer { margin-top:50px; text-align:center; font-size:14px; }
</style>

<div class="header">
<img src="/GRH/images/logo.png">
<div>'.$company.'</div>
</div>

<div class="title">'.$title.'</div>

<div class="card">
<p>'.$text_intro.'</p>
<p>
<b>'.$label_nom.' :</b> '.$emp["nom"].'<br>
<b>'.$label_prenom.' :</b> '.$emp["prenom"].'<br>
<b>'.$label_fonction.' :</b> '.($lang=='ar' ? ($fonction_ar[$emp["nom_fonction"]] ?? $emp["nom_fonction"]) : $emp["nom_fonction"]).'<br>
<b>'.$label_date.' :</b> '.$emp["date_embauche"].'
</p>
<p>'.$text_final.'</p>
';

// Historique promotions en tableau
if($history){
    $html.='<div class="section"><b>'.$label_history.'</b>
    <table class="table"><tr><th>#</th><th>'.($lang=='ar'?"الوظيفة":"Fonction").'</th><th>'.($lang=='ar'?"تاريخ الترقية":"Date promotion").'</th></tr>';
    $i=1;
    foreach($history as $h){
        $fn=($lang=='ar' ? ($fonction_ar[$h['nom_fonction']] ?? $h['nom_fonction']) : $h['nom_fonction']);
        $html.='<tr><td>'.$i.'</td><td>'.$fn.'</td><td>'.$h['date_promotion'].'</td></tr>';
        $i++;
    }
    $html.='</table></div>';
}

$html.='
<div class="footer">
<p><b>'.$date_now_label.' :</b> '.date("d/m/Y").'</p>
<p><b>'.$signature.'</b></p>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output("attestation_travail.pdf","I");










