<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un compte - SIRH</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #74ebd5, #9face6);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      transition: background 0.3s;
    }
    body.dark {
      background: linear-gradient(135deg, #1c1c1c, #2c3e50);
      color: #f0f0f0;
    }
    .register-box {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      width: 400px;
      text-align: center;
      transition: background 0.3s, color 0.3s;
      position: relative;
      z-index: 1;
    }
    body.dark .register-box {
      background: #2c3e50;
      color: #f0f0f0;
    }

    /* ✅ LOGO repris de login */
    .logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 20px;
    }
    .logo img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #2980b9;
      padding: 5px;
      background: #f4f6f9;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      animation: spin 30s linear infinite;
    }
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    body.dark .logo img { background: #1c1c1c; }
    .logo h1 { margin-top: 15px; font-size: 20px; font-weight: bold; }
    .logo span { font-size: 14px; color: #7f8c8d; }
    body.dark .logo span { color: #bbb; }

    .register-box input, 
    .register-box select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 8px;
      outline: none;
      font-size: 15px;
      transition: border 0.3s;
    }
    body.dark .register-box input,
    body.dark .register-box select {
      background: #444;
      border: 1px solid #777;
      color: #fff;
    }
    .register-box input:focus, .register-box select:focus {
      border-color: #2980b9;
      box-shadow: 0 0 8px rgba(41, 128, 185, 0.3);
    }

    .register-box button {
      width: 100%;
      padding: 12px;
      margin-top: 15px;
      background: linear-gradient(135deg, #2980b9, #3498db);
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.2s, background 0.3s;
    }
    .register-box button:hover {
      background: linear-gradient(135deg, #1f6391, #2471a3);
      transform: scale(1.03);
    }

    .footer {
      margin-top: 20px;
      font-size: 13px;
      color: #888;
    }
    body.dark .footer { color: #bbb; }
    .footer a { color: #2980b9; text-decoration: none; font-weight: bold; }

    .message {
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      font-size: 14px;
    }
    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }

    /* Icône toggle mode sombre */
    .dark-toggle {
      position: absolute;
      top: 20px;
      right: 20px;
      cursor: pointer;
      font-size: 20px;
      color: #2980b9;
    }
    body.dark .dark-toggle { color: #f1c40f; }
  </style>
</head>
<body>
  <i class="fa-solid fa-moon dark-toggle" id="darkToggle"></i>

  <div class="register-box">
    <!-- ✅ Logo repris de login -->
    <div class="logo">
      <img src="/GRH/images/logo.png" alt="Logo GRH" onerror="this.style.opacity=0.6;">
      <h1>SIRH</h1>
      <span>Gestion des Ressources Humaines</span>
    </div>

    <h2>Créer un compte</h2>

    <?php if (!empty($success)): ?>
      <div class="message success"><?= $success ?></div>
    <?php elseif (!empty($error)): ?>
      <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?page=register">
      <input type="text" name="username" placeholder="Nom d'utilisateur" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
      
     <select name="role" required>
    <option value="none">-- Sélectionnez un rôle --</option>
    <option value="admin">Administrateur</option>
    <option value="user">Employé</option>
</select>

      
      <button type="submit">S'inscrire</button>
    </form>

    <div class="footer">
      <p>Déjà un compte ? <a href="index.php?page=login">Se connecter</a></p>
    </div>
  </div>

  <script>
    // Toggle mode sombre
    const toggle = document.getElementById("darkToggle");
    const body = document.body;
    toggle.addEventListener("click", () => {
      body.classList.toggle("dark");
      toggle.classList.toggle("fa-sun");
      toggle.classList.toggle("fa-moon");
    });
  </script>
</body>
</html>



