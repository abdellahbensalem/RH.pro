<?php
class Conge {
    private $PDO;

    public function __construct($db) {
        $this->PDO = $db;
    }

    // 🔹 Récupérer un employé par ID
    public function getEmployeeById($id) {
        $stmt = $this->PDO->prepare("SELECT * FROM employees WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Récupérer tous les types de congé
    public function getTypesConge() {
        $stmt = $this->PDO->query("SELECT id, nom_type FROM type_conge ORDER BY nom_type");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Ajouter une demande de congé
    public function addConge($employee_id, $type_conge_id, $date_debut, $date_fin, $raison) {
        $stmt = $this->PDO->prepare("
            INSERT INTO conges (employee_id, type_conge_id, date_debut, date_fin, raison, statut)
            VALUES (?, ?, ?, ?, ?, 'EN_ATTENTE')
        ");
        return $stmt->execute([$employee_id, $type_conge_id, $date_debut, $date_fin, $raison]);
    }

    // 🔹 Récupérer les congés d’un employé
    public function getCongesByEmployeeId($employee_id, $search = '') {
        $sql = "
            SELECT c.*, t.nom_type 
            FROM conges c
            LEFT JOIN type_conge t ON c.type_conge_id = t.id
            WHERE c.employee_id = :employee_id
        ";
        if ($search) {
            $sql .= " AND (t.nom_type LIKE :search OR c.raison LIKE :search)";
        }
        $stmt = $this->PDO->prepare($sql);
        $stmt->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
        if ($search) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Récupérer tous les congés (pour admin)
    public function getAllConges($search = '') {
        $sql = "
            SELECT c.*, t.nom_type, e.nom, e.prenom
            FROM conges c
            LEFT JOIN type_conge t ON c.type_conge_id = t.id
            LEFT JOIN employees e ON c.employee_id = e.id
            WHERE 1
        ";
        if ($search) {
            $sql .= " AND (t.nom_type LIKE :search OR c.raison LIKE :search OR e.nom LIKE :search OR e.prenom LIKE :search)";
        }
        $stmt = $this->PDO->prepare($sql);
        if ($search) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>




