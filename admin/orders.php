<?php
// delete_product.php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../include/db.php';

$id = intval($_GET['id'] ?? 0);
if($id) {
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
}
header("Location: manage_products.php?msg=deleted");
exit;