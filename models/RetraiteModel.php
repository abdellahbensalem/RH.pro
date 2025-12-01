<?php
class RetraiteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function retraiteExists($employee_id) {
        $sql = "SELECT COUNT(*) FROM retraits WHERE employee_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$employee_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function addRetraite($employee_id, $motif = 'Départ automatique à la retraite (60 ans)') {
        if ($this->retraiteExists($employee_id)) return false;

        $sql = "INSERT INTO retraits (employee_id, type_retrait, montant, description, date_retrait, motif)
                VALUES (?, 'Automatique', 0, 'Départ automatique du système', CURDATE(), ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$employee_id, $motif]);
        return true;
    }

    public function getEmployes() {
        $sql = "SELECT id, nom, prenom, date_naissance, statut FROM employees";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRetraites() {
        $sql = "SELECT r.id, e.nom, e.prenom, e.date_naissance, 
                       r.date_retrait AS date_retraite, r.motif
                FROM retraits r
                JOIN employees e ON r.employee_id = e.id
                ORDER BY r.date_retrait DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


