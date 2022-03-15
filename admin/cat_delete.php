<?php
require '../config/config.php';
$id = $_GET['id'];
$statement = $pdo->prepare("DELETE FROM categories WHERE id=:id");
$result = $statement->execute([':id' => $id]);
if ($result) header("location: category.php");
