<?php include 'views/sidebar.php'; ?>
<div class="main" id="mainContent">

<?php
$done   = $_GET['done'] ?? null;
$none   = $_GET['none'] ?? null;
$search = htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>📈 Historique des Promotions</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f4f6f9; padding:20px; color:#333; }
.card { background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); max-width:1000px; margin:auto; }
h1 { color:#2c3e50; text-align:center; }
form { margin-bottom:25px; display:flex; flex-wrap:wrap; gap:10px; justify-content:center; }
input, select, button { padding:10px; border:1px solid #ccc; border-radius:6px; font-size:14px; }
button { background:#3498db; color:#fff; border:none; cursor:pointer; transition:0.3s; }
button:hover { background:#2980b9; }
.alert { padding:10px; border-radius:8px; margin-bottom:20px; text-align:center; }
.alert-success { background:#d4edda; color:#155724; }
.alert-danger { background:#f8d7da; color:#721c24; }
table { width:100%; border-collapse:collapse; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
th { background:#3498db; color:#fff; }
tr:hover { background:#f1f1f1; }
.add-form { background:#ecf0f1; padding:20px; border-radius:10px; margin-top:30px; }
.auto-info { background:#e8f5e9; color:#2e7d32; padding:10px; border-radius:8px; margin-bottom:20px; text-align:center; font-weight:500; }
</style>
</head>
<body>

<div class="card">
    <h1>📈 Gestion et Historique des Promotions</h1>

    <!-- ✅ Messages -->
    <?php if ($done): ?>
        <div class="alert alert-success">✅ Promotion enregistrée avec succès.</div>
    <?php elseif ($none): ?>
        <div class="alert alert-danger">❌ Échec de l’ajout de la promotion.</div>
    <?php endif; ?>

    <!-- 🔄 Info automatique -->
    <div class="auto-info">
        ⚙️ Les promotions automatiques sont effectuées pour les employés ayant atteint 3 ans sans promotion.
    </div>

    <!-- 🔍 Recherche -->
    <form method="GET" action="">
        <input type="hidden" name="page" value="promotions">
        <input type="text" name="search" placeholder="🔍 Rechercher un employé..." value="<?= $search ?>">
        <button type="submit">Rechercher</button>
        <button type="button" onclick="window.location='index.php?page=promotions'">Réinitialiser</button>
        <button onclick="window.print()" class="print-btn">🖨️ Imprimer</button>

    </form>

    <!-- 📊 Tableau -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employé</th>
                <th>Ancienne fonction</th>
                <th>Nouvelle fonction</th>
                <th>Date</th>
                <th>Motif</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($promotions)): ?>
                <?php foreach ($promotions as $i => $p): ?>
                    <?php
                        $ancien = $p['ancien_fonction'] ? 
                            $p['ancien_fonction']['nom_fonction'] . ' - ' . $p['ancien_fonction']['Catégorie'] . '_' . $p['ancien_fonction']['Section'] 
                            : '-';
                        $nouveau = $p['nouvelle_fonction'] ? 
                            $p['nouvelle_fonction']['nom_fonction'] . ' - ' . $p['nouvelle_fonction']['Catégorie'] . '_' . $p['nouvelle_fonction']['Section'] 
                            : '-';
                    ?>
                    <tr style="<?= strpos($p['motif'], 'automatique') !== false ? 'background:#e8f5e9;' : '' ?>">
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($p['nom'] . ' ' . $p['prenom']) ?></td>
                        <td><?= htmlspecialchars($ancien) ?></td>
                        <td><?= htmlspecialchars($nouveau) ?></td>
                        <td><?= htmlspecialchars($p['date_promotion']) ?></td>
                        <td>
                            <?= htmlspecialchars($p['motif']) ?>
                            <?php if (strpos($p['motif'], 'automatique') !== false): ?> 🕒 <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">Aucune promotion trouvée</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

   <!-- ➕ Formulaire d’ajout -->
<div class="add-form">
    <h3>➕ Ajouter une promotion manuelle</h3>
    <form method="POST" action="index.php?page=promotions&action=add">
        <!-- Sélection de l’employé -->
        <select name="employee_id" id="employeeSelect" required>
            <option value="">-- Sélectionner un employé --</option>
            <?php foreach ($employees as $e): ?>
                <option 
                    value="<?= $e['id'] ?>" 
                    data-fonction="<?= $e['fonction_id'] ?>"
                >
                    <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Ancienne fonction (pré-remplie automatiquement) -->
        <select name="ancien_fonction_id" id="ancienFonctionSelect" required>
            <option value="">-- Ancienne fonction --</option>
            <?php foreach ($fonctions as $f): ?>
                <option value="<?= $f['id'] ?>">
                    <?= htmlspecialchars($f['nom_fonction'] . ' - ' . $f['Catégorie'] . '_' . $f['Section']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Nouvelle fonction -->
        <select name="nouvelle_fonction_id" required>
            <option value="">-- Nouvelle fonction --</option>
            <?php foreach ($fonctions as $f): ?>
                <option value="<?= $f['id'] ?>">
                    <?= htmlspecialchars($f['nom_fonction'] . ' - ' . $f['Catégorie'] . '_' . $f['Section']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Date et motif -->
        <input type="date" name="date_promotion" value="<?= date('Y-m-d') ?>" required>
        <input type="text" name="motif" placeholder="Motif de la promotion" required>
        <button type="submit">💾 Enregistrer</button>
    </form>
</div>

<!-- Script JS pour pré-remplir l’ancienne fonction -->
<script>
const employeeSelect = document.getElementById('employeeSelect');
const ancienFonctionSelect = document.getElementById('ancienFonctionSelect');

employeeSelect.addEventListener('change', function() {
    const fonctionId = this.selectedOptions[0].dataset.fonction || '';
    if(fonctionId) {
        ancienFonctionSelect.value = fonctionId;
    } else {
        ancienFonctionSelect.value = '';
    }
});
</script>


</body>
</html>












