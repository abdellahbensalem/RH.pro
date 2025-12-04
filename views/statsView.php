<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

// Variables sécurisées provenant du controller
$by_sex       = $by_sex       ?? [];
$by_situation = $by_situation ?? [];
$by_age       = $by_age       ?? [];
$sex_by_age   = $sex_by_age   ?? [];

$total_employees = $total_employees ?? 0;
$total_conges    = $total_conges ?? 0;

// === CALCULS POUR LES POURCENTAGES ===
$sex_labels   = array_column($by_sex, 'sexe');
$sex_totals   = array_column($by_sex, 'total');
$total_sex    = array_sum($sex_totals);
$sex_percents = array_map(fn($t)=> $total_sex>0?round($t*100/$total_sex,1):0, $sex_totals);

$sit_labels   = array_column($by_situation, 'situation_familiale');
$sit_totals   = array_column($by_situation, 'total');
$total_sit    = array_sum($sit_totals);
$sit_percents = array_map(fn($t)=> $total_sit>0?round($t*100/$total_sit,1):0, $sit_totals);

$age_labels   = array_column($by_age, 'age_group');
$age_totals   = array_column($by_age, 'total');
$total_age    = array_sum($age_totals);
$age_percents = array_map(fn($t)=> $total_age>0?round($t*100/$total_age,1):0, $age_totals);

// === SEXE PAR TRANCHE D’ÂGE ===
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Statistiques RH</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; margin:0; padding:20px; color:#2c3e50; }
.container { max-width:1200px; margin:auto; }
h2,h3 { text-align:center; color:#2980b9; margin-bottom:20px; }
.cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:30px; }
.card { background:#fff; padding:20px; border-radius:10px; box-shadow:0 6px 15px rgba(0,0,0,0.08); text-align:center; }
.card h3 { margin-bottom:8px; color:#2980b9; }
.card p { font-size:18px; font-weight:700; margin:0; }
.table { margin-bottom:40px; width:100%; max-width:900px; margin-left:auto; margin-right:auto; }
.table th { background:#2980b9; color:white; }
.table td, .table th { padding:10px; text-align:center; }
canvas { display:block; margin: 20px auto; max-width:900px; }
.btn-retour { display:inline-block; padding:8px 15px; margin:15px auto; background:#3498db; color:white; text-decoration:none; border-radius:6px; font-size:14px; }
body.dark { background:#121212; color:#eaeaea; }
body.dark .card { background:#1f1f1f; color:#eaeaea; }
body.dark .table th { background:#0d4b5a; }
</style>
</head>
<body>

<div class="container">
  <h2>📊 Dashboard Statistiques RH</h2>
  <div style="text-align:center; margin-bottom:20px;">
    <a href="excel/export_stats_excel.php" class="btn btn-success">
        📥 Exporter en Excel
    </a>
</div>


  <!-- CARDS PRINCIPALES -->
  <div class="cards">
    <div class="card"><h3>Total employés</h3><p><?= $total_employees ?></p></div>
    <div class="card"><h3>Total congés</h3><p><?= $total_conges ?></p></div>
  </div>

  <!-- SEXE -->
  <hr>
  <h3>👨‍👩‍👧‍👦 Employés par sexe</h3>
  <div class="cards">
    <?php foreach ($sex_labels as $i => $s): ?>
      <div class="card">
        <h3><?= $s ?></h3>
        <p><?= $sex_totals[$i] ?> (<?= $sex_percents[$i] ?>%)</p>
      </div>
    <?php endforeach; ?>
  </div>
  <table class="table table-bordered">
    <thead><tr><th>Sexe</th><th>Total</th><th>Pourcentage</th></tr></thead>
    <tbody>
      <?php foreach ($sex_labels as $i => $s): ?>
      <tr><td><?= $s ?></td><td><?= $sex_totals[$i] ?></td><td><?= $sex_percents[$i] ?>%</td></tr>
      <?php endforeach; ?>
      <?php if(count($sex_labels)===0): ?><tr><td colspan="3">Aucune donnée</td></tr><?php endif; ?>
    </tbody>
  </table>

  <!-- SITUATION FAMILIALE -->
  <hr>
  <h3>💍 Employés par situation familiale</h3>
  <div class="cards">
    <?php foreach ($sit_labels as $i => $s): ?>
      <div class="card">
        <h3><?= $s ?></h3>
        <p><?= $sit_totals[$i] ?> (<?= $sit_percents[$i] ?>%)</p>
      </div>
    <?php endforeach; ?>
  </div>
  <table class="table table-bordered">
    <thead><tr><th>Situation familiale</th><th>Total</th><th>Pourcentage</th></tr></thead>
    <tbody>
      <?php foreach ($sit_labels as $i => $s): ?>
      <tr><td><?= $s ?></td><td><?= $sit_totals[$i] ?></td><td><?= $sit_percents[$i] ?>%</td></tr>
      <?php endforeach; ?>
      <?php if(count($sit_labels)===0): ?><tr><td colspan="3">Aucune donnée</td></tr><?php endif; ?>
    </tbody>
  </table>

  <!-- TRANCHE D’ÂGE -->
  <hr>
  <h3>🎂 Répartition par tranche d’âge</h3>
  <table class="table table-bordered">
    <thead><tr><th>Tranche d'âge</th><th>Total</th><th>Pourcentage</th></tr></thead>
    <tbody>
      <?php foreach($age_labels as $i => $g): ?>
      <tr><td><?= $g ?></td><td><?= $age_totals[$i] ?></td><td><?= $age_percents[$i] ?>%</td></tr>
      <?php endforeach; ?>
      <?php if(count($age_labels)===0): ?><tr><td colspan="3">Aucune donnée</td></tr><?php endif; ?>
    </tbody>
  </table>

  <!-- SEXE PAR TRANCHE D’ÂGE -->
  <hr>
  <h3>🧮 Sexe par tranche d'âge</h3>
  <table class="table table-bordered">
    <thead>
      <tr><th>Sexe \ Tranche</th>
        <?php foreach($age_groups as $g): ?><th><?= $g ?></th><?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($sexes as $s): ?>
      <tr>
        <td><?= $s ?></td>
        <?php foreach($age_groups as $g): ?>
        <td><?= $data_sex_age[$s][$g] ?></td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <canvas id="chartSexAge"></canvas>

  <div style="text-align:center; margin-top:18px;">
    <a href="index.php?page=dashboard" class="btn-retour">⬅ Retour</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ageGroups = <?= json_encode($age_groups) ?>;
const sexes = <?= json_encode($sexes) ?>;
const dataSexAge = <?= json_encode($data_sex_age) ?>;

const datasets = sexes.map((s, idx) => ({
  label: s,
  data: ageGroups.map(g => dataSexAge[s][g] ?? 0),
  backgroundColor: ['#e45090ff','#1585d0ff','#9b59b6','#2ecc71','#f1c40f'][idx % 5]
}));

new Chart(document.getElementById('chartSexAge'), {
  type: 'bar',
  data: { labels: ageGroups, datasets },
  options: { responsive:true, scales:{ x:{stacked:true}, y:{stacked:true, beginAtZero:true} }, plugins:{legend:{position:'bottom'}} }
});

// dark mode
if(localStorage.getItem('theme')==='dark') document.body.classList.add('dark');
</script>
</body>
</html>






