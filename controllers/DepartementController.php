<?php
class DepartementController {
    private $PDO;

    public function __construct($PDO){
        $this->PDO = $PDO;
    }

    public function index(){
        $message = '';
        $editing = false;
        $edit_dept = null;

        // 🔹 Récupérer tous les départements
        $stmt = $this->PDO->query("SELECT * FROM departements");
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔹 Récupérer tous les employés
        $stmt = $this->PDO->query("SELECT * FROM employees");
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔹 Construire tableau des employés par département
        $empsByDept = [];
        foreach($employees as $e){
            if($e['departement_id']){
                $empsByDept[$e['departement_id']][] = $e;
            }
        }

        // 🔹 Ajouter info responsable dans chaque département
        foreach($departements as &$d){
            $respId = $d['responsable_id'] ?? null;
            if($respId){
                $resp = array_filter($employees, fn($e)=>$e['id']==$respId);
                if($resp){
                    $resp = array_values($resp)[0];
                    $d['responsable_prenom'] = $resp['prenom'];
                    $d['responsable_nom'] = $resp['nom'];
                }
            }
        }
        unset($d);

        // 🔹 Gérer édition
        if(isset($_GET['edit'])){
            $deptId = (int)$_GET['edit'];
            $edit_dept = array_filter($departements, fn($d)=>$d['id']==$deptId);
            if($edit_dept){
                $edit_dept = array_values($edit_dept)[0];
                $editing = true;
            }
        }

        // 🔹 Gérer suppression
        if(isset($_GET['delete'])){
            $deptId = (int)$_GET['delete'];
            $stmt = $this->PDO->prepare("DELETE FROM departements WHERE id = ?");
            $stmt->execute([$deptId]);
            header("Location: index.php?page=departements&message=Département supprimé avec succès");
            exit;
        }

        // 🔹 Gérer ajout/modification via POST
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $nom = $_POST['nom'];
            $responsable_id = $_POST['responsable_id'] ?: null;

            if(!empty($_POST['id'])){ // modification
                $stmt = $this->PDO->prepare("UPDATE departements SET nom=?, responsable_id=? WHERE id=?");
                $stmt->execute([$nom, $responsable_id, $_POST['id']]);
                $message = "Département modifié avec succès ✅";
            } else { // ajout
                $stmt = $this->PDO->prepare("INSERT INTO departements (nom,responsable_id) VALUES (?,?)");
                $stmt->execute([$nom, $responsable_id]);
                $message = "Département ajouté avec succès ✅";
            }
            header("Location: index.php?page=departements&message=".urlencode($message));
            exit;
        }

        // 🔹 Message éventuel depuis GET
        if(isset($_GET['message'])){
            $message = $_GET['message'];
        }

        // 🔹 Charger la vue
        include 'views/departementView.php';
    }
}



