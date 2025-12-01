<?php
$host = "localhost";
$user = "root";          // ton utilisateur MySQL
$pass = "";              // ton mot de passe MySQL
$dbname = "rh_pro_db";   // nouvelle base

try {
    $PDO = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
