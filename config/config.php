<?php
define("DB_HOST", "localhost:8080");
define("DB_NAME", "shopping_ap");
define("DB_USER", "root");
define("DB_PASSWORD", "");
$option = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];
try {
    $pdo = new PDO("mysql:dbhost=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, $option);
} catch (PDOException $e) {
    $e->getMessage();
}
