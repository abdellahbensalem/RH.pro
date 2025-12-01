<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Contrôle des mises à jour</title>
<style>
:root {
  --bg-color: #f4f6f9;
  --text-color: #222;
  --card-bg: #fff;
  --shadow-color: rgba(0,0,0,0.1);
  --btn-auto: #2ecc71;
  --btn-manual: #f39c12;
  --btn-cancel: #e74c3c;
}

body.dark {
  --bg-color: #2a2a35;
  --text-color: #eef;
  --card-bg: #1f1f27;
  --shadow-color: rgba(0,0,0,0.6);
  --btn-auto: #27ae60;
  --btn-manual: #e67e22;
  --btn-cancel: #c0392b;
}

body {
  font-family: 'Segoe UI', sans-serif;
  background: var(--bg-color);
  color: var(--text-color);
  margin: 0;
  padding: 20px;
  transition: all 0.3s ease;
}

.container {
  max-width: 600px;
  margin: auto;
  background: var(--card-bg);
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 15px var(--shadow-color);
  text-align: center;
  transition: background 0.3s ease, color 0.3s ease;
}

h2 {
  color: #2980b9;
}

.btn {
  display: block;
  width: 100%;
  margin: 10px 0;
  padding: 12px;
  font-size: 16px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
  color: white;
}

.auto { background: var(--btn-auto); }
.manual { background: var(--btn-manual); }
.cancel { background: var(--btn-cancel); }

.btn:hover {
  opacity: 0.85;
}

.message {
  margin: 15px 0;
  font-weight: bold;
}

.success { color: #27ae60; }
.error { color: #e74c3c; }
.info { color: #555; }
</style>
</head>
<body>
<div class="container">
  <h2>⚙️ Contrôle des mises à jour</h2>

  <?php if (!empty($message)): ?>
    <p class="message 
       <?= (strpos($message,'✅')!==false ? 'success' : 
           (strpos($message,'❌')!==false ? 'error' : 'info')) ?>">
      <?= $message ?>
    </p>
  <?php endif; ?>

  <form action="index.php" method="GET">
    <input type="hidden" name="page" value="update_control">
    <input type="hidden" name="action" value="auto_update">
    <button class="btn auto" type="submit">✅ Appliquer les mises à jour automatiques</button>
  </form>

  <form action="index.php" method="GET">
    <input type="hidden" name="page" value="employees">
    <button class="btn manual" type="submit">✏️ Gérer les employés manuellement</button>
  </form>

  <form action="index.php" method="GET">
    <input type="hidden" name="page" value="dashboard">
    <button class="btn cancel" type="submit">❌ Annuler et revenir au Dashboard</button>
  </form>
</div>

<script>
// ✅ Active automatiquement le thème selon la préférence sauvegardée
document.addEventListener("DOMContentLoaded", () => {
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark');
  }
});
</script>
</body>
</html>



