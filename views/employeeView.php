<?php /** @var array $result, $departements, $fonctions, $edit_employee, $editing, $message, $search */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des employés</title>
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
  </style>
  <style>
.table-wrapper {
    max-width: 100%;
    overflow-x: auto;  /* Scroll horizontal propre */
}

.table-sticky thead th {
    position: sticky;
    top: 0;
    background: #2980b9;
    z-index: 10;
}

/* Pour éviter que ton tableau se pète en largeur */
table {
    white-space: nowrap;
}
</style>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

</head>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<body>

<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
  <i class="fa-solid fa-moon dark-toggle" id="darkToggle"></i>

  <div class="container mt-4">
    <?php if (!empty($message)): ?>
      <div class="alert <?= strpos($message,'⚠️')!==false ? 'alert-danger':'alert-success' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Gestion des employés</h2>
      <form class="d-flex" method="get">
        <input type="hidden" name="page" value="employees">
        <input type="text" name="search" class="form-control me-2" placeholder="🔎 Rechercher..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-primary">Rechercher</button>
      </form>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#employeeModal">➕ Ajouter un employé</button>
      <a href="excel/export_employees_excel.php" class="btn btn-outline-success">
        <i class="fa-solid fa-file-excel"></i> Exporter en Excel
      </a>
    </div>

    <div class="card">
      <div class="card-body table-responsive">
        <table id="employeesTable" class="table table-bordered table-hover text-center table-sticky">

          <thead>
            <tr>
              <th>ID</th><th>Matricule</th><th>Nom</th><th>Prénom</th><th>الاسم (AR)</th><th>اللقب (AR)</th><th>CNI</th>
              <th>NIF</th><th>Assurance</th><th>RIB / CCP</th><th>Date Naissance</th><th>Lieu Naissance</th>
              <th>Sexe</th><th>Situation familiale</th><th>Enfants</th><th>Adresse</th>
              <th>Email</th><th>Téléphone</th><th>Poste</th><th>Département</th>
              <th>Salaire</th><th>Solde congé</th><th>Statut</th><th>Type contrat</th>
              <th>Type statut contrat</th><th>Diplôme</th><th>Spécialité</th><th>Niveau études</th>
              <th>Structure</th><th>Catégorie</th><th>Section</th><th>Échelon</th><th>Supérieur</th>
              <th>Date embauche</th><th>Date sortie</th><th>Date promotion</th><th>Dernière promotion</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($result)): ?>
              <?php foreach ($result as $emp): ?>
                <tr>
                  <td><?= $emp['id'] ?></td>
                  <td><?= htmlspecialchars($emp['matricule']) ?></td>
                  <td><?= htmlspecialchars($emp['nom']) ?></td>
                  <td><?= htmlspecialchars($emp['prenom']) ?></td>
                  <td><?= htmlspecialchars($emp['الاسم'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['اللقب'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['cni_numero'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['nif'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['numero_assurance'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['rib_ccp'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['date_naissance'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['lieu_naissance'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['sexe'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['situation_familiale'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['nombre_enfants']) ?></td>
                  <td><?= htmlspecialchars($emp['adresse'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['email']) ?></td>
                  <td><?= htmlspecialchars($emp['telephone'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['poste'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['departement_nom'] ?? '-') ?></td>
                  <td><?= number_format($emp['salaire'], 2, ',', ' ') ?> DA</td>
                  <td><?= htmlspecialchars($emp['solde_conge']) ?> jours</td>
                  <td><?= htmlspecialchars($emp['statut'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['type_contrat'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['type_statut_contrat'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['diplome'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['specialite_diplome'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['niveau_etudes'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['structure'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['categorie'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['section'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['echelon'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['superieur_hierarchique'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['date_embauche'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['date_sortie'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($emp['date_promotion'] ?? '-') ?></td>
                   <td><?= htmlspecialchars($emp['date_derniere_promotion'] ?? '-') ?></td>
  <td>
  <div class="btn-group">
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      ⚙️ Actions
    </button>
    <ul class="dropdown-menu">
      <li>
        <a class="dropdown-item text-warning" href="index.php?page=employees&edit=<?= $emp['id'] ?>">
          ✏️ Modifier
        </a>
      </li>
      <li>
        <a class="dropdown-item text-danger" href="index.php?page=employees&delete=<?= $emp['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet employé ?')">
          🗑️ Supprimer
        </a>
      </li>
      <li>
        <a class="dropdown-item text-success" target="_blank" href="attestation_travail.php?id=<?= $emp['id'] ?>&lang=fr">
          📄 Attestation FR
        </a>
      </li>
      <li>
        <a class="dropdown-item text-success" target="_blank" href="attestation_travail.php?id=<?= $emp['id'] ?>&lang=ar">
          📄 Attestation AR
        </a>
      </li>
    </ul>
  </div>
</td>
               </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="38" class="text-muted">Aucun employé trouvé.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <a href="index.php?page=dashboard" class="btn-retour mt-3">⬅ Retour</a>
  </div>

  <!-- Modal Employé -->
  <div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form method="post" action="index.php?page=employees">
          <div class="modal-header">
            <h5 class="modal-title"><?= $editing ? "✏️ Modifier" : "➕ Ajouter" ?> un employé</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <?php if ($editing && $edit_employee): ?>
              <input type="hidden" name="id" value="<?= $edit_employee['id'] ?>">
            <?php endif; ?>

            <div class="row g-3">
              <!-- Matricule, Nom, Prénom -->
              <div class="col-md-4"><label class="form-label">Matricule</label><input type="text" name="matricule" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['matricule']) : '' ?>"></div>
              <div class="col-md-4"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" required value="<?= $editing ? htmlspecialchars($edit_employee['nom']) : '' ?>"></div>
              <div class="col-md-4"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-control" required value="<?= $editing ? htmlspecialchars($edit_employee['prenom']) : '' ?>"></div>

              <!-- Date/Lieu Naissance, Sexe -->
              <div class="col-md-3"><label class="form-label">Date de naissance</label><input type="date" name="date_naissance" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['date_naissance']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Lieu de naissance</label><input type="text" name="lieu_naissance" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['lieu_naissance']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Sexe</label>
                <select name="sexe" class="form-select">
                  <option value="">-- Choisir --</option>
                  <option value="Homme" <?= ($editing && $edit_employee['sexe']=='Homme')?'selected':'' ?>>Homme</option>
                  <option value="Femme" <?= ($editing && $edit_employee['sexe']=='Femme')?'selected':'' ?>>Femme</option>
                </select>
              </div>
                 <div class="col-md-3">
  <label class="form-label">الاسم (Ar)</label>
  <input type="text" name="الاسم" class="form-control"
    value="<?= $editing ? htmlspecialchars($edit_employee['الاسم'] ?? '') : '' ?>">
</div>

<div class="col-md-3">
  <label class="form-label">اللقب (Ar)</label>
  <input type="text" name="اللقب" class="form-control"
    value="<?= $editing ? htmlspecialchars($edit_employee['اللقب'] ?? '') : '' ?>">
</div>

<div class="col-md-3">
  <label class="form-label">CNI</label>
  <input type="text" name="cni_numero" class="form-control"
    value="<?= $editing ? htmlspecialchars($edit_employee['cni_numero'] ?? '') : '' ?>">
</div>

<div class="col-md-3">
  <label class="form-label">NIF</label>
  <input type="text" name="nif" class="form-control"
    value="<?= $editing ? htmlspecialchars($edit_employee['nif'] ?? '') : '' ?>">
</div>

<div class="col-md-3">
  <label class="form-label">N° Assurance</label>
  <input type="text" name="numero_assurance" class="form-control"
    value="<?= $editing ? htmlspecialchars($edit_employee['numero_assurance'] ?? '') : '' ?>">
</div>

<div class="col-md-3">
  <label class="form-label">RIB / CCP</label>
  <input type="text" name="rib_ccp" class="form-control"
    value="<?= $editing ? htmlspecialchars($edit_employee['rib_ccp'] ?? '') : '' ?>">
</div>

              <!-- Situation familiale & enfants -->
              <div class="col-md-3"><label class="form-label">Situation familiale</label>
                <select name="situation_familiale" class="form-select">
                  <option value="">-- Choisir --</option>
                  <option value="Célibataire" <?= ($editing && $edit_employee['situation_familiale']=='Célibataire')?'selected':'' ?>>Célibataire</option>
                  <option value="Marié(e)" <?= ($editing && $edit_employee['situation_familiale']=='Marié(e)')?'selected':'' ?>>Marié(e)</option>
                  <option value="Divorcé(e)" <?= ($editing && $edit_employee['situation_familiale']=='Divorcé(e)')?'selected':'' ?>>Divorcé(e)</option>
                  <option value="Veuf(ve)" <?= ($editing && $edit_employee['situation_familiale']=='Veuf(ve)')?'selected':'' ?>>Veuf(ve)</option>
                </select>
              </div>
              <div class="col-md-2"><label class="form-label">Nombre enfants</label><input type="number" name="nombre_enfants" class="form-control" min="0" value="<?= $editing ? htmlspecialchars($edit_employee['nombre_enfants']) : '0' ?>"></div>

              <!-- Adresse, Email, Téléphone -->
              <div class="col-md-4"><label class="form-label">Adresse</label><input type="text" name="adresse" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['adresse']) : '' ?>"></div>
              <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['email']) : '' ?>"></div>
              <div class="col-md-2"><label class="form-label">Téléphone</label><input type="text" name="telephone" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['telephone']) : '' ?>"></div>

              <!-- Poste + Salaire + Fonction ID + Catégorie + Section -->
              <div class="col-md-4">
                <label class="form-label">Poste</label>
                <select name="poste" id="posteSelect" class="form-select">
                  <option value="">-- Choisir --</option>
                  <?php foreach ($fonctions as $f): ?>
                    <option 
                      value="<?= htmlspecialchars($f['nom_fonction']) ?>" 
                      data-id="<?= $f['id'] ?>"
                      data-salaire="<?= htmlspecialchars($f['salaire_base']) ?>" 
                      data-categorie="<?= htmlspecialchars($f['Catégorie']) ?>" 
                      data-section="<?= htmlspecialchars($f['section']) ?>"
                      <?= ($editing && $edit_employee['fonction_id']==$f['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($f['nom_fonction']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <input type="hidden" name="fonction_id" id="fonctionIdInput" value="<?= $editing ? $edit_employee['fonction_id'] : '' ?>">
              </div>

              <div class="col-md-2">
                <label class="form-label">Salaire</label>
                <input type="number" step="0.01" name="salaire" id="salaireInput" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['salaire']) : '' ?>">
              </div>

              <div class="col-md-2">
                <label class="form-label">Catégorie</label>
                <input type="text" name="categorie" id="categorieInput" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['categorie']) : '' ?>">
              </div>

              <div class="col-md-2">
                <label class="form-label">Section</label>
                <input type="text" name="section" id="sectionInput" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['section']) : '' ?>">
              </div>

              <div class="col-md-2">
                <label class="form-label">Échelon</label>
                <input type="text" name="echelon" id="echelonInput" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['echelon']) : '' ?>">
              </div>

              <!-- Département -->
              <div class="col-md-4"><label class="form-label">Département</label>
                <select name="departement_id" class="form-select">
                  <option value="">-- Choisir --</option>
                  <?php foreach ($departements as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= ($editing && $edit_employee['departement_id']==$d['id'])?'selected':'' ?>><?= htmlspecialchars($d['nom']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Solde congé, Statut, Type contrat -->
              <div class="col-md-2"><label class="form-label">Solde congé</label><input type="number" name="solde_conge" class="form-control" min="0" value="<?= $editing ? htmlspecialchars($edit_employee['solde_conge']) : '0' ?>"></div>
              <div class="col-md-3"><label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                  <option value="ACTIF" <?= ($editing && $edit_employee['statut']=='ACTIF')?'selected':'' ?>>ACTIF</option>
                  <option value="INACTIF" <?= ($editing && $edit_employee['statut']=='INACTIF')?'selected':'' ?>>INACTIF</option>
                </select>
              </div>
              <div class="col-md-3"><label class="form-label">Type contrat</label>
                <select name="type_contrat" class="form-select">
                  <option value="">-- Choisir --</option>
                  <option value="CDI" <?= ($editing && $edit_employee['type_contrat']=='CDI')?'selected':'' ?>>CDI</option>
                  <option value="CDD" <?= ($editing && $edit_employee['type_contrat']=='CDD')?'selected':'' ?>>CDD</option>
                  <option value="Stage" <?= ($editing && $edit_employee['type_contrat']=='Stage')?'selected':'' ?>>Stage</option>
                  <option value="Vacataire" <?= ($editing && $edit_employee['type_contrat']=='Vacataire')?'selected':'' ?>>Vacataire</option>
                </select>
              </div>

              <div class="col-md-3"><label class="form-label">Type statut contrat</label>
                <select name="type_statut_contrat" class="form-select">
                  <option value="">-- Choisir --</option>
                  <option value="Permanent" <?= ($editing && $edit_employee['type_statut_contrat']=='Permanent')?'selected':'' ?>>Permanent</option>
                  <option value="Contractuel" <?= ($editing && $edit_employee['type_statut_contrat']=='Contractuel')?'selected':'' ?>>Contractuel</option>
                  <option value="Stagiaire" <?= ($editing && $edit_employee['type_statut_contrat']=='Stagiaire')?'selected':'' ?>>Stagiaire</option>
                  <option value="Vacataire" <?= ($editing && $edit_employee['type_statut_contrat']=='Vacataire')?'selected':'' ?>>Vacataire</option>
                </select>
              </div>

              <!-- Diplômes / Spécialité / Études / Structure / Supérieur -->
              <div class="col-md-3"><label class="form-label">Diplôme</label><input type="text" name="diplome" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['diplome']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Spécialité</label><input type="text" name="specialite_diplome" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['specialite_diplome']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Niveau études</label><input type="text" name="niveau_etudes" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['niveau_etudes']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Structure</label><input type="text" name="structure" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['structure']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Supérieur hiérarchique</label><input type="text" name="superieur_hierarchique" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['superieur_hierarchique']) : '' ?>"></div>

              <!-- Dates -->
              <div class="col-md-3"><label class="form-label">Date embauche</label><input type="date" name="date_embauche" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['date_embauche']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Date sortie</label><input type="date" name="date_sortie" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['date_sortie']) : '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Date promotion</label><input type="date" name="date_promotion" class="form-control" value="<?= $editing ? htmlspecialchars($edit_employee['date_promotion']) : '' ?>"></div>
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

  // 💰 Auto-fill salaire / echelon / catégorie / section / fonction_id
  const posteSelect = document.getElementById('posteSelect');
  const salaireInput = document.getElementById('salaireInput');
  const echelonInput = document.getElementById('echelonInput');
  const fonctionIdInput = document.getElementById('fonctionIdInput');
  const categorieInput = document.getElementById('categorieInput');
  const sectionInput = document.getElementById('sectionInput');

  posteSelect.addEventListener('change', function() {
    const selected = this.selectedOptions[0];

    const salaire = selected.dataset.salaire || '';
    const categorie = selected.dataset.categorie || '';
    const section = selected.dataset.section || '';
    const fonctionId = selected.dataset.id || '';

    salaireInput.value = salaire;
    categorieInput.value = categorie;
    sectionInput.value = section;
    echelonInput.value = categorie && section ? `${categorie}_${section}` : '';
    fonctionIdInput.value = fonctionId;
  });

  // Auto-show modal if editing
  <?php if($editing): ?>
    const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
    modal.show();
  <?php endif; ?>
</script>
<script>
$(document).ready(function () {
  $('#employeesTable').DataTable({
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50, 100],
    ordering: true,
    searching: false, // ✅ garder la recherche PHP
    responsive: false,

    dom: '<"d-flex justify-content-between mb-2"B>rtip',

    buttons: [
      {
        extend: 'colvis',
        text: '👁️ Colonnes visibles',
        className: 'btn btn-outline-primary btn-sm'
      }
    ],

    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
    }
  });
});
</script>



</body>
</html>

















