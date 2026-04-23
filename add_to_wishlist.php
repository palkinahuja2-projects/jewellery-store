<?php
session_start();

$id = intval($_GET['id']);

if(!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Avoid duplicates
if(!in_array($id, $_SESSION['wishlist'])) {
    $_SESSION['wishlist'][] = $id;
}

header("Location: wishlist.php");
?>