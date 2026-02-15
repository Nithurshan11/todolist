<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $email, $password) {
        $stmt = $this->conn->prepare(
            "INSERT INTO users (username,email,password) VALUES (?,?,?)"
        );
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $username, $email, $hash);
        return $stmt->execute();
    }

    public function login($email) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email=?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>