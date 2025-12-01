<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php?page=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des utilisateurs</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --sidebar-width: 240px;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 20px;
      margin-left: var(--sidebar-width); /* ✅ espace réservé pour la sidebar */
      transition: background 0.4s, color 0.4s;
    }

    body.dark { background: #2c3e50; color: #ecf0f1; }

    /* ✅ Quand la sidebar est cachée (sur mobile) */
    @media (max-width: 768px) {
      body {
        margin-left: 0;
        padding: 80px 15px 20px; /* espace pour le hamburger */
      }
    }

    h2 { text-align: center; color: #2980b9; margin-top: 0; }
    body.dark h2 { color: #ecf0f1; }

    .message {
      margin: 15px auto; padding: 10px; width: 60%;
      text-align: center; border-radius: 8px; font-size: 14px;
    }
    .success { background: #e6ffed; color: #2e7d32; }
    .error { background: #ffe6e6; color: #c0392b; }
    body.dark .success { background: #27ae60; color: #fff; }
    body.dark .error { background: #e74c3c; color: #fff; }

    .container {
      display: flex; gap: 20px; justify-content: center;
      margin-top: 20px; flex-wrap: wrap;
    }

    .form-container, .table-container {
      background: #fff; padding: 20px; border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      transition: background 0.4s, color 0.4s;
    }
    body.dark .form-container, body.dark .table-container {
      background: #34495e; color: #ecf0f1;
    }

    .form-container { width: 320px; }
    .table-container { flex: 1; min-width: 500px; }

    label { font-weight: bold; color: #2c3e50; }
    body.dark label { color: #ecf0f1; }

    input, select, button {
      width: 100%; padding: 10px; margin: 8px 0;
      border: 1px solid #ccc; border-radius: 8px;
      font-size: 14px;
      transition: background 0.4s, color 0.4s, border 0.4s;
    }

    body.dark input, body.dark select {
      background: #2c3e50; color: #ecf0f1; border: 1px solid #555;
    }

    button {
      background: #2980b9; color: white; font-weight: bold;
      cursor: pointer; transition: 0.3s;
    }
    button:hover { background: #1f6391; }

    table {
      width: 100%; border-collapse: collapse; margin-top: 10px;
    }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background: #2980b9; color: white; }
    body.dark th { background: #1f6391; }

    a.action { margin: 0 5px; text-decoration: none; font-weight: bold; }
    a.delete { color: red; }
    a.edit { color: green; }

    .search-bar { margin-bottom: 15px; text-align: right; }

    .btn-retour {
      display: inline-block; padding: 8px 15px; margin: 10px 0;
      background: #3498db; color: white; text-decoration: none;
      border-radius: 6px; font-size: 14px; transition: 0.3s;
    }
    .btn-retour:hover { background: #2980b9; }

    .dark-toggle {
      position: absolute; top: 15px; right: 20px;
      cursor: pointer; font-size: 20px; color: #2c3e50;
    }
    body.dark .dark-toggle { color: #ecf0f1; }
  </style>
</head>
<body>
  <i class="fa-solid fa-moon dark-toggle" id="darkToggle"></i>

  <h2>Gestion des utilisateurs</h2>

  <?php if (!empty($message)): ?>
    <div class="message <?= strpos($message,'✅')!==false || strpos($message,'✏️')!==false || strpos($message,'🗑️')!==false ? 'success':'error' ?>">
      <?= $message ?>
    </div>
  <?php endif; ?>

  <div class="container">
    <div class="form-container">
      <h3><?= isset($edit_user) ? "Modifier l’utilisateur" : "Ajouter un utilisateur" ?></h3>
      <form method="post">
        <input type="hidden" name="id" value="<?= isset($edit_user) ? $edit_user['id'] : '' ?>">
        <label>Nom d'utilisateur</label>
        <input type="text" name="username" required value="<?= isset($edit_user) ? htmlspecialchars($edit_user['username']) : '' ?>">

        <label>Mot de passe <?= isset($edit_user) ? "(laisser vide si inchangé)" : "" ?></label>
        <input type="text" name="password">

        <label>Rôle</label>
        <select name="role">
          <option value="admin" <?= isset($edit_user) && $edit_user['role']=="admin" ? "selected":"" ?>>Admin</option>
          <option value="manager" <?= isset($edit_user) && $edit_user['role']=="manager" ? "selected":"" ?>>Manager</option>
          <option value="employee" <?= isset($edit_user) && $edit_user['role']=="employee" ? "selected":"" ?>>Employé</option>
        </select>

        <label>Employé associé</label>
        <select name="employee_id">
          <option value="">-- Aucun employé --</option>
          <?php foreach ($employees as $emp): ?>
            <option value="<?= $emp['id'] ?>" 
              <?= isset($edit_user) && $edit_user['employee_id'] == $emp['id'] ? "selected" : "" ?>>
              <?= htmlspecialchars($emp['prenom'] . " " . $emp['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <?php if (isset($edit_user)): ?>
          <button type="submit" name="update">Mettre à jour</button>
          <a href="index.php?page=users" class="btn-retour">❌ Annuler</a>
        <?php else: ?>
          <button type="submit" name="add">Ajouter</button>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-container">
      <h3>Liste des utilisateurs</h3>
      <div class="search-bar">
        <form method="get">
          <input type="hidden" name="page" value="users">
          <input type="text" name="search" placeholder="Rechercher un utilisateur..." value="<?= htmlspecialchars($search) ?>">
          <button type="submit">🔍 Rechercher</button>
        </form>
      </div>

      <table>
        <tr>
          <th>ID</th>
          <th>Nom d’utilisateur</th>
          <th>Employé</th>
          <th>Rôle</th>
          <th>Action</th>
        </tr>
        <?php foreach ($result as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['employee_nom_complet'] ?? '—') ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <a href="index.php?page=users&edit=<?= $user['id'] ?>" class="action edit">✏️</a>
                <a href="index.php?page=users&delete=<?= $user['id'] ?>" class="action delete" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <a href="index.php?page=dashboard" class="btn-retour">⬅ Retour</a>

  <script>
    const darkToggle = document.getElementById("darkToggle");
    function toggleDarkMode() {
      document.body.classList.toggle("dark");
      if (document.body.classList.contains("dark")) {
        darkToggle.classList.replace("fa-moon", "fa-sun");
        localStorage.setItem("theme", "dark");
      } else {
        darkToggle.classList.replace("fa-sun", "fa-moon");
        localStorage.setItem("theme", "light");
      }
    }
    darkToggle.addEventListener("click", toggleDarkMode);
    window.onload = function() {
      if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark");
        darkToggle.classList.replace("fa-moon", "fa-sun");
      }
    }
  </script>
</body>
</html>




