<?php
class StatsModel {
    private $PDO;

    public function __construct(PDO $db) {
        $this->PDO = $db;
    }

    // Total employés
    public function getTotalEmployees(): int {
        $stmt = $this->PDO->query("SELECT COUNT(*) AS total FROM employees");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // Total congés
    public function getTotalConges(): int {
        $stmt = $this->PDO->query("SELECT COUNT(*) AS total FROM conges");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // Répartition par sexe
    public function getBySex(): array {
        $stmt = $this->PDO->query("SELECT COALESCE(sexe,'Inconnu') AS sexe, COUNT(*) AS total FROM employees GROUP BY sexe");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Répartition par situation familiale
    public function getBySituationFamiliale(): array {
        $stmt = $this->PDO->query("SELECT COALESCE(situation_familiale,'Inconnu') AS situation_familiale, COUNT(*) AS total FROM employees GROUP BY situation_familiale");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Répartition par tranche d'âge (labels configurables)
    public function getByAgeGroup(): array {
        $age_groups = [
            '19-25' => [19,25],
            '26-30' => [26,30],
            '31-35' => [31,35],
            '36-40' => [36,40],
            '41-45' => [41,45],
            '46-50' => [46,50],
            '51+'   => [51,150]
        ];

        $results = [];
        foreach ($age_groups as $label => $range) {
            $stmt = $this->PDO->prepare("
                SELECT COUNT(*) AS total
                FROM employees
                WHERE TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN :min AND :max
            ");
            $stmt->execute(['min' => $range[0], 'max' => $range[1]]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $results[] = [
                'age_group' => $label,
                'total' => (int)($data['total'] ?? 0)
            ];
        }
        return $results;
    }

    // Sexe par tranche d'âge (pour tableau et graphique empilé)
    public function getSexByAge(): array {
        $sql = "
            SELECT
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 19 AND 25 THEN '19-25'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 26 AND 30 THEN '26-30'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 31 AND 35 THEN '31-35'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 36 AND 40 THEN '36-40'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 41 AND 45 THEN '41-45'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 46 AND 50 THEN '46-50'
                    ELSE '51+'
                END AS age_group,
                COALESCE(sexe,'Inconnu') AS sexe,
                COUNT(*) AS total
            FROM employees
            GROUP BY age_group, sexe
            ORDER BY 
              FIELD(age_group,'19-25','26-30','31-35','36-40','41-45','46-50','51+'),
              sexe
        ";
        $stmt = $this->PDO->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



