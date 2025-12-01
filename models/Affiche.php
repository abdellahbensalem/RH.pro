<?php
class Affiche {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    // 🔹 Tous les congés (admin/directeur)
    public function getAllConges($search = '') {
        $sql = "
            SELECT c.id, e.nom, e.prenom, e.solde_conge,
                   t.nom_type AS type_conge, c.date_debut, c.date_fin, c.raison, c.statut
            FROM conges c
            JOIN employees e ON c.employee_id = e.id
            LEFT JOIN type_conge t ON c.type_conge_id = t.id
        ";
        $params = [];
        if ($search) {
            $sql .= " WHERE e.nom LIKE ? OR e.prenom LIKE ? OR c.statut LIKE ?";
            $like = "%$search%";
            $params = [$like, $like, $like];
        }
        $sql .= " ORDER BY c.date_debut DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Congés pour un employé spécifique
    public function getCongesByEmployeeId($employee_id, $search = '') {
        $sql = "
            SELECT c.id, e.nom, e.prenom, e.solde_conge,
                   t.nom_type AS type_conge, c.date_debut, c.date_fin, c.raison, c.statut
            FROM conges c
            JOIN employees e ON c.employee_id = e.id
            LEFT JOIN type_conge t ON c.type_conge_id = t.id
            WHERE c.employee_id = ?
        ";
        $params = [$employee_id];
        if ($search) {
            $sql .= " AND (e.nom LIKE ? OR e.prenom LIKE ? OR c.statut LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        $sql .= " ORDER BY c.date_debut DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Ajouter un congé
    public function addConge($employee_id, $type_conge_id, $date_debut, $date_fin, $raison) {
        $stmt = $this->pdo->prepare("SELECT solde_conge FROM employees WHERE id = ?");
        $stmt->execute([$employee_id]);
        $solde = $stmt->fetchColumn();
        if ($solde === false) return ['success'=>false,'message'=>"Employé introuvable."];
        $jours = (new DateTime($date_debut))->diff(new DateTime($date_fin))->days + 1;
        if ($jours > $solde) return ['success'=>false,'message'=>"Solde insuffisant."];

        $stmt = $this->pdo->prepare("
            INSERT INTO conges (employee_id, type_conge_id, date_debut, date_fin, raison, statut)
            VALUES (?, ?, ?, ?, ?, 'EN_ATTENTE')
        ");
        $ok = $stmt->execute([$employee_id,$type_conge_id,$date_debut,$date_fin,$raison]);
        return ['success'=>$ok,'message'=>$ok ? "Congé ajouté." : "Erreur lors de l'ajout."];
    }

    // 🔹 Mettre à jour statut
    public function updateCongeStatus($id, $statut) {
        $stmt = $this->pdo->prepare("SELECT employee_id, date_debut, date_fin FROM conges WHERE id = ?");
        $stmt->execute([$id]);
        $conge = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$conge) return false;

        if ($statut === "APPROUVE") {
            $jours = (new DateTime($conge['date_debut']))->diff(new DateTime($conge['date_fin']))->days + 1;
            $stmtSolde = $this->pdo->prepare("UPDATE employees SET solde_conge = solde_conge - ? WHERE id = ?");
            $stmtSolde->execute([$jours, $conge['employee_id']]);
        }

        $stmtUpdate = $this->pdo->prepare("UPDATE conges SET statut = ? WHERE id = ?");
        return $stmtUpdate->execute([$statut, $id]);
    }

    // 🔹 Supprimer un congé
    public function deleteConge($id) {
        $stmt = $this->pdo->prepare("DELETE FROM conges WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 🔹 Liste employés
    public function getAllEmployees() {
        $stmt = $this->pdo->query("SELECT id, nom, prenom, solde_conge FROM employees ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Liste types de congé
    public function getAllTypesConge() {
        $stmt = $this->pdo->query("SELECT id, nom_type FROM type_conge ORDER BY nom_type");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>















