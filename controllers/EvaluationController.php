<?php
require_once __DIR__ . "/../models/EvaluationModel.php";

class EvaluationController {
    private $model;

    public function __construct($PDO) {
        $this->model = new EvaluationModel($PDO);
    }

    public function index() {
        $message = "";
        $user = $_SESSION["user"] ?? null;

        if (!$user) {
            header("Location: index.php?page=login");
            exit;
        }

        // Vérifier rôle
        $role = $user["role"] ?? "employe";

        // 🔹 Admin / Directeur → CRUD complet
        if ($role === "admin" || $role === "directeur") {
            
            // Ajouter
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
                $employee_id   = $_POST["employee_id"] ?? null;
                $date_eval     = $_POST["date_eval"] ?? null;
                $note          = $_POST["note"] ?? null;
                $commentaire   = $_POST["commentaire"] ?? null;
                $evaluateur_id = $_POST["evaluateur_id"] ?? null;

                if ($this->model->add($employee_id, $date_eval, $note, $commentaire, $evaluateur_id)) {
                    $message = "✅ Évaluation ajoutée avec succès.";
                } else {
                    $message = "❌ Erreur lors de l’ajout.";
                }
            }

            // Supprimer
            if (isset($_GET["action"]) && $_GET["action"] === "delete" && isset($_GET["id"])) {
                if ($this->model->delete($_GET["id"])) {
                    $message = "✅ Évaluation supprimée.";
                } else {
                    $message = "❌ Erreur lors de la suppression.";
                }
            }

            // Tous les employés & toutes les évaluations
            $evaluations = $this->model->getAll();
            $employees   = $this->model->getEmployees();

        } else {
            // 🔹 Employé → voir seulement SES évaluations
            $employee_id = $user["employee_id"] ?? null;
            $evaluations = $this->model->getByEmployee($employee_id);
            $employees   = []; // inutile pour l’employé
        }

        include __DIR__ . "/../views/evaluationsview.php";
    }
}
?>






