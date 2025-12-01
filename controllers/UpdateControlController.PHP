<?php
require_once __DIR__ . "/../models/update_control.php";


class UpdateControlController {
    private $model;

    public function __construct($PDO) {
        $this->model = new UpdateControl($PDO);
    }

    public function index() {
        $message = "";

        // Quand on clique sur "Appliquer les mises à jour automatiques"
        if (isset($_GET['action']) && $_GET['action'] === "auto_update") {
            $result = $this->model->runAutoUpdate();

            if ($result === false) {
                $message = "❌ Erreur lors de la mise à jour automatique.";
            } elseif ($result === 0) {
                $message = "ℹ️ Aucune donnée n’avait besoin de mise à jour.";
            } else {
                $message = "✅ Mise à jour automatique appliquée avec succès ($result lignes modifiées).";
            }
        }

        require "views/update_control_view.php";
    }
}


