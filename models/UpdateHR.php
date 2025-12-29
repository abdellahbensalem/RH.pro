<?php
class UpdateHR {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    // Récupérer tous les employés avec leur fonction et section
    public function getAllEmployees() {
        $sql = "SELECT e.*, f.nom_fonction, f.salaire_base, f.Section
                FROM employees e
                JOIN fonctions f ON e.fonction_id = f.id
                ORDER BY e.nom ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mise à jour automatique des salaires et congés
    public function runAutoUpdate() {
        $today = new DateTime();
        $employees = $this->getAllEmployees();
        $countUpdated = 0;

        foreach ($employees as $emp) {
            if (empty($emp['date_embauche'])) continue;

            $updated = false;

            $salaire = (float)$emp['salaire'];
            $solde_conge = (float)$emp['solde_conge'];

            $lastSalaryUpdate = !empty($emp['last_salary_update'])
                ? new DateTime($emp['last_salary_update'])
                : null;

            $lastCongeUpdate = !empty($emp['last_conge_update'])
                ? new DateTime($emp['last_conge_update'])
                : null;

            // 💰 Augmentation annuelle de 3 %
            if ($lastSalaryUpdate === null || $lastSalaryUpdate->diff($today)->y >= 1) {
                $salaire = round($salaire * 1.03, 2);
                $lastSalaryUpdate = $today;
                $updated = true;
            }

            // 🏖 Ajout de 2,5 jours de congés par mois
           $diff = $lastCongeUpdate ? $lastCongeUpdate->diff($today) : null;

if ($lastCongeUpdate === null || ($diff->y * 12 + $diff->m) >= 1) {
    $solde_conge = max(0, min($solde_conge + 2.5, 30));

    $lastCongeUpdate = $today;
    $updated = true;
}

            // Mettre à jour la base si changement
            if ($updated) {
                $sql = "UPDATE employees
                        SET salaire = ?, solde_conge = ?,
                            last_salary_update = ?, last_conge_update = ?
                        WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $salaire,
                    $solde_conge,
                    $lastSalaryUpdate ? $lastSalaryUpdate->format('Y-m-d') : null,
                    $lastCongeUpdate ? $lastCongeUpdate->format('Y-m-d') : null,
                    $emp['id']
                ]);
                $countUpdated++;
            }
        }

        return $countUpdated;
    }
}
?>    




