<?php
require_once "models/Dashboard.php";

class DashboardController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Dashboard($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php");
            exit;
        }

        $user = $_SESSION['user'];

        // 👤 Nom affiché
        if (!empty($user['prenom']) || !empty($user['nom'])) {
            $prenom = $user['prenom'] ?? '';
            $nom = $user['nom'] ?? '';
            $displayName = trim($prenom . ' ' . $nom);
        } elseif (!empty($user['username'])) {
            $displayName = $user['username'];
        } else {
            $displayName = "Utilisateur";
        }

        $role = $user['role'] ?? 'employe';

        // 📊 Données du tableau de bord
        $totalEmployees = $this->model->getTotalEmployees();
        $pendingLeaves  = $this->model->getPendingLeaves();
        $totalSalaries  = $this->model->getTotalSalaries();
        $totalSalariesFormatted = number_format($totalSalaries, 0, ',', ' ') . " DA";

        $departements = [];
        $deptCounts   = [];
        foreach ($this->model->getEmployeesByDept() as $row) {
            $departements[] = $row['departement'];
            $deptCounts[]   = $row['total'];
        }

        $leaveStatus = [];
        $leaveCounts = [];
        foreach ($this->model->getLeavesByStatus() as $row) {
            $leaveStatus[] = $row['statut'];
            $leaveCounts[] = $row['total'];
        }

        // 🧓 Vérifier les employés proches de la retraite (60 ans dans les 6 prochains mois)
        $sql = "
            SELECT id, nom, prenom, date_naissance 
            FROM employees
            WHERE statut = 'ACTIF'
              AND date_naissance IS NOT NULL
              AND DATE_ADD(date_naissance, INTERVAL 60 YEAR)
                  BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 6 MONTH)
        ";
        $stmt = $this->pdo->query($sql);
        $proches_retraite = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $nb_proches = count($proches_retraite);

        // Vue du tableau de bord
        include __DIR__ . "/../views/dashboardView.php";
    }
}
?>


