<?php
session_start();
require "config/config.php";
if ($_POST) {
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=:id");
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($qty > $result['quantity']) {
        echo "<script>alert('Not enough items!');window.location.href='product_detail.php?pid=" . $id . "'</script>";
        exit();
    }
    if (isset($_SESSION['cart']['id' . $id])) {
        $_SESSION['cart']['id' . $id] += $qty;
    } else {
        $_SESSION['cart']['id' . $id] = $qty;
    }
    header("location: product_detail.php?pid=" . $id);
} elseif (isset($_GET)) {
    $id = $_GET['id'];
    $qty = $_GET['qty'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=:id");
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_SESSION['cart']['id' . $id])) {
        $_SESSION['cart']['id' . $id] += $qty;
    } else {
        $_SESSION['cart']['id' . $id] = $qty;
    }
    header("location: index.php");
}
