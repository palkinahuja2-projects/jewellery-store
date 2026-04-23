<?php
session_start();

$id = intval($_GET['id']);

if(isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$id]);
}

header("Location: wishlist.php");
?>