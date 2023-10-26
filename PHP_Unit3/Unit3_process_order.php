<?php include 'Unit3_database.php';?>

<?php
// <!-- use the $_POST super global to fetch data from the form: -->
// Fetch user input
// Sanitize and validate the POST data.
$firstName = isset($_POST['first_name']) ? filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING) : '';
$lastName = isset($_POST['last_name']) ? filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING) : '';
$email = isset($_POST['email']) ? filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) : '';
$productId = isset($_POST['product']) ? filter_input(INPUT_POST, 'product', FILTER_VALIDATE_INT) : 0;
$quantity = isset($_POST['quantity']) ? filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) : 1;
$donationOption = isset($_POST['donation']) ? filter_input(INPUT_POST, 'donation', FILTER_SANITIZE_STRING) : 'no';
$receivedTimestamp = isset($_POST['orderTimestamp']) ? $_POST['orderTimestamp'] : null;

$conn = getConnection();
$customer = findCustomerByEmail($conn, $email);

$welcomeMessage = "";
if ($customer) {
    $welcomeMessage = "Hello " . $firstName . " " . $lastName . " - Welcome back!";
} else {
    $welcomeMessage = "Hello " . $firstName . " " . $lastName . " - Thanks for your first order!";
    addCustomer($conn, $firstName, $lastName, $email);
    $customer = findCustomerByEmail($conn, $email);
}

// Fetch product details from database
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

if ($donationOption == "yes") {
    $total = ceil($total);
}

$donationAmount = ($donationOption == "yes") ? $total - ($subtotal + $tax) : 0;
//addOrder($conn, $customer['id'], $productId, $quantity, $price, $tax, $donationAmount);
// Now, add the order to the database

if (!orderExists($conn, $customer['id'], $productId, $receivedTimestamp)) {
    addOrder($conn, $customer['id'], $productId, $quantity, $price, $tax, $donationAmount, $receivedTimestamp);
    $quantityToReduce = $quantity; // The quantity to be reduced from stock (from your order data)
    $result_reduceStock = reduceStock($conn, $productId, $quantityToReduce);

    if ($result_reduceStock !== 1) {
        // Handle any errors here, for example:
        echo "Error updating stock!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Unit3_process_order.css">
    <title>Order Confirmation</title>
</head>
<body>
<?php include 'Unit3_header.php';?>

<div class="order-confirmation">
    <p class="order-header"><?= $welcomeMessage ?></p>
    <p>We hope you enjoy your <strong><?= $productDetails['product_name'] ?></strong> puzzle!</p>   
    <div class="order-details">
        <p>Order details:</p>
        <p><?= $quantity ?> @ $<?= number_format($price, 2) ?>: $<?= number_format($subtotal, 2) ?></p>
        <p>Tax: $<?= number_format($tax, 2) ?></p>
        <p>Subtotal: $<?= number_format($subtotal + $tax, 2) ?></p>
        <p>Total with donation: $<?= number_format($total, 2) ?></p>
    </div>
    
    <p class="order-footer">We'll send special offers to <?= $email ?></p>
</div>

<?php include 'Unit3_footer.php'?>;

</body>
</html>
