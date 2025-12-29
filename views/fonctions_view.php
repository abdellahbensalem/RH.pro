<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
<?php
// Ces variables sont supposées être passées depuis le contrôleur :
// $fonctions, $totalPages, $page, $search, $done

// Sécurité : si elles n’existent pas, on les initialise pour éviter les erreurs
$search = $search ?? '';

$fonctions = $fonctions ?? [];
$done = $done ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des Fonctions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>

body {
  font-family: 'Segoe UI', sans-serif;
  background: var(--bg);
  color: var(--text);
  margin: 0;
  padding: 20px;
  display: flex;
  justify-content: center;
  transition: background 0.3s, color 0.3s;
}
.card {
  background: var(--card);
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  width: 95%;
  max-width: 1100px;
  transition: background 0.3s;
}
h2 { text-align: center; color: #9b59b6; margin-bottom: 20px; }
.btn-auto {
  background: #8e44ad;
  color: white;
  border-radius: 8px;
  padding: 8px 14px;
  text-decoration: none;
  border: none;
}
.btn-auto:hover { background: #732d91; }
.btn-retour {
  display: inline-block;
  padding: 8px 15px;
  margin-top: 15px;
  background: #3498db;
  color: white;
  text-decoration: none;
  border-radius: 6px;
}
.filters {
  display: flex;
  gap: 12px;
  justify-content: center;
  margin-bottom: 15px;
  flex-wrap: wrap;
}
input, select, textarea {
  padding: 8px 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  background: var(--input-bg);
  color: var(--text);
}
table {
  width: 100%;
  border-collapse: collapse;
}
.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  white-space: nowrap;
}

thead th {
  position: sticky;
  top: 0;
  z-index: 5;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
  font-size: 14px;
}
th {
  background: #9b59b6;
  color: white;
}
tr:nth-child(even) { background: var(--row-alt); }
.alert-success {
  background: #27ae60;
  color: white;
  padding: 10px;
  border-radius: 8px;
  text-align: center;
  margin-bottom: 15px;
}
form.add-form {
  margin-top: 25px;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}
form.add-form input, form.add-form select, form.add-form textarea {
  width: 100%;
}
button {
  background: #9b59b6;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 8px;
  cursor: pointer;
}
button:hover { background: #8e44ad; }
.pagination {
  margin-top: 15px;
  display: flex;
  justify-content: center;
  gap: 5px;
}
.pagination a {
  padding: 6px 12px;
  background: #eee;
  border-radius: 6px;
  text-decoration: none;
  color: #333;
}
.pagination a.active {
  background: #9b59b6;
  color: white;
}
.pagination a:hover {
  background: #732d91;
  color: white;
}
/* 🌞 Thème clair */
:root {
  --bg: #f4f6f9;
  --card: #fff;
  --text: #333;
  --row-alt: #f9f9f9;
  --input-bg: #fff;
}
/* 🌙 Thème sombre */
body.dark {
  --bg: #1e1e2f;
  --card: #2c2c3e;
  --text: #eee;
  --row-alt: #252538;
  --input-bg: #333;
}
/* 🌙 Texte du tableau en mode sombre */
body.dark table,
body.dark th,
body.dark td {
    color: #eee !important;
}

/* Bordures en mode sombre */
body.dark th,
body.dark td {
    border-color: #444;
}

/* Fond du header tableau en mode sombre */
body.dark th {
    background: #7d3c98;
}

</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

</head>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>



<body>
<div class="card">
  <h2>💼 Gestion des Fonctions</h2>

  <?php if ($done): ?>
    <div class="alert-success">
      🔗 <?= htmlspecialchars($done) ?> employé(s) associés à une fonction.
    </div>
  <?php endif; ?>

  <div style="text-align:center; margin-bottom:15px;">
    <a href="index.php?page=fonctions&action=autoAssign" class="btn-auto">🔗 Associer automatiquement</a>
    <a href="index.php?page=dashboard" class="btn-retour">⬅ Retour</a>
  </div>

  <!-- 🔍 Barre de recherche -->
  <form method="GET" action="index.php" class="filters">
    <input type="hidden" name="page" value="fonctions">
    <input type="text" name="search" placeholder="🔍 Rechercher fonction..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn-auto" type="submit">Filtrer</button>
  </form>

  <!-- 📋 Tableau -->
  <div class="table-wrapper">
  <table id="fonctionsTable">

    <thead>
      <tr>
        <th>#</th>
        <th>Nom Fonction</th>
        <th>Salaire Base</th>
        <th>Catégorie</th>
        <th>Section</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($fonctions)): ?>
        <?php foreach ($fonctions as $f): ?>
        <tr>
          <td><?= htmlspecialchars($f['id']) ?></td>
          <td><?= htmlspecialchars($f['nom_fonction']) ?></td>
          <td><?= number_format($f['salaire_base'], 2, ',', ' ') ?> DA</td>
          <td><?= htmlspecialchars($f['Catégorie']) ?></td>
          <td><?= htmlspecialchars($f['Section']) ?></td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                ⚙️ Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="index.php?page=fonctions&edit=<?= $f['id'] ?>">✏️ Modifier</a></li>
                <li><a class="dropdown-item text-danger" href="index.php?page=fonctions&delete=<?= $f['id'] ?>"
                  onclick="return confirm('Supprimer cette fonction ?')">🗑 Supprimer</a></li>
              </ul>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6">Aucune fonction trouvée.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>


  <!-- ➕ Formulaire d'ajout / modification -->
  <form method="POST" action="index.php?page=fonctions&action=add" class="add-form mt-4">
    <input type="hidden" name="id" value="<?= $edit_fonction['id'] ?? '' ?>">

    <input type="text" name="nom_fonction" placeholder="Nom de la fonction" required
           value="<?= isset($edit_fonction) ? htmlspecialchars($edit_fonction['nom_fonction']) : '' ?>">

    <input type="number" step="0.01" name="salaire_base" placeholder="Salaire de base (DA)" required
           value="<?= isset($edit_fonction) ? htmlspecialchars($edit_fonction['salaire_base']) : '' ?>">

    <input type="number" name="Catégorie" placeholder="Catégorie hiérarchique" required
           value="<?= isset($edit_fonction) ? htmlspecialchars($edit_fonction['Catégorie']) : '' ?>">

    <input type="text" name="Section" placeholder="Section (ex: Niveau 1, Niveau 2...)" required
           value="<?= isset($edit_fonction) ? htmlspecialchars($edit_fonction['Section']) : '' ?>">

    <button type="submit"><?= isset($edit_fonction) ? '💾 Enregistrer' : '➕ Ajouter' ?></button>
  </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// 🌙 Synchronisation avec le thème du dashboard
document.addEventListener('DOMContentLoaded', () => {
  const theme = localStorage.getItem('theme') || 'light';
  if (theme === 'dark') document.body.classList.add('dark');
});
</script>
<script>
$('#fonctionsTable').DataTable({
  pageLength: 10,
  lengthMenu: [5, 10, 25, 50],
  ordering: true,
  searching: true,
  dom: '<"d-flex justify-content-between mb-2"B>rtip',
  buttons: [
    {
      extend: 'colvis',
      text: '👁️ Colonnes',
      className: 'btn btn-sm btn-outline-secondary'
    }
  ],
  language: {
    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
  }
});

</script>
</body>
</html>







