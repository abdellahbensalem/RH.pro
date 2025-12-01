<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
<?php 
$done = isset($_GET['done']) ? (int)$_GET['done'] : null;
$search = $_GET['search'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Historique des Retraites</title>
  <style>
    :root {
      --bg: #f4f6f9;
      --card-bg: #fff;
      --text: #000;
      --accent: #e67e22;
      --accent-hover: #ca6f1e;
      --success: #27ae60;
      --link: #3498db;
      --border: #ddd;
    }

    body.dark {
      --bg: #1e1e1e;
      --card-bg: #2c2c2c;
      --text: #eaeaea;
      --accent: #d35400;
      --accent-hover: #e67e22;
      --success: #2ecc71;
      --link: #2980b9;
      --border: #444;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      color: var(--text);
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      transition: background 0.4s, color 0.4s;
    }
    .card {
      background: var(--card-bg);
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      width: 95%;
      max-width: 1100px;
      transition: background 0.4s, color 0.4s;
    }
    h2 {
      text-align: center;
      color: var(--accent);
      margin-bottom: 20px;
    }
    .btn-auto {
      background: var(--accent);
      color: white;
      border-radius: 8px;
      padding: 8px 14px;
      text-decoration: none;
      border: none;
      transition: background 0.3s;
    }
    .btn-auto:hover { background: var(--accent-hover); }
    .btn-retour {
      display: inline-block;
      padding: 8px 15px;
      margin-top: 15px;
      background: var(--link);
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: background 0.3s;
    }
    .btn-retour:hover { background: #2471a3; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid var(--border);
      padding: 10px;
      text-align: center;
      font-size: 14px;
    }
    th {
      background: var(--accent);
      color: white;
      cursor: pointer;
    }
    tr:nth-child(even) { background: #f9f9f9; }
    body.dark tr:nth-child(even) { background: #2e2e2e; }
    .alert-success {
      background: var(--success);
      color: white;
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>👴 Historique des Retraites Automatiques</h2>

    <?php if ($done !== null): ?>
      <div class="alert-success">✅ <?= $done ?> employé(s) ont été envoyés à la retraite.</div>
    <?php endif; ?>

    <div style="text-align:center; margin-bottom: 15px;">
      <a href="controllers/AutoRetraiteController.php?action=run" class="btn-auto">⚙️ Lancer la retraite automatique</a>
      <a href="index.php?page=dashboard" class="btn-retour">⬅ Retour</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Date de naissance</th>
          <th>Âge</th>
          <th>Date prévue retraite</th>
          <th>Date réelle retraite</th>
          <th>Motif</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($retraites)): ?>
          <?php foreach ($retraites as $r): 
              $dateN = new DateTime($r['date_naissance']);
              $dateRetraitePrevue = (clone $dateN)->modify('+60 years')->format('Y-m-d');
              $age = (new DateTime())->diff($dateN)->y;
          ?>
            <tr>
              <td><?= htmlspecialchars($r['id']) ?></td>
              <td><?= htmlspecialchars($r['nom']) ?></td>
              <td><?= htmlspecialchars($r['prenom']) ?></td>
              <td><?= htmlspecialchars($r['date_naissance']) ?></td>
              <td><?= $age ?> ans</td>
              <td><?= htmlspecialchars($dateRetraitePrevue) ?></td>
              <td><?= htmlspecialchars($r['date_retraite']) ?></td>
              <td><?= htmlspecialchars($r['motif']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8">Aucune retraite trouvée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script>
  // 🌙 Appliquer le thème sauvegardé
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    document.body.classList.add('dark');
  }

  // 🔁 Écoute dynamique si ton dashboard change le thème
  window.addEventListener('storage', (e) => {
    if (e.key === 'theme') {
      if (e.newValue === 'dark') {
        document.body.classList.add('dark');
      } else {
        document.body.classList.remove('dark');
      }
    }
  });
  </script>
</body>
</html>



