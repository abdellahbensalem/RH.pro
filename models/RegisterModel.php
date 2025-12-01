<?php
class RegisterModel {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    // Vérifier si l’utilisateur existe déjà
    public function userExists($username) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Inscrire un nouvel utilisateur
    public function registerUser($username, $password, $role = "user") {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password, role) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$username, $password, $role]);
    }
}


