<?php include 'views/sidebar.php'; ?>
<div class="main" id="mainContent">
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Gestion des Absences</title>
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f4f6f9;
  margin: 0;
  padding: 20px;
  display: flex;
  justify-content: center;
  color: #333;
  transition: 0.3s;
}

/* 🌙 Mode nuit */
body.dark {
  background: #1e1e2f;
  color: #eee;
}
body.dark .card {
  background: #2b2b3a;
  color: #f5f5f5;
  box-shadow: 0 6px 15px rgba(255,255,255,0.05);
}
body.dark th { background: #34495e; }
body.dark tr:nth-child(even) { background: #2f2f3d; }
body.dark input, body.dark select, body.dark textarea {
  background: #3a3a4a;
  color: #fff;
  border: 1px solid #555;
}
body.dark .btn-retour { background: #555; }
body.dark .btn-retour:hover { background: #777; }

.card {
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  width: 95%;
  max-width: 1100px;
}

h2 {
  text-align: center;
  color: #d35400;
  margin-bottom: 25px;
}

.btn {
  border: none;
  border-radius: 6px;
  padding: 8px 12px;
  color: #fff;
  cursor: pointer;
  font-size: 14px;
  text-decoration: none;
  display: inline-block;
  margin: 2px;
  transition: 0.2s;
}
.btn-add { background: #27ae60; }
.btn-add:hover { background: #1e8449; }
.btn-edit { background: #3498db; }
.btn-edit:hover { background: #2e86c1; }
.btn-delete { background: #e74c3c; }
.btn-delete:hover { background: #c0392b; }
.btn-retour { background: #7f8c8d; }
.btn-retour:hover { background: #636e72; }
.btn-accept { background: #2ecc71; }
.btn-accept:hover { background: #27ae60; }
.btn-refuse { background: #e74c3c; }
.btn-refuse:hover { background: #c0392b; }

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
  font-size: 14px;
}
th {
  background: #e67e22;
  color: white;
  cursor: pointer;
}
tr:nth-child(even) { background: #f9f9f9; }

.badge {
  padding: 5px 10px;
  border-radius: 8px;
  color: white;
  font-weight: 500;
  font-size: 13px;
}
.badge-success { background: #27ae60; }
.badge-danger { background: #e74c3c; }
.badge-warning { background: #f39c12; }

form {
  background: #fafafa;
  padding: 20px;
  border-radius: 10px;
  margin-top: 25px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
body.dark form {
  background: #2b2b3a;
  box-shadow: 0 4px 12px rgba(255,255,255,0.05);
}

label {
  font-weight: bold;
  display: block;
  margin-top: 10px;
}
input, select, textarea {
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  margin-top: 6px;
  font-size: 14px;
  transition: 0.2s;
}
textarea { resize: vertical; }

.form-actions {
  margin-top: 20px;
  text-align: center;
}
.filters {
  display: flex;
  gap: 12px;
  justify-content: center;
  margin-bottom: 15px;
  flex-wrap: wrap;
}
input[type="text"].search { width: 220px; }

/* ✅ Dropdown Actions */
.dropdown {
  position: relative;
  display: inline-block;
}
.dropdown-btn {
  background: #e67e22;
  border: none;
  border-radius: 6px;
  color: #fff;
  padding: 6px 12px;
  cursor: pointer;
  font-size: 14px;
  transition: 0.2s;
}
.dropdown-btn:hover { background: #e67e22; }
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #fff;
  min-width: 160px;
  box-shadow: 0 6px 12px rgba(0,0,0,0.15);
  z-index: 1000;
  right: 0;
  border-radius: 8px;
  overflow: hidden;
}
body.dark .dropdown-content { background-color: #2f2f3d; }
.dropdown-content a {
  color: #333;
  padding: 10px 14px;
  text-decoration: none;
  display: block;
  font-size: 14px;
  transition: 0.2s;
}
body.dark .dropdown-content a { color: #eee; }
.dropdown-content a:hover { background-color: #f1f1f1; }
body.dark .dropdown-content a:hover { background-color: #3a3a4a; }
.dropdown:hover .dropdown-content { display: block; }
</style>
</head>
<body>
<div class="card">
<h2>📅 Gestion des Absences</h2>

<div style="text-align:center;">
  <button onclick="toggleForm()" class="btn btn-add" id="btnToggle">
    <?= isset($absence) ? '⬅ Retour à la liste' : '➕ Nouvelle absence' ?>
  </button>
  <a href="index.php?page=dashboard" class="btn btn-retour">⬅ Retour</a>
</div>

<form method="GET" action="index.php" class="filters">
  <input type="hidden" name="page" value="absences">
  <input type="text" name="search" class="search" placeholder="🔍 Rechercher employé..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
  <select name="statut">
    <option value="">-- Tous les statuts --</option>
    <option value="EN_ATTENTE" <?= ($_GET['statut'] ?? '') === 'EN_ATTENTE' ? 'selected' : '' ?>>En attente</option>
    <option value="ACCEPTEE" <?= ($_GET['statut'] ?? '') === 'ACCEPTEE' ? 'selected' : '' ?>>Acceptée</option>
    <option value="REFUSEE" <?= ($_GET['statut'] ?? '') === 'REFUSEE' ? 'selected' : '' ?>>Refusée</option>
  </select>
  <button class="btn btn-add" type="submit">Filtrer</button>
</form>

<table id="absenceTable" style="<?= isset($absence) ? 'display:none;' : '' ?>">
  <thead>
    <tr>
      <th>#</th>
      <th>Employé</th>
      <th>Date</th>
      <th>Durée</th>
      <th>Motif</th>
      <th>Justifié</th>
      <th>Justificatif</th>
      <th>Statut</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php if (!empty($absences)): ?>
    <?php foreach ($absences as $a): ?>
    <tr>
      <td><?= htmlspecialchars($a['id']) ?></td>
      <td><?= htmlspecialchars($a['prenom'].' '.$a['nom']) ?></td>
      <td><?= htmlspecialchars($a['date_absence']) ?></td>
      <td><?= htmlspecialchars($a['duree']) ?> j</td>
      <td><?= htmlspecialchars($a['motif']) ?></td>
      <td><?= $a['justifie']==='OUI' ? '<span class="badge badge-success">Oui</span>' : '<span class="badge badge-danger">Non</span>' ?></td>
      <td>
        <?php if (!empty($a['justificatif'])): ?>
          <a href="uploads/absences/<?= htmlspecialchars($a['justificatif']) ?>" target="_blank">📎 Voir</a>
        <?php else: ?> - <?php endif; ?>
      </td>
      <td>
        <?php $color = $a['statut']==='ACCEPTEE' ? 'badge-success' : ($a['statut']==='REFUSEE' ? 'badge-danger' : 'badge-warning'); ?>
        <span class="badge <?= $color ?>"><?= htmlspecialchars($a['statut']) ?></span>
      </td>

      <!-- ✅ Nouveau bouton d'actions -->
      <td>
        <div class="dropdown">
          <button class="btn btn-edit dropdown-btn">⚙ Actions ▾</button>
          <div class="dropdown-content">
            <a href="index.php?page=absences&action=edit&id=<?= $a['id'] ?>">✏️ Modifier</a>
            <a href="index.php?page=absences&action=delete&id=<?= $a['id'] ?>" onclick="return confirm('Supprimer cette absence ?')">🗑 Supprimer</a>
            <?php if ($_SESSION['user']['role'] === 'admin' && $a['statut'] === 'EN_ATTENTE'): ?>
              <a href="index.php?page=absences&action=updateStatus&id=<?= $a['id'] ?>&statut=ACCEPTEE">✅ Accepter</a>
              <a href="index.php?page=absences&action=updateStatus&id=<?= $a['id'] ?>&statut=REFUSEE">❌ Refuser</a>
            <?php endif; ?>
          </div>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="9">Aucune absence trouvée.</td></tr>
  <?php endif; ?>
  </tbody>
</table>

<!-- Formulaire d'ajout / modification -->
<form method="POST" enctype="multipart/form-data" action="index.php?page=absences&action=<?= isset($absence) ? 'update' : 'store' ?>" id="formAbsence" style="<?= isset($absence) ? 'display:block;' : 'display:none;' ?>">
  <h3 style="text-align:center;color:#e67e22;"> <?= isset($absence) ? "✏️ Modifier l'absence" : "➕ Ajouter une absence" ?> </h3>
  <?php if (isset($absence)): ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars($absence['id']) ?>">
  <?php endif; ?>

  <label>Employé</label>
  <select name="employee_id" required>
    <option value="">-- Sélectionner un employé --</option>
    <?php foreach($employees as $emp): ?>
      <option value="<?= $emp['id'] ?>" <?= isset($absence) && $absence['employee_id']==$emp['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($emp['prenom'].' '.$emp['nom']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>Date d’absence</label>
  <input type="date" name="date_absence" value="<?= htmlspecialchars($absence['date_absence'] ?? '') ?>" required>

  <label>Durée (en jours)</label>
  <input type="number" name="duree" min="1" value="<?= htmlspecialchars($absence['duree'] ?? 1) ?>" required>

  <label>Motif</label>
  <textarea name="motif" rows="3"><?= htmlspecialchars($absence['motif'] ?? '') ?></textarea>

  <label>Justifié</label>
  <select name="justifie" required>
    <option value="OUI" <?= isset($absence) && $absence['justifie']==='OUI' ? 'selected' : '' ?>>Oui</option>
    <option value="NON" <?= isset($absence) && $absence['justifie']==='NON' ? 'selected' : '' ?>>Non</option>
  </select>

  <label>Justificatif (PDF/Image)</label>
  <input type="file" name="justificatif" accept=".pdf,.jpg,.jpeg,.png">

  <?php if ($_SESSION['user']['role'] === 'admin'): ?>
  <label>Statut</label>
  <select name="statut" required>
    <option value="EN_ATTENTE" <?= isset($absence) && $absence['statut']==='EN_ATTENTE' ? 'selected' : '' ?>>En attente</option>
    <option value="ACCEPTEE" <?= isset($absence) && $absence['statut']==='ACCEPTEE' ? 'selected' : '' ?>>Acceptée</option>
    <option value="REFUSEE" <?= isset($absence) && $absence['statut']==='REFUSEE' ? 'selected' : '' ?>>Refusée</option>
  </select>
  <?php else: ?>
  <input type="hidden" name="statut" value="EN_ATTENTE">
  <?php endif; ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-add">
      <?= isset($absence) ? "💾 Mettre à jour" : "✅ Enregistrer" ?>
    </button>
    <button type="button" onclick="toggleForm()" class="btn btn-retour">❌ Annuler</button>
  </div>
</form>
</div>

<script>
function toggleForm() {
  const form = document.getElementById('formAbsence');
  const table = document.getElementById('absenceTable');
  const btn = document.getElementById('btnToggle');
  const isHidden = form.style.display === 'none';
  form.style.display = isHidden ? 'block' : 'none';
  table.style.display = isHidden ? 'none' : 'table';
  btn.textContent = isHidden ? '⬅ Retour à la liste' : '➕ Nouvelle absence';
}

document.querySelectorAll("th").forEach((th, index) => {
  th.addEventListener("click", () => {
    const tbody = document.querySelector("#absenceTable tbody");
    const rows = Array.from(tbody.rows);
    const sorted = rows.sort((a, b) => a.cells[index].innerText.localeCompare(b.cells[index].innerText, 'fr', {numeric: true}) );
    tbody.innerHTML = "";
    sorted.forEach(r => tbody.appendChild(r));
  });
});

// 🌙 Mode nuit
if (localStorage.getItem('theme') === 'dark') {
  document.body.classList.add('dark');
}
</script>
</body>
</html>
</div>








