<?php
class Departement {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les départements
    public function getAll() {
        $stmt = $this->pdo->query("SELECT d.*, e.nom AS responsable_nom, e.prenom AS responsable_prenom 
                                   FROM departements d
                                   LEFT JOIN employees e ON d.responsable_id = e.id
                                   ORDER BY d.id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un département par id
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM departements WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter un département
    public function add($nom, $responsable_id = null) {
        $stmt = $this->pdo->prepare("INSERT INTO departements (nom, responsable_id) VALUES (?, ?)");
        return $stmt->execute([$nom, $responsable_id]);
    }

    // Modifier un département
    public function update($id, $nom, $responsable_id = null) {
        $stmt = $this->pdo->prepare("UPDATE departements SET nom = ?, responsable_id = ? WHERE id = ?");
        return $stmt->execute([$nom, $responsable_id, $id]);
    }

    // Supprimer un département
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM departements WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Récupérer tous les employés d’un département
    public function getEmployeesByDepartement($dept_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM employees WHERE departement_id = ?");
        $stmt->execute([$dept_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les employés (pour le select responsable)
    public function getAllEmployees() {
        $stmt = $this->pdo->query("SELECT id, prenom, nom FROM employees ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


