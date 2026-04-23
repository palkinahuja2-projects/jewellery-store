<?php
session_start();

$id = intval($_GET['id']);
$action = $_GET['action'];

if(isset($_SESSION['cart'][$id])) {

    if($action == 'increase') {
        $_SESSION['cart'][$id]++;
    }

    if($action == 'decrease') {
        $_SESSION['cart'][$id]--;

        // Remove if quantity becomes 0
        if($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

header("Location: cart.php");
?>