<?php
require_once "models/Affiche.php";

class AfficheController {
    private $model;

    public function __construct($db) {
        $this->model = new Affiche($db);
    }

    public function index() {
        
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login"); exit;
        }

        $user = $_SESSION['user'];
        $userRole = $user['role'];
        $employee_id = $user['employee_id'] ?? null;
        $search = $_GET['search'] ?? '';
        $message = null;

        // Ajouter un congé
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_conge'])) {
            $result = $this->model->addConge($_POST['employee_id'], $_POST['type_conge_id'], $_POST['date_debut'], $_POST['date_fin'], $_POST['raison']);
            $message = $result['message'];
        }

        // Mettre à jour statut
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['conge_id'])) {
            $this->model->updateCongeStatus($_POST['conge_id'], $_POST['action']);
        }

        // Supprimer congé
        if (isset($_GET['delete'])) {
            $this->model->deleteConge($_GET['delete']);
        }

        // Récupérer congés
        if ($userRole === 'admin' || $userRole === 'directeur') {
            $conges = $this->model->getAllConges($search);
        } else {
            $conges = $this->model->getCongesByEmployeeId($employee_id, $search);
        }

        $employees = $this->model->getAllEmployees();
        $typesConge = $this->model->getAllTypesConge();

        require "views/AfficheView.php";
    }
}
?>















