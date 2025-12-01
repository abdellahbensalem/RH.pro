<?php
require_once "models/RegisterModel.php";

class RegisterController {
    private $model;

    public function __construct($pdo) {
        $this->model = new RegisterModel($pdo);
    }

    public function handleRequest() {
        $success = "";
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';
            $role     = $_POST['role'] ?? '';

            // 🧩 Validation basique
            if (empty($username) || empty($password) || empty($confirm) || empty($role) || $role === "none") {
                $error = "⚠️ Veuillez remplir tous les champs, y compris le rôle avant de continuer.";
            } elseif ($password !== $confirm) {
                $error = "❌ Les mots de passe ne correspondent pas.";
            } elseif ($this->model->userExists($username)) {
                $error = "🚫 Ce nom d'utilisateur existe déjà.";
            } else {
                // 🔐 Hashage sécurisé du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                if ($this->model->registerUser($username, $hashedPassword, $role)) {
                    $success = "✅ Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                } else {
                    $error = "❌ Erreur lors de l'inscription. Veuillez réessayer.";
                }
            }
        }

        // 🔁 Charger la vue d’inscription
        include "views/registerView.php";
    }
}
?>





