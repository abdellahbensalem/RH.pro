<?php
require_once "config.php";
require_once "models/User.php";

class LoginController {
    private $model;

    public function __construct($PDO) {
        $this->model = new User($PDO);
    }

    public function handleRequest() {
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $user = $this->model->authenticate($username, $password);

            if ($user) {
                // Stocker les informations essentielles dans la session
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user['id']; // ✅ pour l’attestation
                $_SESSION['role'] = $user['role'] ?? 'employe';
                $_SESSION['displayName'] = $user['nom'] . ' ' . $user['prenom'];

                header("Location: index.php?page=dashboard");
                exit;
            } else {
                $error = "⚠️ Nom d'utilisateur ou mot de passe incorrect";
            }
        }

        include __DIR__ . "/../views/loginView.php";
    }
}
?>

     