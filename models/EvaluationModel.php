<?php
class EvaluationModel {
    private $PDO;

    public function __construct($db) {
        $this->PDO = $db;
    }

    // Récupérer toutes les évaluations (admin / directeur)
    public function getAll() {
        $stmt = $this->PDO->query("
            SELECT e.id, e.employee_id, emp.nom, emp.prenom, e.date_eval, e.note, e.commentaire, e.evaluateur_id
            FROM evaluations e
            LEFT JOIN employees emp ON e.employee_id = emp.id
            ORDER BY e.date_eval DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Récupérer uniquement les évaluations d’un employé
    public function getByEmployee($employee_id) {
        $stmt = $this->PDO->prepare("
            SELECT e.id, e.employee_id, emp.nom, emp.prenom, e.date_eval, e.note, e.commentaire, e.evaluateur_id
            FROM evaluations e
            LEFT JOIN employees emp ON e.employee_id = emp.id
            WHERE e.employee_id = ?
            ORDER BY e.date_eval DESC
        ");
        $stmt->execute([$employee_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter une évaluation
    public function add($employee_id, $date_eval, $note, $commentaire, $evaluateur_id) {
        $stmt = $this->PDO->prepare("
            INSERT INTO evaluations (employee_id, date_eval, note, commentaire, evaluateur_id) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$employee_id, $date_eval, $note, $commentaire, $evaluateur_id]);
    }

    // Supprimer une évaluation
    public function delete($id) {
        $stmt = $this->PDO->prepare("DELETE FROM evaluations WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Liste des employés pour les selects
    public function getEmployees() {
        $stmt = $this->PDO->query("SELECT id, nom, prenom FROM employees ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


