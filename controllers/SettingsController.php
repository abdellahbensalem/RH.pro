<?php
require_once "models/Settings.php";

class SettingsController {
    private $settingsModel;

    public function __construct($PDO) {
        $this->settingsModel = new Settings($PDO);
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user = $_SESSION['user'];
        $message = "";

        // Mettre à jour le mot de passe
        if (isset($_POST['update_password'])) {
            $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            if ($this->settingsModel->updatePassword($user['id'], $new_pass)) {
                $message = "✅ Mot de passe mis à jour.";
                // mettre aussi à jour la session avec le nouveau mot de passe haché
                $_SESSION['user'] = $this->settingsModel->getUserById($user['id']);
            } else {
                $message = "⚠️ Erreur lors de la mise à jour.";
            }
        }

        // Charger la vue
        include "views/settingsview.php";
    }
}
?>
