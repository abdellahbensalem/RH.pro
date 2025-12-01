<?php
require_once "models/Absence.php";

class AbsenceController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Absence($pdo);
    }

    public function handleRequest() {
      

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'index':
                $this->index();
                break;

            case 'store':
                $this->store();
                break;

            case 'edit':
                $this->edit();
                break;

            case 'update':
                $this->update();
                break;

            case 'delete':
                $this->delete();
                break;

            // ✅ NOUVELLE ACTION : mise à jour du statut (Accepter / Refuser)
            case 'updateStatus':
                $this->updateStatus();
                break;

            default:
                $this->index();
                break;
        }
    }

    // ✅ Liste des absences
    private function index() {
        $search = $_GET['search'] ?? '';
        $statut = $_GET['statut'] ?? '';
        $user = $_SESSION['user'];

        // Si admin → voir toutes les absences
        if ($user['role'] === 'admin') {
            $absences = $this->model->getAll($search, $statut);
        } else {
            // Si employé → ne voir que ses absences
            $absences = $this->model->getByEmail($user['username']);
        }

        $employees = $this->model->getEmployees();
        require "views/absencesview.php";
    }

    // ✅ Ajouter une absence
    private function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'employee_id'   => $_POST['employee_id'],
                'date_absence'  => $_POST['date_absence'],
                'duree'         => $_POST['duree'],
                'motif'         => $_POST['motif'],
                'justifie'      => $_POST['justifie'],
                'statut'        => $_POST['statut'] ?? 'EN_ATTENTE',
            ];

            // Gestion du justificatif
            if (!empty($_FILES['justificatif']['name'])) {
                $file = $_FILES['justificatif'];
                $fileName = time() . "_" . basename($file['name']);
                $targetPath = "uploads/absences/" . $fileName;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $data['justificatif'] = $fileName;
                }
            }

            $this->model->create($data);
            header("Location: index.php?page=absences");
            exit;
        }
    }

    // ✅ Modifier une absence
    private function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?page=absences");
            exit;
        }

        $absence = $this->model->getById($id);
        $employees = $this->model->getEmployees();
        require "views/absencesview.php";
    }

    // ✅ Mise à jour des données
    private function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'employee_id'   => $_POST['employee_id'],
                'date_absence'  => $_POST['date_absence'],
                'duree'         => $_POST['duree'],
                'motif'         => $_POST['motif'],
                'justifie'      => $_POST['justifie'],
                'statut'        => $_POST['statut'] ?? 'EN_ATTENTE',
            ];

            // Gestion du justificatif
            if (!empty($_FILES['justificatif']['name'])) {
                $file = $_FILES['justificatif'];
                $fileName = time() . "_" . basename($file['name']);
                $targetPath = "uploads/absences/" . $fileName;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $data['justificatif'] = $fileName;
                }
            }

            $this->model->update($id, $data);
            header("Location: index.php?page=absences");
            exit;
        }
    }

    // ✅ Supprimer une absence
    private function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header("Location: index.php?page=absences");
        exit;
    }

    // ✅ NOUVELLE MÉTHODE : accepter ou refuser une absence
private function updateStatus() {
    if ($_SESSION['user']['role'] !== 'admin') {
        header("Location: index.php?page=absences");
        exit;
    }

    $id = $_GET['id'] ?? null;
    $statut = $_GET['statut'] ?? null;

    if ($id && in_array($statut, ['ACCEPTEE', 'REFUSEE'])) {
        // 🔹 Récupérer les anciennes données
        $absence = $this->model->getById($id);

        if ($absence) {
            // 🔹 Mettre à jour uniquement le statut
            $absence['statut'] = $statut;
            $this->model->update($id, $absence);
        }
    }

    header("Location: index.php?page=absences");
    exit;
}

}








