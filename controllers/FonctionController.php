<?php
require_once __DIR__ . "/../models/FonctionModel.php";

class FonctionController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new FonctionModel($pdo);
    }

    public function index() {

        // 🔐 Actions spéciales
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

        // ➕ Ajout / modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? '';
            $nom = trim($_POST['nom_fonction']);
            $salaire = max(0, (float)$_POST['salaire_base']); // 🔒 pas négatif
            $categorie = (int)$_POST['Catégorie'];
            $section = trim($_POST['Section']);

            if (!empty($id)) {
                $this->model->update((int)$id, $nom, $salaire, $categorie, $section);
            } else {
                $this->model->add($nom, $salaire, $categorie, $section);
            }

            header("Location: index.php?page=fonctions");
            exit;
        }

        // 📋 Récupération TOTALE (IMPORTANT)
        $fonctions = $this->model->getAll();

        // ✅ Message
        $done = $_GET['done'] ?? null;

        require "views/fonctions_view.php";
    }
}







