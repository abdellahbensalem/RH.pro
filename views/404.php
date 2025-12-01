<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>404 - Page introuvable</title>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: "Segoe UI", Arial, sans-serif;
        background: linear-gradient(135deg, #2c3e50, #4ca1af);
        color: #fff;
        overflow: hidden;
    }

    .card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 40px 60px;
        text-align: center;
        color: #fff;
        box-shadow: 0 0 30px rgba(0,0,0,0.3);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%   { transform: translateY(0); }
        50%  { transform: translateY(-12px); }
        100% { transform: translateY(0); }
    }

    h1 {
        font-size: 120px;
        margin: 0;
        font-weight: 900;
        text-shadow: 0px 0px 15px rgba(255,255,255,0.3);
    }

    h2 {
        font-size: 26px;
        margin: 10px 0 20px 0;
        font-weight: 400;
    }

    p {
        font-size: 17px;
        margin-bottom: 35px;
        color: #f1f1f1;
    }

    .btn {
        padding: 12px 28px;
        background: #ffcc00;
        color: #000;
        border-radius: 25px;
        text-decoration: none;
        font-size: 17px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn:hover {
        background: #ffdd33;
        transform: scale(1.05);
    }

    /* Animated floating circles in background */
    .circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        animation: move 10s infinite alternate;
    }

    .c1 { width: 140px; height: 140px; top: 12%; left: 10%; animation-duration: 8s; }
    .c2 { width: 200px; height: 200px; bottom: 20%; right: 15%; animation-duration: 12s; }
    .c3 { width: 100px; height: 100px; bottom: 10%; left: 30%; animation-duration: 10s; }

    @keyframes move {
        from { transform: translateY(0px) rotate(0deg); }
        to   { transform: translateY(-40px) rotate(30deg); }
    }
</style>
</head>
<body>

<div class="circle c1"></div>
<div class="circle c2"></div>
<div class="circle c3"></div>

<div class="card">
    <h1>404</h1>
    <h2>Oups... Page introuvable</h2>
    <p>La page que vous recherchez n’existe pas ou vous n’avez pas la permission d’y accéder.</p>
    <a class="btn" href="index.php?page=dashboard">Retour au tableau de bord</a>
</div>

</body>
</html>

