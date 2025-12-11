<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['user']['role'] ?? 'user';
$displayName = $_SESSION['user']['nom'] ?? 'Utilisateur';
$currentPage = $_GET['page'] ?? ''; // ✅ Page actuelle
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Menu latéral - GRH</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root { 
  --sidebar-w: 240px; 
  --accent: #2980b9; 
  --card-bg: #fff; 
  --dark-bg: #1e1e2f; 
  --active-bg: #a8bad8ff; /* ✅ Couleur bouton actif */
}
* { box-sizing: border-box; margin:0; padding:0; }
body { 
  font-family:'Segoe UI',sans-serif; 
  background:#f4f6f9; 
  color:#333; 
  transition:0.3s; 
}
body.dark { background:var(--dark-bg); color:#eee; }

/* ✅ Sidebar */
.sidebar { 
  width:var(--sidebar-w); 
  padding:20px; 
  height:100vh; 
  position:fixed; 
  left:0; top:0;
  background:var(--accent); 
  color:white; 
  display:flex; 
  flex-direction:column;
  justify-content:space-between; 
  transition:transform 0.28s ease; 
  z-index:1000; 
  overflow-y:auto; 
  scrollbar-width: thin; 
  scrollbar-color: rgba(255,255,255,0.4) rgba(0,0,0,0.1);
}
.sidebar::-webkit-scrollbar { width:6px; }
.sidebar::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.4); border-radius:4px; }
.sidebar::-webkit-scrollbar-track { background:transparent; }

body.dark .sidebar { background:#21233c; }

.sidebar h2 { text-align:center; margin-bottom:20px; }
.sidebar a {
  display:block; 
  color:white; 
  padding:12px; 
  margin:8px 0; 
  text-decoration:none;
  border-radius:6px; 
  transition:0.25s; 
}
.sidebar a:hover { background:#3498db; transform:translateX(6px); }
body.dark .sidebar a:hover { background:#3a3c5a; transform:none; }

/* ✅ Bouton actif */
.sidebar a.active {
  background: var(--active-bg);
  font-weight: bold;
  transform: translateX(6px);
}
body.dark .sidebar a.active {
  background: #16a085;
}

/* ✅ User info */
.user-info { text-align:center; margin-bottom:25px; }
.user-info img { width:70px; height:70px; border-radius:50%; margin-bottom:10px; border:3px solid #fff; object-fit:cover; background:#fff; }
.user-info h3 { margin:5px 0 0; font-size:16px; }
.user-info p { margin:0; font-size:13px; color:#f0f0f0; }

/* ✅ Logo cliquable */
.logo-link {
  display: inline-block;
  transition: transform 0.2s ease, opacity 0.2s ease;
}
.logo-link:hover img {
  transform: scale(1.05);
  opacity: 0.9;
}


/* ✅ Hamburger */
.hamburger { 
  position: fixed; 
  top:14px; left:14px; 
  font-size:20px; 
  background:var(--accent);
  color:white; 
  padding:10px; 
  border-radius:8px; 
  cursor:pointer; 
  z-index:1100; 
  border:0;
  display:flex; align-items:center; justify-content:center;
  transition:transform .18s, background .18s; 
}
.hamburger.active { transform: rotate(90deg); }
.hamburger:active { transform:scale(.98); }

/* ✅ Dark mode switch */
.switch { position: relative; display:inline-block; width:44px; height:24px; margin:10px 0; }
.switch input { display:none; }
.slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#ccc; transition:0.25s; border-radius:24px; }
.slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background:white; transition:0.25s; border-radius:50%; }
input:checked + .slider { background:var(--accent); }
input:checked + .slider:before { transform:translateX(20px); }

/* ✅ Responsive */
.sidebar.collapsed { transform: translateX(-110%); }
@media (max-width:768px){
  .sidebar { transform:translateX(-110%); }
  .sidebar.active { transform:translateX(0); }
}
</style>
</head>
<body>

<?php if (!in_array($currentPage, ['Conge', 'users'])): ?> 
<button class="hamburger" id="hamb" aria-label="Menu" onclick="toggleSidebar()">
  <i class="fa fa-bars"></i>
</button>
<?php endif; ?>

<div class="sidebar" id="sidebar">
  <div>
    <h2>📊 GRH</h2>
    <div class="user-info">
      <!-- ✅ Logo cliquable vers le dashboard -->
      <a href="index.php?page=dashboard" class="logo-link">
        <img src="/GRH/images/logo.png" alt="LOGO" onerror="this.style.opacity=0.6;">
      </a>
      <h3><?= htmlspecialchars($displayName) ?></h3>
      <p><?= ucfirst($role) ?></p>
    </div>

    <label class="switch" title="Mode sombre">
      <input type="checkbox" id="themeSwitch" onchange="toggleTheme()">
      <span class="slider"></span>
    </label>

    <?php if($role==='admin'): ?>
      <a href="index.php?page=employees" class="<?= ($currentPage==='employees')?'active':'' ?>"><i class="fa fa-users"></i> Employés</a>
      <a href="index.php?page=Conge" class="<?= ($currentPage==='Conge')?'active':'' ?>"><i class="fa fa-calendar"></i> Congés</a>
      <a href="index.php?page=affiche" class="<?= ($currentPage==='affiche')?'active':'' ?>"><i class="fa fa-calendar-check"></i> Liste Congés</a>
      <a href="index.php?page=update_hr" class="<?= ($currentPage==='update_hr')?'active':'' ?>"><i class="fa fa-money-bill"></i> Salaires</a>

      <a href="index.php?page=evaluations" class="<?= ($currentPage==='evaluations')?'active':'' ?>"><i class="fa fa-clipboard-check"></i> Évaluations</a>
      <a href="index.php?page=promotions" class="<?= ($currentPage==='promotions')?'active':'' ?>"><i class="fa fa-chart-line"></i> Promotions</a>
      <a href="index.php?page=stats" class="<?= ($currentPage==='stats')?'active':'' ?>"><i class="fa fa-chart-bar"></i> Statistiques</a>
      <a href="index.php?page=retraites" class="<?= ($currentPage==='retraites')?'active':'' ?>"><i class="fa fa-user-clock"></i> Retraites</a>
       <a href="index.php?page=departements" class="<?= ($currentPage==='departements')?'active':'' ?>"><i class="fa fa-building"></i>Départements</a>
      <a href="index.php?page=fonctions" class="<?= ($currentPage==='fonctions')?'active':'' ?>"><i class="fa fa-briefcase"></i> Fonctions</a>
      <a href="index.php?page=absences" class="<?= ($currentPage==='absences')?'active':'' ?>"><i class="fa fa-user-times"></i> Absences</a>
      <a href="index.php?page=users" class="<?= ($currentPage==='users')?'active':'' ?>"><i class="fa fa-users"></i> Users</a>
      <a href="index.php?page=settings" class="<?= ($currentPage==='settings')?'active':'' ?>"><i class="fa fa-cog"></i> Paramètres</a>
    <?php else: ?>
      <a href="index.php?page=absences" class="<?= ($currentPage==='absences')?'active':'' ?>"><i class="fa fa-user-times"></i> Absences</a>
      <a href="index.php?page=Conge" class="<?= ($currentPage==='Conge')?'active':'' ?>"><i class="fa fa-calendar"></i> Congés</a>
      <a href="index.php?page=affiche" class="<?= ($currentPage==='affiche')?'active':'' ?>"><i class="fa fa-calendar-check"></i> Liste Congés</a>
      <a href="index.php?page=settings" class="<?= ($currentPage==='settings')?'active':'' ?>"><i class="fa fa-cog"></i> Paramètres</a>
    <?php endif; ?>

    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Déconnexion</a>
  </div>
</div>

<script>
// ✅ Thème
if(localStorage.getItem('theme')==='dark'){
  document.body.classList.add('dark');
  const t=document.getElementById('themeSwitch'); if(t) t.checked=true;
}
function toggleTheme(){
  document.body.classList.toggle('dark');
  let theme=document.body.classList.contains('dark')?'dark':'light';
  localStorage.setItem('theme',theme);
}

// ✅ Sidebar (mobile)
function toggleSidebar(){
  const sb=document.getElementById('sidebar');
  const ham=document.getElementById('hamb');
  sb.classList.toggle('active');
  sb.classList.toggle('collapsed');
  ham.classList.toggle('active');
}
</script>
</body>
</html>





