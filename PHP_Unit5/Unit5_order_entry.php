<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" Unit5_order_entry.css">
    <link rel="stylesheet" href="Unit5_common.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>The Puzzle and Game Store</title>
</head>
<body>

<?php include 'Unit5_header.php';?>
<?php include 'Unit5_database.php';?>
<?php date_default_timezone_set("America/Denver");?>

<div class="grid-container">
    <div class="main-comtent" id="content">
        <form id="orderForm" >
            <!-- Personal Information Fieldset -->
            <input type="hidden" id="orderTimestamp" value="<?php echo time(); ?>">
            <fieldset>
                <legend>Personal Info</legend>
                <label class="required" for="first_name">First Name: </label>
                <input type="text" id="first_name" name="first_name" pattern="[A-Za-z' ]+" required title="Names can only include letters, spaces, and apostrophe" onkeyup="showHint(this.value, 'first')">

                <label class="required" for="last_name">Last Name: </label>
                <input type="text" id="last_name" name="last_name" pattern="[A-Za-z' ]+" required title="Names can only include letters, spaces, and apostrophe" onkeyup="showHint(this.value, 'last')">

                <label class="required" for="email">Email: </label>
                <input type="email" id="email" name="email" required><br><br>
            </fieldset>

            <!-- Product Information Fieldset -->
            <fieldset>
                <legend>Product Info</legend>
                <label for="product">Select a product:</label>
                <select id="product" name="product" required onchange="showAvailable(); updateMaxQuantity();">
                    <option value = "" disabled selected>Select a puzzle</option>
                    <?php foreach($allPuzzles as $puzzle):?>
                        <?php if (!$puzzle['inactive']):?>
                        <?php
                            $productId = $puzzle['id'];
                            $productName = $puzzle['product_name'];
                            $pieces = $puzzle['puzzle_pieces'];
                            $price = $puzzle['price'];
                            $instcok = $puzzle['in_stock'];
                        ?>
                        <option value="<?= $productId ?>"
                            data-in-stock='<?= $instcok ?>'
                            ><?= $productName ?> - <?= $pieces ?> pieces - $<?= $price ?></option>
                        <?php endif; ?> 
                <?php endforeach; ?>   
                </select>

                <label for="availableStock">Available:</label>
                <input type="text" id="availableStock" readonly value="">

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>
            </fieldset>

            <input id="submit" type="submit" value="Purchase">
            <input id="Clear Fields" type="reset" value="Clear Fields">

            <script src="Unit5_script.js"></script>

        </form>
    </div>     

    <div class="Coustom-form-display" id="Coustom-form-display">
        <!-- Coustom form display-->
        <p>Choosing an existing customer</p>
        <div id="orderMessage"></div>
        <table id="customerTable" class="customerTable" style="display: none;">
            <thead>
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <!-- dynanic rows -->
            </tbody>
        </table>

    </div>  
</div> 


<?php
include 'Unit5_footer.php';
?>

</body>
</html>