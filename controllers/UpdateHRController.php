<?php
require_once "models/UpdateHR.php";

class UpdateHRController {
    private $model;

    public function __construct($db) {
        $this->model = new UpdateHR($db);
    }

    public function index() {
        // 🔐 Vérification que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }

        $message = null;

        // 🟢 Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_code'])) {
            $enteredCode = trim($_POST['admin_code']);
            $trueCode = "121212"; // 🔐 Code admin à personnaliser

            if ($enteredCode === $trueCode) {
                $count = $this->model->runAutoUpdate();
                $message = "✅ Mise à jour automatique terminée : $count employés mis à jour.";
            } else {
                $message = "❌ Code administrateur incorrect.";
            }
        }

        // 🔹 Charger tous les employés avec leur fonction
        $employees = $this->model->getAllEmployees();

        require "views/update_hr_view.php";
    }
}
?>





