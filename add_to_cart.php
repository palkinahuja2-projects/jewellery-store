<?php
session_start();

$id = intval($_GET['id']);

if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// If product already exists → increase quantity
if(isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]++;
} else {
    $_SESSION['cart'][$id] = 1;
}

header("Location: cart.php");
?>