<?php
class UserModel {
    private $PDO;

    public function __construct($db) {
        $this->PDO = $db;
    }

    // 🔹 Ajouter utilisateur
    public function addUser($username, $password, $role, $employee_id = null) {
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->PDO->prepare("INSERT INTO users (username, password, role, employee_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $hashedPass, $role, $employee_id]);
    }

    // 🔹 Supprimer utilisateur
    public function deleteUser($id) {
        $stmt = $this->PDO->prepare("DELETE FROM users WHERE id=?");
        return $stmt->execute([$id]);
    }

    // 🔹 Rechercher ou lister les utilisateurs avec jointure employés
 public function searchUsers($search = "") {
    $sql = "SELECT u.*, CONCAT(e.prenom, ' ', e.nom) AS employee_nom_complet
            FROM users u
            LEFT JOIN employees e ON u.employee_id = e.id";
    if (!empty($search)) {
        $sql .= " WHERE u.username LIKE :q OR e.nom LIKE :q OR e.prenom LIKE :q";
    }
    $sql .= " ORDER BY u.id ASC";
    $stmt = $this->PDO->prepare($sql);
    if (!empty($search)) {
        $q = "%$search%";
        $stmt->bindValue(':q', $q);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // 🔹 Récupérer un utilisateur
    public function getUserById($id) {
        $stmt = $this->PDO->prepare("
            SELECT u.*, e.nom AS emp_nom, e.prenom AS emp_prenom
            FROM users u
            LEFT JOIN employees e ON u.employee_id = e.id
            WHERE u.id=?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Mettre à jour utilisateur
    public function updateUser($id, $username, $password, $role, $employee_id = null) {
        if (!empty($password)) {
            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->PDO->prepare("UPDATE users SET username=?, password=?, role=?, employee_id=? WHERE id=?");
            return $stmt->execute([$username, $hashedPass, $role, $employee_id, $id]);
        } else {
            $stmt = $this->PDO->prepare("UPDATE users SET username=?, role=?, employee_id=? WHERE id=?");
            return $stmt->execute([$username, $role, $employee_id, $id]);
        }
    }

    // 🔹 Récupérer tous les employés pour le menu déroulant
    public function getAllEmployees() {
        $stmt = $this->PDO->query("SELECT id, nom, prenom FROM employees ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>






