<?php
include 'include/db.php';

$query = "INSERT INTO products (name, price, image, description, category)
VALUES ('Test Product', 1000, 'images/sample.jpg', 'Test desc', 'rings')";

if(mysqli_query($conn, $query)) {
    echo "Inserted successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>