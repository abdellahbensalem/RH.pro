<?php
require_once "models/SettingsHR.php";

class SettingsHRController {
    private $model;

    public function __construct($db) {
        $this->model = new SettingsHR($db);
    }

    public function index() {
        // 🔐 Seul un admin peut gérer les règles
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }

        $rules = $this->model->getRules();
        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $augmentation = floatval($_POST['augmentation_salaire']);
            $conge = floatval($_POST['conge_mensuel']);
            $grade = floatval($_POST['changement_grade']);

            if ($this->model->updateRules($augmentation, $conge, $grade)) {
                $message = "✅ Règles mises à jour avec succès.";
                $rules = $this->model->getRules(); // recharger les nouvelles valeurs
            } else {
                $message = "⚠️ Erreur lors de la mise à jour.";
            }
        }

        require "views/settingsHRview.php";
    }
}
