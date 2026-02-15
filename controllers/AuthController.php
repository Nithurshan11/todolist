<?php
session_start();
require_once "../config/database.php";
require_once "../models/User.php";

$db = (new Database())->connect();
$user = new User($db);

if ($_POST['action'] == "register") {
    $user->register($_POST['username'], $_POST['email'], $_POST['password']);
    header("Location: ../views/login.php");
}

if ($_POST['action'] == "login") {
    $data = $user->login($_POST['email']);
    if ($data && password_verify($_POST['password'], $data['password'])) {
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        header("Location: ../views/dashboard.php");
    } else {
        echo "Login Failed";
    }
}
?>