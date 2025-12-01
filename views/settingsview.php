<?php include 'views/sidebar.php'; ?>

<div class="main" id="mainContent">
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Paramètres GRH</title>
<style>
body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; margin:0; padding:20px; color:#2c3e50; }
.card { max-width:400px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); text-align:center; }
h2 { color:#2980b9; margin-bottom:20px; }
input { padding:10px; width:90%; margin:10px 0; border:1px solid #ccc; border-radius:6px; }
button { padding:10px 20px; border:none; border-radius:6px; background:#2980b9; color:white; cursor:pointer; transition:0.3s; }
button:hover { background:#1f6391; }
.message { margin:10px 0; }
.btn-toggle { background:#27ae60; margin-top:10px; }
.btn-retour { display:inline-block; padding:8px 15px; margin-top:15px; background:#3498db; color:white; text-decoration:none; border-radius:6px; font-size:14px; transition:0.3s; }
.btn-retour:hover { background:#2980b9; }

/* Thème sombre */
body.dark {background:#1f1f1f; color:#f0f0f0;}
body.dark .card {background:#2c2c2c; color:#f0f0f0;}
body.dark input {background:#3a3a3a; color:#f0f0f0; border:1px solid #555;}
body.dark button {background:#63c0f7; color:#1f1f1f;}
body.dark .btn-toggle {background:#145173;}
</style>
</head>
<body>

<div class="card">
<h2>⚙ Paramètres</h2>

<?php if(!empty($message)): ?>
<p class="message"><?= $message ?></p>
<?php endif; ?>

<!-- Changer mot de passe -->
<form method="post">
    <input type="password" name="password" placeholder="Nouveau mot de passe" required>
    <button type="submit" name="update_password">Mettre à jour</button>
</form>

<!-- Changer thème -->
<button class="btn-toggle" onclick="toggleTheme()">Changer thème clair/sombre</button>

<div>
<a href="index.php?page=dashboard" class="btn-retour">⬅ Retour</a>
</div>
</div>

<script>
if(localStorage.getItem('theme')==='dark') document.body.classList.add('dark');
function toggleTheme() {
    document.body.classList.toggle('dark');
    localStorage.setItem('theme',
        document.body.classList.contains('dark') ? 'dark' : 'light');
}
</script>

</body>
</html>