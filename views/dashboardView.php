<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Tableau de bord - GRH</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root { --sidebar-w: 240px; --accent: #2980b9; --card-bg: #fff; --dark-bg: #1e1e2f; }
* { box-sizing: border-box; }
body {margin:0; font-family:'Segoe UI',sans-serif; display:flex; background:#f4f6f9; transition:0.3s;}
body.dark {background:var(--dark-bg); color:#eee;}

/* ✅ Sidebar avec scroll */
.sidebar { 
  width:var(--sidebar-w); 
  padding:20px;
  height:100vh;
  position:fixed;
  left:0; 
  top:0; 
  background:var(--accent); 
  color:white; 
  display:flex; 
  flex-direction:column;
  justify-content:space-between; 
  transition:transform 0.28s ease, box-shadow 0.28s;
  transform: translateX(0); 
  z-index:1000;
  overflow-y: auto; /* ✅ Scroll vertical */
  scrollbar-width: thin; 
  scrollbar-color: #ffffff33 transparent;
}
.sidebar::-webkit-scrollbar { width: 6px; }
.sidebar::-webkit-scrollbar-thumb { background: #ffffff55; border-radius: 10px; }
.sidebar::-webkit-scrollbar-thumb:hover { background: #ffffff88; }

body.dark .sidebar { background:#21233c; }
.sidebar h2 {text-align:center; margin-bottom:20px;}
.sidebar a {
  display:block; color:white; padding:12px; margin:8px 0; text-decoration:none;
  border-radius:6px; transition:0.25s;
}
.sidebar a:hover { background:#3498db; transform:translateX(6px); }
body.dark .sidebar a:hover { background:#3a3c5a; transform:none; }

/* User info */
.user-info { text-align:center; margin-bottom:25px; }
.user-info img { width:70px; height:70px; border-radius:50%; margin-bottom:10px;
  border:3px solid #fff; object-fit:cover; background:#fff; }
.user-info h3 { margin:5px 0 0; font-size:16px; }
.user-info p { margin:0; font-size:13px; color:#f0f0f0; }

/* Main content */
.main { 
  margin-left: calc(var(--sidebar-w) + 20px); 
  padding:20px; width:100%;
  transition:margin-left 0.28s ease; 
}
@media (max-width: 1024px) { .main { margin-left: calc(var(--sidebar-w) + 16px); } }

/* Collapsed state */
.sidebar.collapsed { transform: translateX(-110%); box-shadow:none; }
.main.collapsed { margin-left: 20px; }

/* Cards / stats / charts */
.cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:20px; margin-top:20px; }
.card { background:var(--card-bg); padding:20px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.08);
  text-align:center; transition:transform .18s, box-shadow .18s; color:inherit; text-decoration:none; display:block; }
body.dark .card { background:#2b2b3a; }
.card:hover { transform:translateY(-6px); box-shadow:0 10px 30px rgba(0,0,0,0.12); }
.card i { font-size:36px; color:var(--accent); margin-bottom:10px; }

.stats-summary { display:flex; gap:20px; margin-top:20px; flex-wrap:wrap; }
.stat-box { flex:1; min-width:150px; background:var(--card-bg); padding:15px; border-radius:10px; text-align:center;
  box-shadow:0 4px 12px rgba(0,0,0,0.08); transition:transform .18s; }
body.dark .stat-box { background:#2b2b3a; }
.stat-box h2 { margin:0; font-size:24px; color:var(--accent); }
.stat-box p { margin:6px 0 0; font-size:14px; color:#666; }
body.dark .stat-box p { color:#ccc; }

.charts { margin-top:30px; display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:20px; }
.chart-box { background:var(--card-bg); padding:18px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.08); }
body.dark .chart-box { background:#2b2b3a; }

/* Hamburger button */
.hamburger { 
  position: fixed; top: 14px; left: 14px; font-size:20px;
  background:var(--accent); color:white; padding:10px; border-radius:8px;
  cursor:pointer; z-index:1100; border:0; display:flex; align-items:center;
  justify-content:center; transition:transform .18s, background .18s; 
}
.hamburger:active { transform:scale(.98); }
.hamburger.active { transform: rotate(90deg); }

/* Dark-mode switch */
.switch { position: relative; display: inline-block; width: 44px; height: 24px; margin:10px 0; }
.switch input { display:none; }
.slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#ccc; transition:0.25s; border-radius:24px; }
.slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px;
  background:white; transition:0.25s; border-radius:50%; }
input:checked + .slider { background:var(--accent); }
input:checked + .slider:before { transform:translateX(20px); }

/* Responsive */
@media (max-width: 768px) {
  .sidebar { transform: translateX(-110%); }
  .sidebar.active { transform: translateX(0); }
  .main { margin-left: 20px; }
  .main.collapsed { margin-left: 20px; }
}
</style>
</head>
<body>

<!-- Hamburger -->
<button class="hamburger" id="hamb" aria-label="Ouvrir menu" onclick="toggleSidebar()">
  <i class="fa fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
  <div>
    <h2>📊 SIRH</h2>
    <div class="user-info">
     <a href="index.php?page=dashboard">
  <img src="/GRH/images/logo.png" alt="LOGO" onerror="this.style.opacity=0.6;">
</a>

      <h3><?= htmlspecialchars($displayName) ?></h3>
      <p><?= ucfirst($role) ?></p>
    </div>

    <!-- dark mode switch -->
    <label class="switch" title="Mode sombre">
      <input type="checkbox" id="themeSwitch" onchange="toggleTheme()">
      <span class="slider"></span>
    </label>

    <?php if($role==='admin'): ?>
      <a href="index.php?page=employees"><i class="fa fa-users"></i> Employés</a>
      <a href="index.php?page=Conge" ><i class="fa fa-calendar"></i> Congés</a>
      <a href="index.php?page=affiche"><i class="fa fa-calendar-check"></i> Liste Congés</a>
      <a href="index.php?page=update_hr"><i class="fa fa-money-bill"></i> Salaires</a>
      <a href="index.php?page=evaluations"><i class="fa fa-clipboard-check"></i> Évaluations</a>
      <a href="index.php?page=promotions" ><i class="fa fa-chart-line"></i>Promotions</a>
      <a href="index.php?page=stats"><i class="fa fa-chart-bar"></i> Statistiques</a>
      <a href="index.php?page=retraites"><i class="fa fa-user-clock"></i> Retraites</a>
      <a href="index.php?page=absences"><i class="fa fa-user-times"></i> absences</a>
      <a href="index.php?page=fonctions" ><i class="fa fa-briefcase"></i> Fonctions</a>
       <a href="index.php?page=departements" ><i class="fa fa-building"></i> Départements</a>
      <a href="index.php?page=users"><i class="fa fa-users"></i> Users</a>
      <a href="index.php?page=settings"><i class="fa fa-cog"></i> Paramètres</a>
    <?php else: ?>
      <a href="index.php?page=absences"><i class="fa fa-user-times"></i> absences</a>
      <a href="index.php?page=Conge"><i class="fa fa-calendar"></i> Congés</a>
      <a href="index.php?page=affiche"><i class="fa fa-calendar-check"></i> Liste Congés</a>
      <a href="index.php?page=settings"><i class="fa fa-cog"></i> Paramètres</a>
    <?php endif; ?>
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Déconnexion</a>
  </div>
</div>

<div class="main" id="mainContent">
  <h1>Bienvenue <?= htmlspecialchars($displayName) ?></h1>
<?php if (!empty($proches_retraite)): ?>
  <div style="background:#f39c12; color:white; padding:12px; border-radius:8px; margin-bottom:15px;">
    ⚠️ <strong><?= $nb_proches ?></strong> employé(s) atteindront l’âge de la retraite dans les 6 prochains mois :
    <ul style="margin:8px 0 0 20px;">
      <?php foreach ($proches_retraite as $emp): 
          $datePrevue = (new DateTime($emp['date_naissance']))->modify('+60 years')->format('Y-m-d');
      ?>
        <li><?= htmlspecialchars($emp['nom']) ?> <?= htmlspecialchars($emp['prenom']) ?> — départ prévu le <strong><?= $datePrevue ?></strong></li>
      <?php endforeach; ?>
    </ul>

    <div style="margin-top:10px;">
      <a href="controllers/AutoRetraiteController.php?action=run" 
         style="background:#27ae60;color:white;padding:8px 14px;border-radius:6px;text-decoration:none;">
         ⚙️ Lancer la retraite automatique
      </a>
    </div>
  </div>
<?php endif; ?>

  <!-- ✅ Date + heure -->
  <div id="dateBox" style="margin-top:10px; font-size:16px; font-weight:500; color:#555;"></div>

  <!-- Résumé statistiques -->
  <div class="stats-summary">
    <div class="stat-box"><h2><?= $totalEmployees ?></h2><p>Employés</p></div>
    <div class="stat-box"><h2><?= $pendingLeaves ?></h2><p>Congés en attente</p></div>
    <div class="stat-box"><h2><?= $totalSalariesFormatted ?></h2><p>Salaires</p></div>
  </div>

  <!-- Cartes -->
  <div class="cards">
    <?php if($role==='admin'): ?>
      <a href="index.php?page=employees" class="card"><i class="fa fa-users"></i><h3>Employés</h3><p>Gérer et consulter les employés</p></a>
      <a href="index.php?page=Conge"  class="card"><i class="fa fa-calendar"></i><h3>Congés</h3><p>Suivi des demandes de congés</p></a>
      <a href="index.php?page=affiche" class="card"><i class="fa fa-calendar-check"></i><h3>Liste Congés</h3><p>Affiche les congés</p></a>
      <a href="index.php?page=update_hr" class="card"><i class="fa fa-money-bill"></i><h3>Salaires</h3><p>Gestion des salaires et primes</p></a>
      <a href="index.php?page=evaluations" class="card"><i class="fa fa-clipboard-check"></i><h3>Évaluations</h3><p>Suivi des évaluations des employés</p></a> <!-- ✅ AJOUT -->
      <a href="index.php?page=stats" class="card"><i class="fa fa-chart-bar"></i><h3>Statistiques</h3><p>Rapports et analyses RH</p></a>
      <a href="index.php?page=users" class="card"><i class="fa fa-users"></i><h3>Users</h3><p>Gestion des utilisateurs</p></a>
      <a href="index.php?page=promotions" class="card"><i class="fa fa-chart-line"></i><h3>Promotions</h3><p>Historique et gestion des promotions</p></a>
      <a href="index.php?page=retraites" class="card"><i class="fa fa-user-clock"></i><h3>Retraites</h3><p>Suivi des départs automatiques</p></a>
      <a href="index.php?page=absences" class="card"><i class="fa fa-user-times"></i><h3>Absences</h3><p>Gestion des absences des employés</p></a>
      <a href="index.php?page=departements" class="card"><i class="fa fa-building"></i><h3>Départements</h3><p>Gérer les départements et visualiser les employés rattachés</p></a>
      <a href="index.php?page=fonctions" class="card"><i class="fa fa-briefcase"></i><h3>Fonctions</h3><p>Gestion des fonctions et grades</p></a>
      <a href="index.php?page=settings"class="card"><i class="fa fa-cog"></i><h3>Paramètres</h3><p>Gestion des Paramètres</p></a>
      <?php else: ?>
      <a href="index.php?page=absences" class="card"><i class="fa fa-user-times"></i><h3>Absences</h3><p>Gestion des absences des employés</p></a>
      <a href="index.php?page=Conge"  class="card"><i class="fa fa-calendar"></i><h3>Congés</h3><p>Demander un congé</p></a>
      <a href="index.php?page=affiche" class="card"><i class="fa fa-calendar-check"></i><h3>Liste Congés</h3><p>Affiche les congés</p></a>
      <a href="index.php?page=settings"class="card"><i class="fa fa-cog"></i><h3>Paramètres</h3><p>Gestion des Paramètres</p></a>
            <a href="attestation_travail.php?id=<?= $_SESSION['user_id'] ?>&lang=fr" class="card" target="_blank">
          <i class="fa fa-file-alt"></i><h3>Attestation FR</h3>
      </a>
      <a href="attestation_travail.php?id=<?= $_SESSION['user_id'] ?>&lang=ar" class="card" target="_blank">
          <i class="fa fa-file-alt"></i><h3>Attestation AR</h3>
      </a>
    <?php endif; ?>
  </div>

  <!-- Graphiques -->
  <div class="charts">
    <div class="chart-box">
      <h3>Répartition des employés par département</h3>
      <canvas id="deptChart"></canvas>
    </div>
    <div class="chart-box">
      <h3>Statut des congés</h3>
      <canvas id="leaveChart"></canvas>
    </div>
  </div>
</div>

<script>
// restore theme
if(localStorage.getItem('theme')==='dark') {
  document.body.classList.add('dark');
  const t = document.getElementById('themeSwitch'); if(t) t.checked = true;
}
// toggle dark
function toggleTheme(){
  document.body.classList.toggle('dark');
  let theme = document.body.classList.contains('dark') ? 'dark' : 'light';
  localStorage.setItem('theme', theme);
}
// Sidebar toggle
function toggleSidebar(){
  const sb = document.getElementById('sidebar');
  const main = document.getElementById('mainContent');
  const ham = document.getElementById('hamb');
  sb.classList.toggle('active');
  sb.classList.toggle('collapsed');
  main.classList.toggle('collapsed');
  ham.classList.toggle('active');
}
// close sidebar mobile
document.addEventListener('click', function(e){
  const sb = document.getElementById('sidebar');
  const ham = document.getElementById('hamb');
  const isClickInside = sb.contains(e.target) || ham.contains(e.target);
  if (!isClickInside && window.innerWidth <= 768 && sb.classList.contains('active')) {
    toggleSidebar();
  }
});
// Chart.js
const deptCtx = document.getElementById('deptChart');
if (deptCtx) {
  new Chart(deptCtx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($departements) ?>,
      datasets: [{
        label: 'Employés',
        data: <?= json_encode($deptCounts) ?>,
        backgroundColor: 'rgba(41,128,185,0.9)'
      }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
  });
}
const leaveCtx = document.getElementById('leaveChart');
if (leaveCtx) {
  new Chart(leaveCtx, {
    type: 'doughnut',
    data: {
      labels: <?= json_encode($leaveStatus) ?>,
      datasets: [{
        data: <?= json_encode($leaveCounts) ?>,
        backgroundColor: ['#27ae60','#e67e22','#c0392b']
      }]
    },
    options: { responsive: true }
  });
}

// ✅ Date + heure en temps réel
function updateDateBox() {
  const dateBox = document.getElementById('dateBox');
  if (!dateBox) return;

  const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  const now = new Date();

  const dateStr = now.toLocaleDateString('fr-FR', optionsDate);
  const timeStr = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

  dateBox.textContent = `${dateStr} - ${timeStr}`;
}
updateDateBox();
setInterval(updateDateBox, 1000);
</script>
</body>
</html>

