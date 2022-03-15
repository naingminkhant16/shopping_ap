<?php
require '../config/config.php';
$id = $_GET['id'];
$statement = $pdo->prepare("DELETE FROM products WHERE id=:id");
$result=$statement->execute([':id' => $id]);
if($result)header("location: index.php");