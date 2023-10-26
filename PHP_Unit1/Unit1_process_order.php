<?php
include 'Unit1_header.php';

// <!-- use the $_POST super global to fetch data from the form: -->
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$product = $_POST['product'];
$quantity = $_POST['quantity'];
$donation = $_POST['donation'];

// <!-- assume three products and their hard-coded prices: -->
switch($product) {
    case "product1":
        $productName = "product1";
        $price = 9.99;
        break;
    case "product2":
        $productName = "product2";
        $price = 14.99;
        break;
    // ... Add more products if necessary
    default:
        $productName = "product3";
        $price = 19.99;
        break;
}

// <!-- Calculate subtotal, tax, and total: -->
$subtotal = $quantity * $price;
$taxRate = 0.075; // 7.5% for example
$taxRate_percent = $taxRate *100;
$totalWithTax = $subtotal * (1 + $taxRate);

// If the user has selected "yes" for donation, round up the total
$totalWithDonation = $donation === "yes" ? ceil($totalWithTax) : $totalWithTax;

//<!-- display the order details: -->
echo "<h2>Order Confirmation</h2>";
echo "Thank you for your order, $firstName $lastName ($email).<br>";
echo "You have selected $quantity $productName @ $price.<br>";
echo "Subtotal: $$subtotal<br>";
echo "Total including tax ($taxRate_percent %): $$totalWithTax<br>";

if($donation === "yes") {
    echo "Total with donation: $$totalWithDonation<br>";
}

include 'Unit1_footer.php';

?>