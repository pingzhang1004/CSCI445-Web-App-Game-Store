<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (!isset($_SESSION['role'])) {
        header("Location: Unit6_index.php?err=Must log in first!");
        exit;
    } elseif ($_SESSION['role'] != 1) {
        header("Location: Unit6_index.php?err=You are not authorized for that page!");
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="Unit6_admin.css">
</head>
<body>

<?php include 'Unit6_header.php';?>
<?php include 'Unit6_database.php';?>
<?php date_default_timezone_set("America/Denver");?>

<!--Start displaying the information -->
<div class="main-comtent" id="content">
    <section class="customers">
        <h1>Customers</h1>
        <!-- Dynamically fetch all customers and count -->
        <table>
            <tr>
                <th>Customer #</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Email</th>
            </tr>
            <?php if ($allCustomers): ?>
                <?php foreach($allCustomers as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['last_name'] ?></td>
                    <td><?= $row['first_name'] ?></td>
                    <td><?= $row['email'] ?></td>            
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </section>

    <section class="orders">
        <h1>Orders</h1>
        <!-- Dynamically fetch all orders -->
        <?php if (!$allOrders || $allOrders->num_rows == 0): ?>
            <p>No orders yet</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Customer</th>
                    <th>Puzzle</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Tax</th>
                    <th>Donation</th>
                    <th>Total</th>
                </tr>
                <?php foreach($allOrders as $order): 
                    $customerName = getCustomerNameById($conn, $order['customer_id']);
                    $productName = getProductNameById($conn, $order['product_id']);
                    $total = ($order['quantity'] * $order['price']) + ($order['quantity'] * $order['tax']) + $order['donation'];
                ?>
                <tr>
                    <td><?= $customerName ?></td>
                    <td><?= $productName ?></td>
                    <td><?= date("m/d/y h:i A", $order['time_stamp']) ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td><?= $order['price'] ?></td>
                    <td><?= $order['tax'] ?></td>
                    <td><?= $order['donation'] ?></td>
                    <td><?= $total ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <!-- Dynamically showing the message about customer coount -->
        <p>Number of orders: <?= $ordersCount; ?></p>
    </section>

    <section class="products">
        <h1>Puzzles</h1>
        <!-- Dynamically fetch all products -->
        <table>
            <tr>
                <th>Puzzle ID</th>
                <th>Puzzle</th>
                <th># pieces</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php    
            if ($allPuzzles): 
                foreach($allPuzzles as $puzzle):
            ?>
            <tr>
                <td><?= $puzzle['id'] ?></td>
                <td><?= $puzzle['product_name'] ?></td>
                <td><?= $puzzle['puzzle_pieces'] ?></td>
                <td><?= $puzzle['in_stock'] ?></td>
                <td><?= $puzzle['price'] ?></td>
            </tr>
            <?php 
               endforeach; 
            endif; 
            ?>
        </table>
    </section>
</div>

<?php include 'Unit6_footer.php';?>

</body>
</html>