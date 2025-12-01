<?php
require_once __DIR__ . "/../models/FonctionModel.php";

class FonctionController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new FonctionModel($pdo);
    }

    public function index() {
        // 🔍 Recherche + pagination
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 10;
        $start = ($page - 1) * $perPage;

        // 🧩 Actions spéciales
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'autoAssign':
                    $count = $this->model->assignAutoFonctions();
                    header("Location: index.php?page=fonctions&done=$count");
                    exit;

                case 'generate':
                    $count = $this->model->generateFonctionsFromEmployees();
                    header("Location: index.php?page=fonctions&generated=$count");
                    exit;
            }
        }

        // ✏️ Édition
        $edit_fonction = null;
        if (isset($_GET['edit'])) {
            $edit_fonction = $this->model->getById((int)$_GET['edit']);
        }

        // 🗑 Suppression
        if (isset($_GET['delete'])) {
            $this->model->delete((int)$_GET['delete']);
            header("Location: index.php?page=fonctions");
            exit;
        }

        // ➕ Ajout ou modification
     // ➕ Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';
    $nom = trim($_POST['nom_fonction']);
    $salaire = (float)$_POST['salaire_base'];
    $Catégorie = (int)$_POST['Catégorie'];
    $Section = trim($_POST['Section']);

    if (!empty($id)) {
        // Modifier
        $this->model->update((int)$id, $nom, $salaire, $Catégorie, $Section);
    } else {
        // Ajouter
        $this->model->add($nom, $salaire, $Catégorie, $Section);
    }

    header("Location: index.php?page=fonctions");
    exit;
}


        // 📋 Récupération avec pagination
        $fonctions = $this->model->getPaginated($search, $start, $perPage);
        $total = $this->model->countAll($search);
        $totalPages = ceil($total / $perPage);

        // ✅ Variables envoyées à la vue
        $done = $_GET['done'] ?? null;

        require "views/fonctions_view.php";
    }
}
?>






