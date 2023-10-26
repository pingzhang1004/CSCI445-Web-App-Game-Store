<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="Unit2_dbQueries.css">
</head>
<body>

<?php
include 'Unit2_header.php';
?>
<?php
include 'Unit2_database.php';
?>
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
        
        <!-- Dynamically showing the message about customer coount -->
        <p>Number of customers: <?= $customerCount; ?></p>

        <!-- Dynamically checking and showing the message about customer 2 -->
        <?php 
        $customer2 = findCustomerById($conn, 2);
        if ($customer2): 
        ?>
            <p>Customer 2 is <?= $customer2['first_name'] . " " . $customer2['last_name']; ?></p>
        <?php else: ?>
            <p>Customer 2 does not exist!</p>
        <?php endif; ?>

        <!-- Dynamically checking and showing the message about customer 3 -->
        <?php 
        $customer3 = findCustomerById($conn, 3);
        if ($customer3): 
        ?>
            <p>Customer 3 is <?= $customer3['first_name'] . " " . $customer3['last_name']; ?></p>
        <?php else: ?>
            <p>Customer 3 does not exist!</p>
        <?php endif; ?>

        <!-- Dynamically fetch and display customer by email: mmouse@mines.edu -->
        <?php 
        $customerByEmail = findCustomerByEmail($conn, "mmouse@mines.edu");
        if ($customerByEmail): 
        ?>
            <p>Finding customer by email: mmouse@mines.edu... <?= $customerByEmail['first_name'] . " " . $customerByEmail['last_name'] ?></p>
        <?php else: ?>
            <p>Customer with email mmouse@mines.edu not found!</p>
        <?php endif; ?>

        <!-- Dynamically fetch and display/add customer by email: dduck@mines.edu -->
        <?php 
        $nonExistentCustomer = findCustomerByEmail($conn, "dduck@mines.edu");
        if (!$nonExistentCustomer): 
            addCustomer($conn, "Donald", "Duck", "dduck@mines.edu");
        ?>
            <p>Adding new customer Donald Duck</p>
            <?php $nonExistentCustomer = findCustomerByEmail($conn, "dduck@mines.edu"); // Fetch the added customer again ?>
        <?php endif; ?>
        <p>Finding customer by email: dduck@mines.edu... <?= $nonExistentCustomer['first_name'] . " " . $nonExistentCustomer['last_name'] ?></p>
    </section>

    <section class="orders">
        <h1>Orders</h1>
        <!-- Dynamically fetch all orders -->
        <?php if (!$allOrders || $allOrders->num_rows == 0): ?>
            <p>No orders yet</p>
        <?php else: ?>
            <p>Adding an order</p>
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
                    <td><?= date("m/d/y h:i A", $order['timestamp']) ?></td>
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
        
        <?php
        // Simulating selling a small amount
        $productIdToSell = 2;  // This could be any product ID
        $quantitySold = 2;
        reduceStock($conn, $productIdToSell, $quantitySold);           
        // Displaying updated stock
        $currentStock = getCurrentStock($conn, $productIdToSell);
        ?>
        
        <p>Selling 2 <?= getProductNameById($conn, $productIdToSell); ?></p>
        <p>The new quantity for <?= getProductNameById($conn, $productIdToSell); ?> is <?= $currentStock; ?></p>
        
        <?php
        // Simulating selling a large amount
        $quantitySold = 2000;
        $message = reduceStock($conn, $productIdToSell, $quantitySold);
        if(is_string($message)) : 
        ?>
        <p><?= $message; ?></p>
        <?php
        else : 
            $currentStock = getCurrentStock($conn, $productIdToSell);
        ?>
        
        <p>Selling 2000 <?= getProductNameById($conn, $productIdToSell); ?></p>
        <p>The new quantity for <?= getProductNameById($conn, $productIdToSell); ?> is <?= $currentStock; ?></p>
        <?php endif; ?>
    </section>
</div>

<?php
include 'Unit2_footer.php';
?>
</body>
</html>