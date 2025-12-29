<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

if (!isset($_GET['id'])) {
    die("❌ ID du congé non fourni !");
}

$id = (int)$_GET['id'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=rh_pro_db;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔍 Récupération du congé + employé + type + fonction
    $sql = "SELECT 
                c.*, 
                e.الاسم, e.اللقب, e.date_naissance, e.lieu_naissance, e.solde_conge,
                f.nom_fonction,
                t.nom_type
            FROM conges c
            LEFT JOIN employees e ON c.employee_id = e.id
            LEFT JOIN fonctions f ON e.fonction_id = f.id
            LEFT JOIN type_conge t ON c.type_conge_id = t.id
            WHERE c.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("❌ Congé introuvable dans la base !");
    }

    // 🧮 Calculs
    $dateDebut = new DateTime($data['date_debut']);
    $dateFin = new DateTime($data['date_fin']);
    $duree = $dateDebut->diff($dateFin)->days + 1;
    $dateReprise = (clone $dateFin)->modify('+1 day');
    $soldeRestant = $data['solde_conge'];


    // Année du congé (ex: 2019/2020)
    $anneeConge = $dateDebut->format('Y') . "/" . $dateFin->format('Y');

    // Numéro du document
    $anneeDoc = date('Y');
    $numDoc = "م/إ/م/ب/{$anneeDoc}";

    // 🔤 Traductions en arabe
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
    ];

    $type_conge_ar = [
        'Annuel' => 'سنوي',
        'Maladie' => 'مرض',
        'Exceptionnel' => 'استثنائي',
        'Sans solde' => 'بدون أجر',
        'Maternité' => 'أمومة'
    ];

    // 🔄 Remplacement français -> arabe
    $nom_fonction_ar = $fonction_ar[$data['nom_fonction']] ?? $data['nom_fonction'];
    $nom_type_ar = $type_conge_ar[$data['nom_type']] ?? $data['nom_type'];

    // 🧾 Contenu HTML
    $html = "
<html lang='ar' dir='rtl'>
<head>
<meta charset='UTF-8'>
<style>
    body {
        font-family: 'dejavusans', sans-serif;
        direction: rtl;
        text-align: right;
        line-height: 1.8;
        font-size: 15px;
        margin: 60px;
    }
    .header { width: 100%; margin-bottom: 20px; }
    .header td { width: 50%; vertical-align: top; }
    .title { text-align: center; font-size: 22px; font-weight: bold; text-decoration: underline; margin-top: 30px; }
    .subtitle { text-align: center; font-size: 18px; font-weight: bold; text-decoration: underline; }
    .section { margin-top: 25px; text-align: right; }
    .footer { margin-top: 30px; text-align: left; }
</style>
</head>
<body>

<table class='header'>
<tr>
<td style='text-align:right;'>الرقم: {$numDoc}</td>
<td style='text-align:left;'>الجزائر في: " . date('Y/m/d') . "</td>
</tr>
</table>

<h2 class='title'>سـنــد عـطــلــة</h2>
<h3 class='subtitle'>{$nom_type_ar} {$anneeConge}</h3>

<div class='section'>
<p>تمنح إلى السيد(ة): <strong>{$data['الاسم']} {$data['اللقب']}</strong></p>
<p>المولود(ة) في: <strong>{$data['date_naissance']}</strong> بـ <strong>{$data['lieu_naissance']}</strong></p>
<p>الوظيفة: <strong>{$nom_fonction_ar}</strong></p>
<p>طبيعة العطلة: <strong>{$nom_type_ar} {$anneeConge}</strong></p>
<p>مدتها: <strong>{$duree}</strong> يوما</p>
<p>ابتداء من تاريخ: <strong>{$data['date_debut']}</strong> إلى غاية: <strong>{$data['date_fin']}</strong></p>
<p>يستأنف عمله بتاريخ: <strong>{$dateReprise->format('Y-m-d')}</strong></p>
<p>يحتفظ المعني بالأمر ببقية إجازة قدرها (<strong>{$soldeRestant}</strong>) يوما لحساب سنة {$anneeConge}</p>
</div>

<div class='footer'>
<p>الإمضاء :</p>
</div>

</body>
</html>
";

    // 📄 Création de l’objet mPDF
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'default_font' => 'dejavusans',
        'orientation' => 'P',
        'margin_top' => 15,
        'margin_bottom' => 15,
        'margin_left' => 20,
        'margin_right' => 20
    ]);

    // 🖨️ Génération du PDF
    $mpdf->WriteHTML($html);
    $mpdf->Output("سند_عطلة_{$data['الاسم']}_{$data['اللقب']}.pdf", 'I');

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>








