<?php
require_once __DIR__ . '/../models/PromotionModel.php';

class PromotionController {
    private PromotionModel $model;

    public function __construct(PDO $pdo) {
        $this->model = new PromotionModel($pdo);
    }

    public function index() {
        $this->model->applyAutomaticPromotions();

        $search = $_GET['search'] ?? '';
        $promotions = $this->model->getAllPromotions($search);
        $fonctions = $this->model->getAllFonctions();
        $employees = $this->model->getAllEmployees();

        // Préparer les données pour afficher rang_niveau
        foreach ($promotions as &$p) {
            $p['ancien_fonction'] = $p['ancien_fonction_id'] ? $this->model->getFonctionById($p['ancien_fonction_id']) : null;
            $p['nouvelle_fonction'] = $p['nouvelle_fonction_id'] ? $this->model->getFonctionById($p['nouvelle_fonction_id']) : null;
        }

        require "views/promotions_view.php";
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee_id = $_POST['employee_id'];
            $ancien_fonction_id = $_POST['ancien_fonction_id'];
            $nouvelle_fonction_id = $_POST['nouvelle_fonction_id'];
            $date_promotion = $_POST['date_promotion'] ?? date('Y-m-d');
            $motif = $_POST['motif'];

            $ok = $this->model->insertPromotion(
                $employee_id,
                $ancien_fonction_id,
                $nouvelle_fonction_id,
                $date_promotion,
                $motif
            );

            if ($ok) {
                $this->model->updateEmployeeFonction($employee_id, $nouvelle_fonction_id);
                header("Location: index.php?page=promotions&done=1");
            } else {
                header("Location: index.php?page=promotions&none=1");
            }
            exit;
        }
    }
}
?>



















