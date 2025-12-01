<?php
class Dashboard {
    private $PDO;

    public function __construct($PDO) {
        $this->PDO = $PDO;
    }

    // Total employés
    public function getTotalEmployees() {
        $sql = "SELECT COUNT(*) as total FROM employees";
        $stmt = $this->PDO->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Congés en attente
    public function getPendingLeaves() {
        $sql = "SELECT COUNT(*) as total FROM conges WHERE statut='EN_ATTENTE'";
        $stmt = $this->PDO->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Somme des salaires
    public function getTotalSalaries() {
        $sql = "SELECT SUM(salaire) as total FROM employees";
        $stmt = $this->PDO->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Employés par département
  // Employés par département
public function getEmployeesByDept() {
    $sql = "SELECT d.nom AS departement, COUNT(e.id) AS total
            FROM employees e
            LEFT JOIN departements d ON e.departement_id = d.id
            GROUP BY d.nom";
    $stmt = $this->PDO->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Congés par statut
    public function getLeavesByStatus() {
        $sql = "SELECT statut, COUNT(*) as total FROM conges GROUP BY statut";
        $stmt = $this->PDO->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getUserPendingLeaves($db, $userId) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM conges WHERE id_employe = ? AND statut = 'En attente'");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

function getUserTotalSalary($db, $userId) {
    $stmt = $db->prepare("SELECT SUM(montant) FROM salaires WHERE id_employe = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn() ?: 0;
}

function getUserLeaveCounts($db, $userId) {
    $stmt = $db->prepare("SELECT statut, COUNT(*) AS nb FROM conges WHERE id_employe = ? GROUP BY statut");
    $stmt->execute([$userId]);
    $data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    return [
        $data['Accepté'] ?? 0,
        $data['En attente'] ?? 0,
        $data['Refusé'] ?? 0
    ];
}

}
?>


