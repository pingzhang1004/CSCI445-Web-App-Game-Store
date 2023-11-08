<?php
include 'Unit5_database.php';

if(isset($_GET['productId'])) {
    $conn = getConnection();
    $productId = $_GET['productId'];
    echo getCurrentStock($conn, $productId);
} else {
    echo "Error: No product ID provided.";
}
?>
