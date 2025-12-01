<?php
require_once __DIR__ . "/../models/Employee.php";

class EmployeeController {
    private Employee $model;

    public function __construct(PDO $pdo) {
        $this->model = new Employee($pdo);
    }

    public function index() {
        $message = "";
        $editing = false;
        $edit_employee = null;
        $search = "";

        // 🔹 Ajouter ou modifier un employé
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'] ?? null;
            try {
                if ($id) {
                    $this->model->update((int)$id, $_POST);
                    $message = "✏️ Employé modifié avec succès.";
                } else {
                    $this->model->add($_POST);
                    $message = "✅ Employé ajouté avec succès.";
                }
            } catch (Exception $e) {
                $message = "⚠️ Erreur : " . $e->getMessage();
            }
        }

        // 🔹 Suppression
        if (isset($_GET['delete'])) {
            $this->model->delete((int)$_GET['delete']);
            $message = "🗑️ Employé supprimé.";
        }

        // 🔹 Édition
        if (isset($_GET['edit'])) {
            $edit_employee = $this->model->getById((int)$_GET['edit']);
            $editing = true;
        }

        // 🔹 Recherche
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
        }

        // 🔹 Données pour affichage
        $result = $this->model->getAll($search);
        $departements = $this->model->getDepartements();
        $fonctions = $this->model->getFonctions();

        include __DIR__ . "/../views/employeeView.php";
    }
}
?>









