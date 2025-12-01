<?php
class SettingsHR {
    private $PDO;

    public function __construct($db) {
        $this->PDO = $db;
    }

    // 🔹 Récupérer la règle RH (1 seule ligne)
    public function getRules() {
        $stmt = $this->PDO->query("SELECT * FROM regles_rh LIMIT 1");
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null; // ✅ PDO style
    }

    // 🔹 Mettre à jour la règle RH
    public function updateRules($augmentation, $conge, $grade) {
        $stmt = $this->PDO->prepare("
            UPDATE regles_rh 
            SET augmentation_salaire = ?, conge_mensuel = ?, changement_grade = ? 
            WHERE id = 1
        ");
        // ✅ PDO -> execute() avec tableau
        return $stmt->execute([$augmentation, $conge, $grade]);
    }
}

