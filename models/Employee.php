<?php
class Employee {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // 🔹 Récupérer tous les employés (avec recherche optionnelle)
    public function getAll(string $search = ""): array {
        $sql = "
            SELECT e.*, d.nom AS departement_nom
            FROM employees e
            LEFT JOIN departements d ON e.departement_id = d.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (
                e.nom LIKE ? OR 
                e.prenom LIKE ? OR 
                e.matricule LIKE ? OR 
                e.email LIKE ? OR 
                e.poste LIKE ?
            )";
            $like = "%$search%";
            $params = [$like, $like, $like, $like, $like];
        }

        $sql .= " ORDER BY e.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Ajouter un employé
public function add(array $data): bool {
    $stmt = $this->pdo->prepare("
        INSERT INTO employees (
            matricule, nom, prenom, `الاسم`, `اللقب`,
            date_naissance, lieu_naissance, sexe, situation_familiale, nombre_enfants,
            adresse, email, telephone,
            cni_numero, nif, numero_assurance, rib_ccp,
            poste, fonction_id, departement_id,
            salaire, solde_conge, statut,
            type_contrat, type_statut_contrat,
            diplome, specialite_diplome, niveau_etudes,
            structure, categorie, section, echelon, superieur_hierarchique,
            date_embauche, date_sortie, date_promotion, date_derniere_promotion
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?
        )
    ");

    return $stmt->execute([
        $data['matricule'] ?? null,
        $data['nom'] ?? null,
        $data['prenom'] ?? null,
        $data['الاسم'] ?? null,
        $data['اللقب'] ?? null,

        $data['date_naissance'] ?? null,
        $data['lieu_naissance'] ?? null,
        $data['sexe'] ?? null,
        $data['situation_familiale'] ?? null,
        $data['nombre_enfants'] ?? 0,

        $data['adresse'] ?? null,
        $data['email'] ?? null,
        $data['telephone'] ?? null,

        $data['cni_numero'] ?? null,
        $data['nif'] ?? null,
        $data['numero_assurance'] ?? null,
        $data['rib_ccp'] ?? null,

        $data['poste'] ?? null,
        $data['fonction_id'] ?? null,
        $data['departement_id'] ?? null,

        $data['salaire'] ?? 0,
        $data['solde_conge'] ?? 0,
        $data['statut'] ?? 'ACTIF',

        $data['type_contrat'] ?? null,
        $data['type_statut_contrat'] ?? null,

        $data['diplome'] ?? null,
        $data['specialite_diplome'] ?? null,
        $data['niveau_etudes'] ?? null,

        $data['structure'] ?? null,
        $data['categorie'] ?? null,
        $data['section'] ?? null,
        $data['echelon'] ?? null,
        $data['superieur_hierarchique'] ?? null,

        $data['date_embauche'] ?? null,
        $data['date_sortie'] ?? null,
        $data['date_promotion'] ?? null,
        $data['date_derniere_promotion'] ?? null
    ]);
}


    // 🔹 Modifier un employé
  public function update(int $id, array $data): bool {
    $stmt = $this->pdo->prepare("
        UPDATE employees SET
            matricule=?, nom=?, prenom=?, `الاسم`=?, `اللقب`=?,
            date_naissance=?, lieu_naissance=?, sexe=?, situation_familiale=?, nombre_enfants=?,
            adresse=?, email=?, telephone=?,
            cni_numero=?, nif=?, numero_assurance=?, rib_ccp=?,
            poste=?, fonction_id=?, departement_id=?,
            salaire=?, solde_conge=?, statut=?,
            type_contrat=?, type_statut_contrat=?,
            diplome=?, specialite_diplome=?, niveau_etudes=?,
            structure=?, categorie=?, section=?, echelon=?, superieur_hierarchique=?,
            date_embauche=?, date_sortie=?, date_promotion=?, date_derniere_promotion=?
        WHERE id=?
    ");

return $stmt->execute([
    $data['matricule'] ?? null,
    $data['nom'] ?? null,
    $data['prenom'] ?? null,
    $data['الاسم'] ?? null,
    $data['اللقب'] ?? null,

    $data['date_naissance'] ?? null,
    $data['lieu_naissance'] ?? null,
    $data['sexe'] ?? null,
    $data['situation_familiale'] ?? null,
    $data['nombre_enfants'] ?? 0,

    $data['adresse'] ?? null,
    $data['email'] ?? null,
    $data['telephone'] ?? null,

    $data['cni_numero'] ?? null,
    $data['nif'] ?? null,
    $data['numero_assurance'] ?? null,
    $data['rib_ccp'] ?? null,

    $data['poste'] ?? null,
    $data['fonction_id'] ?? null,
    $data['departement_id'] ?? null,

    $data['salaire'] ?? 0,
    $data['solde_conge'] ?? 0,
    $data['statut'] ?? 'ACTIF',

    $data['type_contrat'] ?? null,
    $data['type_statut_contrat'] ?? null,

    $data['diplome'] ?? null,
    $data['specialite_diplome'] ?? null,
    $data['niveau_etudes'] ?? null,

    $data['structure'] ?? null,
    $data['categorie'] ?? null,
    $data['section'] ?? null,
    $data['echelon'] ?? null,
    $data['superieur_hierarchique'] ?? null,

    $data['date_embauche'] ?? null,
    $data['date_sortie'] ?? null,
    $data['date_promotion'] ?? null,
    $data['date_derniere_promotion'] ?? null,

    $id
]);

}


    // 🔹 Supprimer un employé
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM employees WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 🔹 Récupérer un employé par ID
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // 🔹 Récupérer les départements
    public function getDepartements(): array {
        return $this->pdo
            ->query("SELECT id, nom FROM departements ORDER BY nom ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Récupérer les fonctions
    public function getFonctions(): array {
        return $this->pdo
            ->query("SELECT id, nom_fonction, salaire_base, Catégorie, section FROM fonctions ORDER BY Catégorie DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>










