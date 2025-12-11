<?php
session_start();
require_once "config.php";

// 🔹 Charger tous les contrôleurs
require_once "controllers/LoginController.php";
require_once "controllers/RegisterController.php";
require_once "controllers/DashboardController.php";
require_once "controllers/EmployeeController.php";
require_once "controllers/AfficheController.php";
require_once "controllers/CongeController.php"; 
require_once "controllers/UpdateHRController.php";
require_once "controllers/UpdateControlController.php";
require_once "controllers/StatsController.php";
require_once "controllers/UserController.php";
require_once "controllers/SettingsController.php"; 
require_once "controllers/SettingsHRController.php"; 
require_once "controllers/EvaluationController.php"; 
require_once "controllers/PromotionController.php"; 
require_once "controllers/AutoRetraiteController.php"; 
require_once "controllers/AbsenceController.php"; 
require_once "controllers/FonctionController.php";
require_once "controllers/DepartementController.php";


// 🔹 Déterminer le rôle de l'utilisateur (ou guest si non connecté)
$role = $_SESSION['user']['role'] ?? 'guest';
if (isset($_SESSION['user']['id'])) {
    $_SESSION['user_id'] = $_SESSION['user']['id'];
}

// 🔹 Pages autorisées selon rôle
$pages_autorisees = [
    'admin' => ['dashboard','employees','affiche','departements','update_hr','conge','update_control','stats','users','settings','settingshr','evaluations','promotions','promotions_auto','retraites','absences','fonctions','register','login'],
    'employee' => ['dashboard','absences','conge','affiche','settings','login'],
    'guest' => ['login','register'] // visiteurs non connectés
];

// 🔹 Page demandée
$page = strtolower($_GET['page'] ?? 'login');

// 🔹 Bloquer l’accès si page non autorisée pour le rôle
if (!in_array($page, $pages_autorisees[$role])) {
    include "views/404.php";
    exit;
}


// 🔹 Bloquer l’accès direct depuis un autre site (sauf login/register)
if (!isset($_SERVER['HTTP_REFERER']) || stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    if (!in_array($page, ['login','register'])) {
         include "views/404.php";
        exit;
    }
}

// 🔹 Routeur sécurisé
switch ($page) {
    case 'dashboard':
        (new DashboardController($PDO))->index();
        break;

    case 'employees':
        (new EmployeeController($PDO))->index();
        break;

    case 'affiche':
        (new AfficheController($PDO))->index();
        break;
        case 'departements':
    (new DepartementController($PDO))->index();
    break;


    case 'update_hr':
        (new UpdateHRController($PDO))->index();
        break;

    case 'conge':   
        (new CongeController($PDO))->request();
        break;

    case 'update_control':
        (new UpdateControlController($PDO))->index();
        break;

    case 'stats':
        (new StatsController($PDO))->index();
        break;

    case 'users':
        (new UserController($PDO))->index();
        break;

    case 'settings':
        (new SettingsController($PDO))->index();
        break;

    case 'settingshr':
        (new SettingsHRController($PDO))->index();
        break;

    case 'evaluations':
        (new EvaluationController($PDO))->index();
        break;

    case 'promotions':
        $controller = new PromotionController($PDO);
        $action = $_GET['action'] ?? null;
        if ($action === 'add') {
            $controller->add();
        } else {
            $controller->index();
        }
        break;

    case 'promotions_auto':
        (new PromotionController($PDO))->auto();
        break;

    case 'retraites': 
        (new AutoRetraiteController($PDO))->index();
        break;

    case 'absences': 
        (new AbsenceController($PDO))->handleRequest();
        break;

    case 'fonctions': 
        (new FonctionController($PDO))->index();
        break;

    case 'register':
        (new RegisterController($PDO))->handleRequest();
        break;

    case 'login':
    default:
        (new LoginController($PDO))->handleRequest();
        break;
}




