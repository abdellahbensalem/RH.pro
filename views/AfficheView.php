<?php include 'views/sidebar.php'; ?>
<div class="main" id="mainContent">

<?php
$user = $_SESSION['user'] ?? null;
$userRole = $user['role'] ?? 'employe';
$userId = $user['id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des congés</title>
<style>
body { font-family:'Segoe UI',sans-serif;background:#f4f6f9;padding:20px; }
.card { background:#fff;padding:25px;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.1);margin-bottom:20px; }
.btn { padding:8px 12px;border:none;border-radius:6px;cursor:pointer;text-decoration:none;display:inline-block; }
.btn-add { background:#2ecc71;color:#fff;margin-bottom:10px; }
.btn-search { background:#3498db;color:#fff; }
.btn-retour { background:#7f8c8d;color:#fff;margin-left:10px; }
.btn-approve { background:#27ae60;color:#fff; }
.btn-reject { background:#e74c3c;color:#fff; }
.btn-delete { background:#f39c12;color:#fff; }
.btn-pdf { background:#2980b9;color:#fff; }
table { width:100%;border-collapse:collapse;margin-top:10px; }
th, td { padding:10px;border-bottom:1px solid #ddd;text-align:center; }
th { background:#3498db;color:#fff; }
.message { background:#f9f9f9;border-left:4px solid #3498db;padding:8px;margin:10px 0;border-radius:5px; }
#formAdd { display:none;margin-top:15px; }
input, select, textarea { padding:6px;width:100%;border:1px solid #ccc;border-radius:4px; }
</style>

<script>
function toggleForm() {
  const form = document.getElementById("formAdd");
  form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
}
function goBack() { window.location.href = "index.php?page=dashboard"; }
</script>
</head>

<body>
<div class="card">
  <h2>📋 Gestion des congés</h2>

  <?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <!-- Recherche -->
  <form method="get" style="margin-bottom:10px;display:flex;gap:10px;align-items:center;">
    <input type="hidden" name="page" value="affiche">
    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Rechercher par nom ou statut..." style="width:300px;">
    <button type="submit" class="btn btn-search">🔍 Rechercher</button>
    <button type="button" class="btn btn-retour" onclick="goBack()">⬅ Retour</button>
  </form>

  <!-- Formulaire ajout congé (admin/directeur uniquement) -->
  <?php if ($userRole === 'admin' || $userRole === 'directeur'): ?>
    <button class="btn btn-add" onclick="toggleForm()">➕ Ajouter un congé</button>
    <form id="formAdd" method="post">
      <input type="hidden" name="ajouter_conge" value="1">

      <label>Employé :</label>
      <select name="employee_id" required>
        <option value="">-- Sélectionner --</option>
        <?php foreach ($employees as $e): ?>
          <option value="<?= htmlspecialchars($e['id']) ?>">
            <?= htmlspecialchars($e['prenom'].' '.$e['nom'].' (Solde: '.$e['solde_conge'].'j)') ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Type de congé :</label>
      <select name="type_conge_id" required>
        <option value="">-- Choisir un type --</option>
        <?php foreach ($typesConge as $t): ?>
          <option value="<?= htmlspecialchars($t['id']) ?>">
            <?= htmlspecialchars($t['nom_type']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Date début :</label>
      <input type="date" name="date_debut" required>
      <label>Date fin :</label>
      <input type="date" name="date_fin" required>
      <label>Raison :</label>
      <textarea name="raison" rows="2" required></textarea>

      <button type="submit" class="btn btn-add">✅ Enregistrer</button>
    </form>
  <?php endif; ?>

  <!-- Tableau des congés -->
  <table>
    <tr>
      <th>ID</th>
      <th>Employé</th>
      <th>Type</th>
      <th>Date début</th>
      <th>Date fin</th>
      <th>Raison</th>
      <th>Statut</th>
      <th>Solde</th>
      <?php if ($userRole === 'admin' || $userRole === 'directeur'): ?><th>Action</th><?php endif; ?>
    </tr>

    <?php if (!empty($conges)): ?>
      <?php foreach ($conges as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['id']) ?></td>
            <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
            <td><?= htmlspecialchars($c['type_conge'] ?? '-') ?></td>
            <td><?= htmlspecialchars($c['date_debut']) ?></td>
            <td><?= htmlspecialchars($c['date_fin']) ?></td>
            <td><?= htmlspecialchars($c['raison']) ?></td>
            <td><?= htmlspecialchars($c['statut']) ?></td>
            <td><?= htmlspecialchars($c['solde_conge']) ?> j</td>

            <!-- Actions uniquement pour admin/directeur -->
            <?php if ($userRole === 'admin' || $userRole === 'directeur'): ?>
                <td>
                    <?php if ($c['statut'] === 'EN_ATTENTE'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="conge_id" value="<?= htmlspecialchars($c['id']) ?>">
                            <button type="submit" name="action" value="APPROUVE" class="btn btn-approve">✔</button>
                            <button type="submit" name="action" value="REFUSE" class="btn btn-reject">✖</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($c['statut'] === 'APPROUVE'): ?>
                        <a href="generate_conge.php?id=<?= urlencode($c['id']) ?>" target="_blank" class="btn btn-pdf">🖨️ PDF</a>
                    <?php endif; ?>

                    <a href="?page=affiche&delete=<?= urlencode($c['id']) ?>" onclick="return confirm('Supprimer ce congé ?')" class="btn btn-delete">🗑</a>
                </td>
            <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="<?= ($userRole === 'admin' || $userRole === 'directeur') ? 9 : 8 ?>">Aucun congé trouvé</td>
      </tr>
    <?php endif; ?>
  </table>
</div>
</body>
</html>











