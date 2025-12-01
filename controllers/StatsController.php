<?php
require_once __DIR__ . "/../models/StatsModel.php";

class StatsController {
    private StatsModel $model;

    public function __construct(PDO $pdo) {
        $this->model = new StatsModel($pdo);
    }

    public function index(): void {
        // Vérifier session avant d'afficher (optionnel selon ton app)
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        // Récupération des données
        $total_employees = $this->model->getTotalEmployees();
        $total_conges    = $this->model->getTotalConges();
        $by_sex          = $this->model->getBySex();                 // [{sexe, total}, ...]
        $by_situation    = $this->model->getBySituationFamiliale();  // [{situation_familiale, total}, ...]
        $by_age          = $this->model->getByAgeGroup();            // [{age_group, total}, ...]
        $sex_by_age      = $this->model->getSexByAge();              // [{age_group,sexe,total}, ...]

        // Charger la vue (les variables seront disponibles dans la view)
        require_once __DIR__ . "/../views/statsView.php";
    }
}

