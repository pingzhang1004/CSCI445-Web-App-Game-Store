<?php
include 'Unit4_database.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// USE Unit4_database.php getConnection()
$conn = getConnection();

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$productId = $_POST['product'];
$quantity = $_POST['quantity'];
$receivedTimestamp = $_POST['orderTimestamp'];


$customer = findCustomerByEmail($conn, $email);
if (!$customer) {
    addCustomer($conn, $first_name, $last_name, $email);
    $customer = findCustomerByEmail($conn, $email);
}

$productDetails = [];
foreach ($allPuzzles as $puzzle) {
    if ($puzzle['id'] == $productId) {
        $productDetails = $puzzle;
        break;
    }
}

// Assume a simple tax calculation for now
$taxRate = 0.075; // 7.5%
$price = $productDetails['price'];
$subtotal = $price * $quantity;
$tax = $subtotal * $taxRate;
$total = $subtotal + $tax;
$donationAmount = 0;


$query = " ";

if (!orderExists($conn, $customer['id'], $productId, $receivedTimestamp)) {
    $query = "INSERT INTO ORDERS (product_id, customer_id, quantity, price, tax, donation, time_stamp) VALUES ('$productId', ' {$customer['id']}', '$quantity', '$price', '$tax','$donationAmount','$receivedTimestamp')";

    $quantityToReduce = $quantity; // The quantity to be reduced from stock (from your order data)
    $result_reduceStock = reduceStock($conn, $productId, $quantityToReduce);

    if ($result_reduceStock !== 1) {
        // Handle any errors here, for example:
        echo "Error updating stock!";
    }
}

$result = mysqli_query($conn, $query);

if ($result) {

    $message = "Order placed successfully for " . $first_name . " " . $last_name . " - " . $productDetails['product_name'] . " - Total: " . $total; // 替换为您的实际逻辑
    //$message = "Order placed successfully for " . $_POST['first_name'] . " " . $_POST['last_name'] . " - " . $_POST['product'] . " - Total: " . $total; // 替换为您的实际逻辑
    $response = ['success' => true, 'message' => $message];
} else {
    $response = ['success' => false, 'message' => 'Order placement failed.']; 
}

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn); // 关闭连接
?>
