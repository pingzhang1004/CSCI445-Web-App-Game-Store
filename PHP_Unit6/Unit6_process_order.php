<?php include 'Unit6_database.php';

// Accessing the viewed items cookie
$viewedItems = array(); // Default to an empty array
if (isset($_COOKIE['viewedItems'])) {
    $viewedItemsJson = $_COOKIE['viewedItems'];
    $viewedItems = json_decode($viewedItemsJson, true);
}
?>


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

// Remove the purchased item from the viewed items array
if (($key = array_search($productId, $viewedItems)) !== false) {
    unset($viewedItems[$key]);
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
    //delete cookie viewedItems 
    setcookie('viewedItems', '', time() - 3600); 
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Unit6_process_order.css">
    <title>Order Confirmation</title>
</head>
<body>
<?php include 'Unit6_header.php';?>

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
    <?php
    // 先将数据库结果集转换为数组

    $allPuzzles = fetchPuzzles($conn);
    $allPuzzlesArray = [];
    if ($allPuzzles->num_rows == 0) {
        echo 'No puzzles found.';
    } else {
        // 将结果集转换为数组
        while($row = $allPuzzles->fetch_assoc()) {
            $allPuzzlesArray[$row['id']] = $row;
        }
    }

    // 现在可以安全地显示剩余的已浏览项目和优惠信息了
    if (!empty($viewedItems)) {
        echo "<p class='Discount-Notice'>Based on your viewing history, we'd like to offer 20% off these items:</p>";
        echo "<ul class='viewed-items-offer'>";
        foreach ($viewedItems as $itemId) {
            // 检查 itemId 是否在 allPuzzlesArray 数组中
            if (isset($allPuzzlesArray[$itemId])) {
                $itemName = $allPuzzlesArray[$itemId]['product_name'];
                echo "<li>{$itemName}</li>";
            }
        }
        echo "</ul>";
    }
    ?>

</div>


<?php include 'Unit6_footer.php'?>;

</body>
</html>