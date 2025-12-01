<?php
class PromotionModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllPromotions(string $search = ''): array {
        $sql = "
            SELECT 
                p.*, 
                e.nom, e.prenom,
                f1.nom_fonction AS ancien_poste,
                f2.nom_fonction AS nouveau_poste,
                p.ancien_fonction_id,
                p.nouvelle_fonction_id
            FROM promotions p
            JOIN employees e ON p.employee_id = e.id
            LEFT JOIN fonctions f1 ON p.ancien_fonction_id = f1.id
            LEFT JOIN fonctions f2 ON p.nouvelle_fonction_id = f2.id
            WHERE 1=1
        ";

        $params = [];
        if (!empty($search)) {
            $sql .= " AND (e.nom LIKE ? OR e.prenom LIKE ? OR CONCAT(e.prenom,' ',e.nom) LIKE ?)";
            $like = "%$search%";
            $params = [$like, $like, $like];
        }

        $sql .= " ORDER BY p.date_promotion DESC, p.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFonctions(): array {
        $stmt = $this->pdo->query("SELECT id, nom_fonction, Catégorie, Section, salaire_base FROM fonctions ORDER BY Catégorie ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllEmployees(): array {
        $stmt = $this->pdo->query("SELECT id, nom, prenom, poste, fonction_id FROM employees ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertPromotion($employee_id, $ancien_fonction_id, $nouvelle_fonction_id, $date_promotion, $motif): bool {
        $sql = "INSERT INTO promotions (employee_id, ancien_fonction_id, nouvelle_fonction_id, date_promotion, motif, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$employee_id, $ancien_fonction_id, $nouvelle_fonction_id, $date_promotion, $motif]);
    }

    public function updateEmployeeFonction($employee_id, $nouvelle_fonction_id): bool {
        $stmt = $this->pdo->prepare("UPDATE employees SET fonction_id = ? WHERE id = ?");
        return $stmt->execute([$nouvelle_fonction_id, $employee_id]);
    }

    public function getFonctionById($id): ?array {
        $stmt = $this->pdo->prepare("SELECT nom_fonction, Catégorie, Section FROM fonctions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

 public function applyAutomaticPromotions(): void {
    try {
        // 1️⃣ Récupérer les employés éligibles à une promotion
        $sql = "
            SELECT e.id, e.nom, e.prenom, e.poste, e.fonction_id, e.date_embauche, e.date_promotion, e.salaire
            FROM employees e
            WHERE e.statut = 'Actif'
              AND (
                  (e.date_promotion IS NULL AND DATEDIFF(NOW(), e.date_embauche) >= 1095)
                  OR (e.date_promotion IS NOT NULL AND DATEDIFF(NOW(), e.date_promotion) >= 1095)
              )
        ";
        $stmt = $this->pdo->query($sql);
        $eligibleEmployees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($eligibleEmployees as $emp) {
            if (!$emp['fonction_id']) continue;

            // 2️⃣ Récupérer les infos de la fonction actuelle
            $stmt = $this->pdo->prepare("SELECT id, Catégorie, Section, nom_fonction, salaire_base FROM fonctions WHERE id = ?");
            $stmt->execute([$emp['fonction_id']]);
            $currentFunction = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$currentFunction) continue;

            // 3️⃣ Chercher la prochaine Section dans la même Catégorie
            $stmt = $this->pdo->prepare("
                SELECT id, nom_fonction, Section, salaire_base 
                FROM fonctions 
                WHERE Catégorie = ? AND Section > ? 
                ORDER BY Section ASC 
                LIMIT 1
            ");
            $stmt->execute([$currentFunction['Catégorie'], $currentFunction['Section']]);
            $nextFunction = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$nextFunction) continue; // Pas de promotion possible

            // 4️⃣ Insérer la promotion dans la table promotions
            $stmt = $this->pdo->prepare("
                INSERT INTO promotions (employee_id, ancien_fonction_id, nouvelle_fonction_id, date_promotion, motif)
                VALUES (?, ?, ?, NOW(), ?)
            ");
            $stmt->execute([
                $emp['id'],
                $emp['fonction_id'],
                $nextFunction['id'],
                'Promotion automatique basée sur Section après 3 ans'
            ]);

            // 5️⃣ Mettre à jour la fonction, le poste et le salaire de l’employé
            $stmt = $this->pdo->prepare("
                UPDATE employees 
                SET fonction_id = ?, poste = ?, salaire = ?, date_promotion = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $nextFunction['id'],
                $nextFunction['nom_fonction'],
                $nextFunction['salaire_base'],
                $emp['id']
            ]);
        }

    } catch (Exception $e) {
        error_log("Erreur applyAutomaticPromotions: " . $e->getMessage());
    }
}

}
?>





















