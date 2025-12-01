<?php
class Absence {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ Récupérer toutes les absences (pour admin)
    public function getAll($search = '', $statut = '') {
        $sql = "SELECT a.*, e.nom, e.prenom 
                FROM absences a 
                JOIN employees e ON a.employee_id = e.id 
                WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (e.nom LIKE :search OR e.prenom LIKE :search OR a.motif LIKE :search)";
        }

        if (!empty($statut)) {
            $sql .= " AND a.statut = :statut";
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        if (!empty($statut)) {
            $stmt->bindValue(':statut', $statut);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Récupérer les absences d’un employé par e-mail (pour user normal)
    public function getByEmail($email) {
        $sql = "SELECT a.*, e.nom, e.prenom 
                FROM absences a 
                JOIN employees e ON a.employee_id = e.id 
                WHERE e.email = :email 
                ORDER BY a.date_absence DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Récupérer une absence par ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM absences WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Ajouter une nouvelle absence
    public function create($data) {
        $sql = "INSERT INTO absences (employee_id, date_absence, duree, motif, justifie, justificatif, statut)
                VALUES (:employee_id, :date_absence, :duree, :motif, :justifie, :justificatif, :statut)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':employee_id'  => $data['employee_id'],
            ':date_absence' => $data['date_absence'],
            ':duree'        => $data['duree'],
            ':motif'        => $data['motif'],
            ':justifie'     => $data['justifie'],
            ':justificatif' => $data['justificatif'] ?? null,
            ':statut'       => $data['statut']
        ]);
    }

    // ✅ Mettre à jour une absence
    public function update($id, $data) {
        $sql = "UPDATE absences 
                SET employee_id=:employee_id, 
                    date_absence=:date_absence, 
                    duree=:duree, 
                    motif=:motif, 
                    justifie=:justifie, 
                    justificatif=:justificatif, 
                    statut=:statut 
                WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':employee_id'  => $data['employee_id'],
            ':date_absence' => $data['date_absence'],
            ':duree'        => $data['duree'],
            ':motif'        => $data['motif'],
            ':justifie'     => $data['justifie'],
            ':justificatif' => $data['justificatif'] ?? null,
            ':statut'       => $data['statut'],
            ':id'           => $id
        ]);
    }

    // ✅ Supprimer une absence
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM absences WHERE id = ?");
        $stmt->execute([$id]);
    }

    // ✅ Récupérer la liste des employés
    public function getEmployees() {
        $stmt = $this->pdo->query("SELECT id, nom, prenom, email FROM employees ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>







