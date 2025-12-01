<?php
class UpdateHR {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function getAllEmployees() {
        $sql = "SELECT e.*, f.nom_fonction, f.salaire_base, f.Section 
                FROM employees e 
                JOIN fonctions f ON e.fonction_id = f.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function runAutoUpdate() {
        $today = new DateTime();
        $sql = "SELECT e.*, f.Section, f.nom_fonction, f.salaire_base 
                FROM employees e 
                JOIN fonctions f ON e.fonction_id = f.id";
        $stmt = $this->pdo->query($sql);
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $countUpdated = 0;

        foreach ($employees as $emp) {
            if (empty($emp['date_embauche'])) continue;

            $embauche = new DateTime($emp['date_embauche']);
            $lastSalaryUpdate = !empty($emp['last_salary_update'])
                ? new DateTime($emp['last_salary_update'])
                : null;
            $lastCongeUpdate = !empty($emp['last_conge_update'])
                ? new DateTime($emp['last_conge_update'])
                : null;
            $date_derniere_promotion = !empty($emp['date_derniere_promotion'])
                ? new DateTime($emp['date_derniere_promotion'])
                : $embauche;

            $salaire = (float)$emp['salaire'];
            $solde_conge = (float)$emp['solde_conge'];
            $fonction_id = (int)$emp['fonction_id'];
            $updated = false;

            // 💰 +3 % de salaire chaque année
            if ($lastSalaryUpdate === null || $lastSalaryUpdate->diff($today)->y >= 1) {
                if ($date_derniere_promotion->diff($today)->y < 3) {
                    $salaire = round($salaire * 1.03, 2);
                    $lastSalaryUpdate = $today;
                    $updated = true;
                }
            }

            // 🏖 +2.5 jours de congés par mois
            if ($lastCongeUpdate === null || $lastCongeUpdate->diff($today)->m >= 1) {
                $solde_conge += 2.5;
                $lastCongeUpdate = $today;
                $updated = true;
            }

            // 🎓 Promotion automatique tous les 3 ans
            if ($date_derniere_promotion->diff($today)->y >= 3) {
                $nextFonction = $this->getNextFonction($fonction_id);
                if ($nextFonction) {
                    $fonction_id = $nextFonction['id'];
                    $salaire = $nextFonction['salaire_base'];
                    $date_derniere_promotion = $today;
                    $lastSalaryUpdate = $today;
                    $updated = true;
                }
            }

            if ($updated) {
                $sql = "UPDATE employees 
                        SET salaire=?, solde_conge=?, fonction_id=?, 
                            last_salary_update=?, last_conge_update=?, date_derniere_promotion=? 
                        WHERE id=?";
                $stmt2 = $this->pdo->prepare($sql);
                $stmt2->execute([
                    $salaire,
                    $solde_conge,
                    $fonction_id,
                    $lastSalaryUpdate ? $lastSalaryUpdate->format('Y-m-d') : null,
                    $lastCongeUpdate ? $lastCongeUpdate->format('Y-m-d') : null,
                    $date_derniere_promotion ? $date_derniere_promotion->format('Y-m-d') : null,
                    $emp['id']
                ]);
                $countUpdated++;
            }
        }

        return $countUpdated;
    }

    private function getNextFonction($fonction_id) {
        $stmt = $this->pdo->prepare("SELECT Section FROM fonctions WHERE id = ?");
        $stmt->execute([$fonction_id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$current) return null;

        $next = $current['Section'] + 1;
        $stmt2 = $this->pdo->prepare("SELECT * FROM fonctions WHERE Section = ? LIMIT 1");
        $stmt2->execute([$next]);
        return $stmt2->fetch(PDO::FETCH_ASSOC);
    }
}
?>




