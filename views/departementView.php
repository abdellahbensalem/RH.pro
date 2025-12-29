<?php /** @var array $departements, $employees, $empsByDept, $edit_dept, $editing, $message */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des départements</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background:#f4f6f9; transition:background 0.3s,color 0.3s; }
    .card, .modal-content { border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); }
    h2 { color:#2980b9; }
    .table th { background:#2980b9; color:white; }
    .btn-retour { background:#3498db; color:white; border-radius:6px; padding:8px 15px; }
    .btn-retour:hover { background:#2980b9; }
    body.dark { background:#1f1f1f; color:#f0f0f0; }
    body.dark .card, body.dark .modal-content { background:#2c2c2c; color:#f0f0f0; }
    body.dark .table th { background:#145173; }
    body.dark .btn-retour { background:#145173; }
    .dark-toggle { cursor:pointer; font-size:20px; position:absolute; top:15px; right:20px; }
    .main { margin-left:250px; padding:20px; transition:margin-left .3s; }
    @media (max-width:768px) { .main { margin-left:0; } }
    .btn-group .dropdown-toggle { background-color:#3498db; border:none; }
    .btn-group .dropdown-toggle:hover { background-color:#2980b9; }
    .dropdown-menu { border-radius:8px; box-shadow:0 6px 12px rgba(0,0,0,0.1); }
    .table-wrapper { max-width: 100%; overflow-x: auto; }
    .table-sticky thead th { position: sticky; top: 0;  ; z-index: 10; }
    table { white-space: nowrap; }
    
  </style>
</head>
<body>

<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
  <i class="fa-solid fa-moon dark-toggle" id="darkToggle"></i>

  <div class="container mt-4">
    <?php if(!empty($message)): ?>
      <div class="alert <?= strpos($message,'⚠️')!==false ? 'alert-danger':'alert-success' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Gestion des départements</h2>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#deptModal">➕ Ajouter un département</button>
    </div>

    <div class="card table-wrapper">
      <div class="card-body table-responsive">
        <table class="table table-bordered table-hover text-center table-sticky">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Responsable</th>
              <th>Employés rattachés</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($departements)): ?>
              <?php foreach($departements as $d): ?>
                <tr>
                  <td><?= $d['id'] ?></td>
                  <td><?= htmlspecialchars($d['nom']) ?></td>
                  <td>
                    <?= isset($d['responsable_prenom']) ? htmlspecialchars($d['responsable_prenom'].' '.$d['responsable_nom']) : '<span class="text-muted">Aucun</span>' ?>
                  </td>
                  <td>
                    <?php
                      $emps = $empsByDept[$d['id']] ?? [];
                      if(!empty($emps)){
                          echo implode(', ', array_map(fn($e)=>htmlspecialchars($e['prenom'].' '.$e['nom']), $emps));
                      } else {
                          echo '<span class="text-muted">Aucun</span>';
                      }
                    ?>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        ⚙️ Actions
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-warning" href="index.php?page=departements&edit=<?= $d['id'] ?>">✏️ Modifier</a></li>
                        <li><a class="dropdown-item text-danger" href="index.php?page=departements&delete=<?= $d['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce département ?')">🗑️ Supprimer</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-muted">Aucun département trouvé.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <a href="index.php?page=dashboard" class="btn-retour mt-3">⬅ Retour</a>
  </div>

  <!-- Modal Département -->
  <div class="modal fade" id="deptModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="index.php?page=departements">
          <div class="modal-header">
            <h5 class="modal-title"><?= $editing ? "✏️ Modifier" : "➕ Ajouter" ?> un département</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <?php if($editing && $edit_dept): ?>
              <input type="hidden" name="id" value="<?= $edit_dept['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
              <label class="form-label">Nom du département</label>
              <input type="text" name="nom" class="form-control" required value="<?= $editing ? htmlspecialchars($edit_dept['nom']) : '' ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Responsable</label>
              <select name="responsable_id" class="form-select">
                <option value="">-- Aucun --</option>
                <?php foreach($employees as $e): ?>
                  <option value="<?= $e['id'] ?>" <?= ($editing && $edit_dept['responsable_id']==$e['id'])?'selected':'' ?>>
                    <?= htmlspecialchars($e['prenom'].' '.$e['nom']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?= $editing ? "Modifier" : "Ajouter" ?></button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // 🌙 Mode sombre
  const toggle = document.getElementById('darkToggle');
  toggle.addEventListener('click',()=>{document.body.classList.toggle('dark');});

  // Auto-show modal if editing
  <?php if($editing): ?>
    const modal = new bootstrap.Modal(document.getElementById('deptModal'));
    modal.show();
  <?php endif; ?>
</script>



