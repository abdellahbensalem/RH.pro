<?php
class AutoRetraiteController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        require_once __DIR__ . '/../models/RetraiteModel.php';
        $this->pdo = $pdo;
        $this->model = new RetraiteModel($pdo);
    }

    // ✅ Vue principale
    public function index() {
        $retraites = $this->model->getAllRetraites();
        $employes = $this->model->getEmployes();
        require "views/retraites_view.php";
    }

    // ✅ Lancer la retraite automatique (≥ 60 ans)
    public function runAutoRetraite() {
        $employes = $this->model->getEmployes();
        $count = 0;

        foreach ($employes as $emp) {
            if (!empty($emp['date_naissance'])) {
                $age = (new DateTime())->diff(new DateTime($emp['date_naissance']))->y;
                if ($age >= 60 && $emp['statut'] === 'ACTIF') {
                    if ($this->model->addRetraite($emp['id'])) {
                        $this->pdo->prepare("UPDATE employees SET statut = 'INACTIF' WHERE id = ?")->execute([$emp['id']]);
                        $count++;
                    }
                }
            }
        }

        header("Location: ../index.php?page=retraites&done=$count");
        exit;
    }
}

// ✅ Si on clique sur “Lancer la retraite automatique”
if (isset($_GET['action']) && $_GET['action'] === 'run') {
    require_once "../config.php";
    (new AutoRetraiteController($PDO))->runAutoRetraite();
}




