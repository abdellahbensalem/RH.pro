<?php
require_once "models/Conge.php";

class CongeController {
    private $model;

    public function __construct($PDO) {
        $this->model = new Conge($PDO);
    }

    public function request() {
        

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user = $_SESSION['user'];
        $userRole = $user['role'];
        // ❌ Empêcher admin de faire une demande
if ($userRole === 'admin') {
    $message = "❌ Les administrateurs ne peuvent pas faire une demande de congé.";
    $employee = null; // désactive formulaire
    $typesConge = [];
    include "views/congeRequestView.php";
    return;
}

        $employee_id = $user['employee_id'] ?? $user['id'];

        $employee = $this->model->getEmployeeById($employee_id);
        $typesConge = $this->model->getTypesConge();
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $type_conge_id = $_POST['type_conge_id'] ?? null;
            $date_debut = $_POST['date_debut'] ?? null;
            $date_fin = $_POST['date_fin'] ?? null;
            $raison = trim($_POST['raison'] ?? '');

            if (!$type_conge_id) {
                $message = "⚠️ Veuillez choisir un type de congé.";
            } elseif ($date_fin < $date_debut) {
                $message = "⚠️ La date de fin doit être postérieure à la date de début.";
            } else {
                $added = $this->model->addConge($employee['id'], $type_conge_id, $date_debut, $date_fin, $raison);
                if ($added) {
                    header("Location: index.php?page=affiche&success=1");
                    exit;
                } else {
                    $message = "⚠️ Erreur lors de l'envoi de la demande.";
                }
            }
        }

        if (isset($_GET['success'])) {
            $message = "✅ Votre demande de congé a été envoyée avec succès !";
        }

        include "views/congeRequestView.php";
    }
}
?>




