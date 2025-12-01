<?php /** @var array $employee, $message, $typesConge */ ?>
<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
  <style>
    .form-page { font-family:'Segoe UI',sans-serif; background:#f4f6f9; min-height:100vh; margin-left:240px; padding:50px 20px; transition:0.3s; }
    .form-container { background:#fff; padding:30px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); width:450px; margin:auto; transition:0.3s; }
    h2 {text-align:center; color:#2980b9; margin-bottom:10px;}
    .employee-name {text-align:center; font-size:16px; font-weight:bold; color:#2c3e50; margin-bottom:20px;}
    label {display:block; margin-top:10px; color:#2c3e50; font-weight:bold;}
    input, textarea, select, button { width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ccc; font-size:14px; transition:0.3s; }
    textarea {resize:none; height:80px;}
    button { margin-top:15px; background:#2980b9; border:none; color:white; font-size:16px; font-weight:bold; cursor:pointer; }
    button:hover {background:#1f6391;}
    .message {margin-top:15px; text-align:center; padding:10px; border-radius:8px; font-size:14px;}
    .success {background:#e6ffed; color:#2e7d32;}
    .error {background:#ffe6e6; color:#c0392b;}
    .btn-retour { display:inline-block; padding:8px 15px; margin:15px auto; background:#3498db; color:white; text-decoration:none; border-radius:6px; font-size:14px; text-align:center; }
    .btn-retour:hover {background:#2980b9;}
    body.dark .form-container {background:#2c2c2c;}
    body.dark input, body.dark textarea, body.dark select {background:#3a3a3a; color:#f0f0f0; border:1px solid #555;}
    body.dark button {background:#1a5a8a;}
    body.dark button:hover {background:#145173;}
    body.dark .btn-retour {background:#1a5a8a;}
    @media (max-width:768px) { .form-page { margin-left:0; padding-top:80px; } }
  </style>

  <div class="form-page">
    <div class="form-container">
      <h2>Demande de congé</h2>

      <?php if ($employee): ?>
        <div class="employee-name">
          👤 Employé : <?= htmlspecialchars($employee['prenom']." ".$employee['nom']) ?>
        </div>
      <?php else: ?>
        <div class="employee-name error">⚠️ Employé introuvable</div>
      <?php endif; ?>

      <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message,'✅') !== false ? 'success' : 'error' ?>">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <form method="post">
        <label for="type_conge_id">Type de congé :</label>
        <select name="type_conge_id" required <?= $employee ? '' : 'disabled' ?>>
          <option value="">-- Choisir un type --</option>
          <?php foreach ($typesConge as $t): ?>
            <option value="<?= htmlspecialchars($t['id']) ?>"><?= htmlspecialchars($t['nom_type']) ?></option>
          <?php endforeach; ?>
        </select>

        <label for="date_debut">Date début :</label>
        <input type="date" name="date_debut" required <?= $employee ? '' : 'disabled' ?>>

        <label for="date_fin">Date fin :</label>
        <input type="date" name="date_fin" required <?= $employee ? '' : 'disabled' ?>>

        <label for="raison">Raison :</label>
        <textarea name="raison" required <?= $employee ? '' : 'disabled' ?>></textarea>

        <button type="submit" <?= $employee ? '' : 'disabled' ?>>Envoyer la demande</button>
      </form>

      <div style="text-align:center;">
        <a href="index.php?page=dashboard" class="btn-retour">⬅ Retour</a>
      </div>
    </div>
  </div>

  <script>
    if(localStorage.getItem('theme')==='dark') document.body.classList.add('dark');
  </script>
</div>







