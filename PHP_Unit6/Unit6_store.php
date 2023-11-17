<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" Unit6_store.css">
    <link rel="stylesheet" href="Unit6_common.css">
    <title>The Puzzle and Game Store</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="Unit6_script.js"></script>
</head>
<body>

<?php include 'Unit6_header.php';?>
<?php include 'Unit6_database.php';?>
<?php date_default_timezone_set("America/Denver");?>

<div class="grid-container">
    <div class="main-comtent" id="content">
        <form action="Unit6_process_order.php" method="post">
            <!-- Personal Information Fieldset -->
            <input type="hidden" name="orderTimestamp" value="<?php echo time(); ?>">
            <fieldset>
                <legend>Personal Info</legend>
                <label class="required" for="first_name">First Name: </label>
                <input type="text" id="first_name" name="first_name" pattern="[A-Za-z' ]+" required title="Names can only include letters, spaces, and apostrophe">

                <label class="required" for="last_name">Last Name: </label>
                <input type="text" id="last_name" name="last_name" pattern="[A-Za-z' ]+" required title="Names can only include letters, spaces, and apostrophe">

                <label class="required" for="email">Email: </label>
                <input type="email" id="email" name="email" required><br><br>
            </fieldset>

            <!-- Product Information Fieldset -->
            <fieldset>
                <legend>Product Info</legend>
                <label class="required" for="product">Select a product:</label>
                
                <select id="product" name="product" required onchange="updateImage(); updateMaxQuantity(); trackViewedItem(this.value)">
                    <option value="" disabled selected>Select a puzzle</option>
                    <?php foreach($allPuzzles as $puzzle): ?>
                        <?php if (!$puzzle['inactive']): // Check if the product is not marked as inactive ?>
                            <?php
                                $productId = $puzzle['id'];
                                $productName = $puzzle['product_name'];
                                $pieces = $puzzle['puzzle_pieces'];
                                $imageName = $puzzle['image_name'];
                                $price = $puzzle['price'];
                                $inStock = $puzzle['in_stock'];
                            ?>
                            <option value="<?= $productId ?>"
                                data-image-name='<?= $imageName ?>'
                                data-in-stock='<?= $inStock ?>'
                                ><?= $productName ?> - <?= $pieces ?> pieces - $<?= $price ?></option>
                        <?php endif; ?>  
                    <?php endforeach; ?>   
                </select>

                <label class="required" for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>
            </fieldset>

            <!-- Donation Radio Buttons -->
            <fieldset>
                <legend>Donation</legend>
                <p>Round up to the nearest dollar for a doantion?</p>
                <input type="radio" id="yes" name="donation" value="yes" checked>
                <label for="yes">Yes</label><br>
                <input type="radio" id="no" name="donation" value="no">
                <label for="no">No</label><br>
            </fieldset>
            <input type="submit" value="Purchase">
        </form>
    </div>     

    <div class="image-display" id="image-display">
        <!-- Image Div -->
        <p>Select a puzzel to see the Image:</p>
        <div id="imageDiv">
            <img src="" alt="Selected Puzzle" id="puzzleImage" style="display: none;">
        </div>
        <p id="stockMessage"></p>
    </div>  
</div> 

<?php
include 'Unit6_footer.php';
?>

</body>
</html>