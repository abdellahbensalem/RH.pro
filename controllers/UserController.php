<?php
require_once "models/UserModel.php";

class UserController {
    private $model;

    public function __construct($PDO) {
        $this->model = new UserModel($PDO);
    }

    public function index() {
        $message = "";
        $search = $_GET['search'] ?? "";
        $edit_user = null;

        if (isset($_POST['add'])) {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $role = $_POST['role'];
            $employee_id = $_POST['employee_id'];

            if (!empty($username) && !empty($password)) {
                if ($this->model->addUser($username, $password, $role, $employee_id)) {
                    $message = "✅ Utilisateur ajouté avec succès.";
                } else {
                    $message = "⚠️ Erreur lors de l'ajout de l'utilisateur.";
                }
            } else {
                $message = "⚠️ Nom ou mot de passe vide.";
            }
        }

        if (isset($_GET['delete'])) {
            $id = intval($_GET['delete']);
            if ($this->model->deleteUser($id)) {
                $message = "🗑️ Utilisateur supprimé.";
            } else {
                $message = "⚠️ Erreur lors de la suppression.";
            }
        }

        if (isset($_GET['edit'])) {
            $id = intval($_GET['edit']);
            $edit_user = $this->model->getUserById($id);
            if (!$edit_user) {
                $message = "⚠️ Utilisateur introuvable.";
            }
        }

        if (isset($_POST['update'])) {
            $id = intval($_POST['id']);
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $role = $_POST['role'];
            $employee_id = $_POST['employee_id'];

            if ($this->model->updateUser($id, $username, $password, $role, $employee_id)) {
                $message = "✏️ Utilisateur mis à jour.";
            } else {
                $message = "⚠️ Erreur lors de la mise à jour.";
            }
        }

        $result = $this->model->searchUsers($search);
        $employees = $this->model->getAllEmployees();

        require "views/userView.php";
    }
}
?>



