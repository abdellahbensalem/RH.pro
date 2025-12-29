<?php include 'views/sidebar.php'; ?>
<div class="main" id="mainContent">
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Mise à jour RH automatique</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 20px; margin: 0; }
.container { max-width: 1100px; margin: 30px auto; background: #fff; padding: 30px;
  border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
h2 { text-align: center; color: #2980b9; }
.flash { background: #e8f5e9; border-left: 5px solid #2ecc71; color: #2e7d32;
  padding: 10px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
form { text-align: center; margin-bottom: 30px; }
input[type="password"] { padding: 10px; border-radius: 8px; border: 1px solid #ccc;
  width: 220px; font-size: 15px; }
button { background: #27ae60; color: #fff; border: none; padding: 10px 20px;
  border-radius: 8px; font-size: 15px; cursor: pointer; margin-left: 10px; }
button:hover { background: #219150; }
.table { width: 100%; border-collapse: collapse; }
.table th { background: #2980b9; color: #fff; padding: 12px; }
.table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
.return { display: block; width: 220px; margin: 20px auto; padding: 10px; text-align: center;
  background: #3498db; color: #fff; border-radius: 8px; text-decoration: none; }
.return:hover { background: #2980b9; }
body.dark { background:#1e1e2f; color:#f0f0f0; }
body.dark .container { background:#2c2c2c; color:#f0f0f0; }
body.dark .table th { background:#145173; }
body.dark .return { background:#145173; }
</style>
</head>
<body>
<div class="container">
    <h2>⚙️ Mise à jour automatique du personnel</h2>

    <p style="text-align:center;margin-bottom:20px;">
        <strong>Règles automatiques :</strong><br>
        - +3 % de salaire chaque année 💰<br>
        - +2,5 jours de congés chaque mois 🏖
    </p>

    <form method="post">
        <input type="password" name="admin_code" placeholder="Entrez le code admin" required>
        <button type="submit">🔄 Mettre à jour automatiquement</button>
    </form>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table class="table">
        <tr>
            <th>Nom & Prénom</th>
            <th>Fonction</th>
            <th>Section</th>
            <th>Salaire actuel</th>
            <th>Solde Congés actuel</th>
        </tr>

        <?php foreach ($employees as $emp): ?>
        <tr>
            <td><?= htmlspecialchars($emp['nom'].' '.$emp['prenom']) ?></td>
            <td><?= htmlspecialchars($emp['nom_fonction']) ?></td>
            <td><?= htmlspecialchars($emp['Section']) ?></td>
            <td><?= htmlspecialchars($emp['salaire']) ?> DA</td>
            <td><?= htmlspecialchars($emp['solde_conge']) ?> jours</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a class="return" href="index.php?page=dashboard">⬅ Retour au Dashboard</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const theme = localStorage.getItem('theme') || 'light';
  if (theme === 'dark') document.body.classList.add('dark');
});
</script>
</body>
</html>
</div>







