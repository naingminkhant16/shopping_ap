<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php?error=login");
}
if ($_SESSION['user_role'] != 1) {
    header("location: login.php?error=password");
}
if (isset($_GET) && $_SESSION['user_role'] == 1) {
    $id = $_GET['id'];
    $role = $_GET['role'];
    // die(var_dump($role));
    if ($role == 1) {
        $statement = $pdo->prepare("UPDATE users SET role=:newrole WHERE id=:id");
        $result = $statement->execute([
            ":newrole" => 0,
            ":id" => $id
        ]);
        if ($result) {
            header("location: manageUsers.php");
        }
    } else {
        $statement = $pdo->prepare("UPDATE users SET role=:newrole WHERE id=:id");
        $result = $statement->execute([
            ":newrole" => 1,
            ":id" => $id
        ]);
        if ($result) {
            header("location: manageUsers.php");
        }
    }
} else {
    header("location: login.php?error=password");
}
