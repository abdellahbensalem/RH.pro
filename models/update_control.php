<?php
class UpdateControl {
    private $db;

    public function __construct($PDO) {
        $this->db = $PDO;
    }

    public function runAutoUpdate() {
        try {
            $total = 0;

            // 1️⃣ Inactiver les employés ayant plus de 30 ans d'ancienneté
            $sql = "UPDATE employees 
                    SET statut = 'INACTIF', updated_at = NOW()
                    WHERE date_embauche IS NOT NULL
                      AND DATE_ADD(date_embauche, INTERVAL 30 YEAR) < CURDATE()
                      AND statut = 'ACTIF'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $total += $stmt->rowCount();

            // 2️⃣ Réinitialiser le solde de congé au 1er janvier
            $sql = "UPDATE employees 
                    SET solde_conge = 30, last_conge_update = CURDATE(), updated_at = NOW()
                    WHERE MONTH(CURDATE()) = 1 AND DAY(CURDATE()) = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $total += $stmt->rowCount();

            // 3️⃣ Mise à jour du salaire annuelle
            $sql = "UPDATE employees 
                    SET last_salary_update = CURDATE(), updated_at = NOW()
                    WHERE last_salary_update IS NULL
                       OR DATE_ADD(last_salary_update, INTERVAL 1 YEAR) < CURDATE()";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $total += $stmt->rowCount();

            // 4️⃣ Mise à jour automatique du grade tous les 3 ans
            $sql = "UPDATE employees 
                    SET grade = CASE
                        WHEN TIMESTAMPDIFF(YEAR, date_embauche, CURDATE()) >= 12 THEN 'Maître'
                        WHEN TIMESTAMPDIFF(YEAR, date_embauche, CURDATE()) >= 9 THEN 'Expert'
                        WHEN TIMESTAMPDIFF(YEAR, date_embauche, CURDATE()) >= 6 THEN 'Senior'
                        WHEN TIMESTAMPDIFF(YEAR, date_embauche, CURDATE()) >= 3 THEN 'Confirmé'
                        ELSE 'Junior'
                    END,
                    updated_at = NOW()
                    WHERE date_embauche IS NOT NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $total += $stmt->rowCount();
            // Inclure le contrôleur de promotion automatique
require_once __DIR__ . '/auto_promotion.php';
$promotionControl = new AutoPromotionController($this->db);
$promotions = $promotionControl->runAutoPromotion();

echo "<p style='color:green'>✔ $promotions promotion(s) automatique(s) appliquée(s).</p>";


            return $total;

        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
}




