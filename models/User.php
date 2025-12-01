<?php
class User {
    private $PDO;

    public function __construct($PDO) {
        $this->PDO = $PDO; // PDO
    }

    // Vérifier l'utilisateur
    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->PDO->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            return $row; // on renvoie les infos user
        }
        return false;
    }
}
?>
