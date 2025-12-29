<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - SIRH</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
      * {margin:0; padding:0; box-sizing:border-box;}
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #74ebd5, #9face6);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.3s;
      overflow: hidden; /* important pour le fond animé */
    }
    body.dark {
      background: linear-gradient(135deg, #1c1c1c, #2c3e50);
      color: #f0f0f0;
    }
    .login-box {
      width: 400px;
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0px 10px 30px rgba(0,0,0,0.2);
      text-align: center;
      animation: fadeIn 0.8s ease;
      transition: background 0.3s, color 0.3s;
      position: relative;
      z-index: 1; /* login au-dessus du background */
    }
    body.dark .login-box {
      background: #2c3e50;
      color: #f0f0f0;
    }
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

      /* ✅ ANIMATION ROTATION */
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
    .input-group { position: relative; margin: 15px 0; }
    .input-group input {
      width: 100%;
      padding: 12px 40px 12px 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      outline: none;
      font-size: 15px;
      transition: all 0.3s;
    }
    body.dark .input-group input { background: #444; border: 1px solid #777; color: #fff; }
    .input-group input:focus {
      border-color: #2980b9;
      box-shadow: 0 0 8px rgba(41, 128, 185, 0.3);
    }
    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 16px;
      color: #555;
    }
    body.dark .toggle-password { color: #ddd; }
    .btn {
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
    .btn:hover {
      background: linear-gradient(135deg, #1f6391, #2471a3);
      transform: scale(1.03);
    }
    .error {
      margin-top: 15px;
      padding: 10px;
      border-radius: 8px;
      background: #ffe6e6;
      color: #c0392b;
      font-size: 14px;
    }
    body.dark .error { background: #ffcccc; color: #900; }
    .footer { margin-top: 20px; font-size: 13px; color: #888; }
    body.dark .footer { color: #bbb; }
    .footer a { color: #2980b9; text-decoration: none; font-weight: bold; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .theme-toggle {
      position: absolute;
      top: 12px;
      right: 12px;
      cursor: pointer;
      font-size: 20px;
      color: #2980b9;
      z-index: 2;
    }
    body.dark .theme-toggle { color: #f1c40f; }

    /* === BACKGROUND ETOILES === */
    .stars {
      width: 1px;
      height: 1px;
      position: absolute;
      background: white;
      box-shadow: 2vw 5vh 2px white, 10vw 8vh 2px white, 15vw 15vh 1px white,
        22vw 22vh 1px white, 28vw 12vh 2px white, 32vw 32vh 1px white,
        38vw 18vh 2px white, 42vw 35vh 1px white, 48vw 25vh 2px white,
        53vw 42vh 1px white, 58vw 15vh 2px white, 63vw 38vh 1px white,
        68vw 28vh 2px white, 73vw 45vh 1px white, 78vw 32vh 2px white,
        83vw 48vh 1px white, 88vw 20vh 2px white, 93vw 52vh 1px white,
        98vw 35vh 2px white, 5vw 60vh 1px white, 12vw 65vh 2px white,
        18vw 72vh 1px white, 25vw 78vh 2px white, 30vw 85vh 1px white,
        35vw 68vh 2px white, 40vw 82vh 1px white, 45vw 92vh 2px white,
        50vw 75vh 1px white, 55vw 88vh 2px white, 60vw 95vh 1px white,
        65vw 72vh 2px white, 70vw 85vh 1px white, 75vw 78vh 2px white,
        80vw 92vh 1px white, 85vw 82vh 2px white, 90vw 88vh 1px white,
        95vw 75vh 2px white;
      animation: twinkle 8s infinite linear;
      z-index: 0;
    }
    .stars::after {
      content: "";
      position: absolute;
      width: 1px;
      height: 1px;
      background: white;
      box-shadow: 8vw 12vh 2px white, 16vw 18vh 1px white, 24vw 25vh 2px white,
        33vw 15vh 1px white, 41vw 28vh 2px white, 49vw 35vh 1px white,
        57vw 22vh 2px white, 65vw 42vh 1px white, 73vw 28vh 2px white,
        81vw 48vh 1px white, 89vw 32vh 1px white, 97vw 45vh 1px white,
        3vw 68vh 2px white, 11vw 75vh 1px white, 19vw 82vh 2px white,
        27vw 88vh 1px white, 35vw 72vh 2px white, 43vw 85vh 1px white,
        51vw 92vh 2px white, 59vw 78vh 1px white;
      animation: twinkle 6s infinite linear reverse;
    }
    .shooting-star {
      position: absolute;
      width: 100px;
      height: 2px;
      background: linear-gradient(90deg, white, transparent);
      animation: shoot 3s infinite ease-in;
      z-index: 0;
    }
    .shooting-star:nth-child(2) { top: 20%; left: -100px; animation-delay: 0s; }
    .shooting-star:nth-child(3) { top: 35%; left: -100px; animation-delay: 1s; }
    .shooting-star:nth-child(4) { top: 50%; left: -100px; animation-delay: 2s; }
    .shooting-star:nth-child(5) { top: 65%; left: -100px; animation-delay: 3s; }
    .shooting-star:nth-child(6) { top: 80%; left: -100px; animation-delay: 4s; }

    @keyframes twinkle {
      0%, 100% { opacity: 0.8; }
      50% { opacity: 0.4; }
    }
    @keyframes shoot {
      0% { transform: translateX(0) translateY(0) rotate(25deg); opacity: 1; }
      100% { transform: translateX(120vw) translateY(50vh) rotate(25deg); opacity: 0; }
    }
  </style>
</head>
<body>
  <!-- Fond étoilé -->
  <div class="stars"></div>
  <div class="shooting-star"></div>
  <div class="shooting-star"></div>
  <div class="shooting-star"></div>
  <div class="shooting-star"></div>
  <div class="shooting-star"></div>

  <i id="themeToggle" class="fa-solid fa-moon theme-toggle" role="button" aria-label="Basculer le thème"></i>

  <div class="login-box">
    <div class="logo">
      <img src="/GRH/images/logo.png" alt="Logo GRH" onerror="this.style.opacity=0.6;">
      <h1>SIRH</h1>
      <span>Gestion des Ressources Humaines</span>
    </div>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="input-group">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required autocomplete="username">
      </div>

      <div class="input-group">
        <input id="password" type="password" name="password" placeholder="Mot de passe" required autocomplete="current-password">
        <i id="toggleIcon" class="fa-solid fa-eye toggle-password" role="button" aria-label="Afficher mot de passe"></i>
      </div>

      <button type="submit" class="btn">Se connecter</button>
    </form>

    <div class="footer">
      <p>Pas de compte ? <a href="index.php?page=register">Créer un nouveau compte</a></p>
    </div>
  </div>

  <script>
   document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (toggleIcon && passwordInput) {
      if (passwordInput.type === 'password') {
        toggleIcon.classList.remove('fa-eye-slash'); toggleIcon.classList.add('fa-eye');
      } else {
        toggleIcon.classList.remove('fa-eye'); toggleIcon.classList.add('fa-eye-slash');
      }

      toggleIcon.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          toggleIcon.classList.remove('fa-eye');
          toggleIcon.classList.add('fa-eye-slash');
        } else {
          passwordInput.type = 'password';
          toggleIcon.classList.remove('fa-eye-slash');
          toggleIcon.classList.add('fa-eye');
        }
      });
    }

    function applyTheme(theme) {
      if (theme === 'dark') {
        document.body.classList.add('dark');
        themeToggle.classList.remove('fa-moon');
        themeToggle.classList.add('fa-sun');
      } else {
        document.body.classList.remove('dark');
        themeToggle.classList.remove('fa-sun');
        themeToggle.classList.add('fa-moon');
      }
      localStorage.setItem('theme', theme);
    }

    const stored = localStorage.getItem('theme');
    if (stored) {
      applyTheme(stored);
    } else {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      applyTheme(prefersDark ? 'dark' : 'light');
    }

    if (themeToggle) {
      themeToggle.addEventListener('click', function () {
        const now = document.body.classList.contains('dark') ? 'dark' : 'light';
        applyTheme(now === 'dark' ? 'light' : 'dark');
      });
    }

    if (themeToggle) themeToggle.tabIndex = 0;
    if (toggleIcon) toggleIcon.tabIndex = 0;

    [themeToggle, toggleIcon].forEach(el => {
      if (!el) return;
      el.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') el.click();
      });
    });
  });
  </script>
</body>
</html>


