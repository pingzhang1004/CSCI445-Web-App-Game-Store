<?php
include 'Unit5_database.php';

$str = $_GET["q"];
$type = $_GET["type"];

$hint = [];

if ($type === "first") {
    $result = getCustomersByFirstName($conn,$str);
} else if ($type === "last") {
    $result = getCustomersByLastName($conn,$str);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hint[] = [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email']
        ];
    }
}

echo json_encode($hint);  
?>
