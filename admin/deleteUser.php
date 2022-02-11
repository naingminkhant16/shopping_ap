<?php
require '../config/config.php';
$id = $_GET['id'];
$statement = $pdo->prepare("DELETE FROM users WHERE id=:id");
$result = $statement->execute([':id' => $id]);
if ($result) {
    header("location: manageUsers.php");
}
