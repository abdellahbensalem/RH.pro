<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>⚙ Paramètres des règles RH</title>
<style>
body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; padding:20px; }
.container { max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#2980b9; }
label { display:block; margin-top:15px; font-weight:bold; }
input { width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:6px; }
button { margin-top:20px; padding:10px 20px; background:#27ae60; color:white; border:none; border-radius:6px; cursor:pointer; transition:0.3s; }
button:hover { background:#1e8449; }
.message { color:green; font-weight:bold; text-align:center; margin-bottom:10px; }
</style>
</head>
<body>
<div class="container">
  <h2>⚙ Paramètres RH</h2>
  
  <?php if(!empty($message)): ?>
    <p class="message"><?= $message ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Augmentation Salaire (%)</label>
    <input type="number" step="1" name="augmentation_salaire" value="<?= $rules['augmentation_salaire'] ?>">

    <label>Congé Mensuel (jours)</label>
    <input type="number" step="1" name="conge_mensuel" value="<?= $rules['conge_mensuel'] ?>">

    <label>Changement de Grade (années)</label>
    <input type="number" step="1" name="changement_grade" value="<?= $rules['changement_grade'] ?>">

    <button type="submit">💾 Enregistrer</button>
  </form>
</div>
</body>
</html>
