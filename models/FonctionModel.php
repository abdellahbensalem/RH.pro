<?php
class FonctionModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // 🔹 Récupération paginée (corrigée)
    public function getPaginated($search = '', $start = 0, $limit = 10) {
        $sql = "SELECT * FROM fonctions WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (nom_fonction LIKE :search OR Section LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY Catégorie DESC, nom_fonction ASC LIMIT $start, $limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Compter toutes les lignes (pour pagination)
    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) FROM fonctions WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (nom_fonction LIKE :search OR Section LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // 🔹 Récupération d'une seule fonction
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM fonctions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Ajout
    public function add($nom, $salaire, $Catégorie, $Section) {
        $stmt = $this->pdo->prepare("
            INSERT INTO fonctions (nom_fonction, salaire_base, Catégorie, Section)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$nom, $salaire, $Catégorie, $Section]);
    }

    // 🔹 Mise à jour
    public function update($id, $nom, $salaire, $Catégorie, $Section) {
        $stmt = $this->pdo->prepare("
            UPDATE fonctions
            SET nom_fonction = ?, salaire_base = ?, Catégorie = ?, Section = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nom, $salaire, $Catégorie, $Section, $id]);
    }

    // 🔹 Suppression
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM fonctions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 🔹 Génération automatique à partir des employés
    public function generateFonctionsFromEmployees() {
        $sql = "SELECT DISTINCT poste FROM employees WHERE poste IS NOT NULL AND poste != ''";
        $stmt = $this->pdo->query($sql);
        $postes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $count = 0;
        foreach ($postes as $poste) {
            $check = $this->pdo->prepare("SELECT COUNT(*) FROM fonctions WHERE nom_fonction = ?");
            $check->execute([$poste]);
            if ($check->fetchColumn() == 0) {
                $insert = $this->pdo->prepare("
                    INSERT INTO fonctions (nom_fonction, salaire_base, Catégorie, Section)
                    VALUES (?, 0, 1, 'Auto')
                ");
                if ($insert->execute([$poste])) {
                    $count++;
                }
            }
        }
        return $count;
    }

    // 🔹 Affectation automatique des fonctions aux employés
    public function assignAutoFonctions() {
        $sql = "
            UPDATE employees e
            JOIN fonctions f ON e.poste LIKE CONCAT('%', f.nom_fonction, '%')
            SET e.fonction_id = f.id
        ";
        return $this->pdo->exec($sql);
    }
    public function getAll() {
    $stmt = $this->pdo->query("SELECT * FROM fonctions ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>








