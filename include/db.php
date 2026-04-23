<?php
$conn = mysqli_connect("localhost", "root", "", "jewellery");

if (!$conn) {
    die("Connection failed");
}
function clean($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}
?>