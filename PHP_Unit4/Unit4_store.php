<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" Unit4_store.css">
    <link rel="stylesheet" href="Unit4_common.css">
    <title>The Puzzle and Game Store</title>
</head>
<body>

<?php include 'Unit4_header.php';?>
<?php include 'Unit4_database.php';?>
<?php date_default_timezone_set("America/Denver");?>

<div class="grid-container">
    <div class="main-comtent" id="content">
        <form action="Unit4_process_order.php" method="post" onchange="updateImage()">
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
                <select id="product" name="product" required onchange="updateMaxQuantity()">
                    <option value="" disabled selected>Select a puzzle</option>
                    <?php foreach($allPuzzles as $puzzle):
                        $productId = $puzzle['id'];
                        $productName = $puzzle['product_name'];
                        $pieces = $puzzle['puzzle_pieces'];
                        $imageName = $puzzle['image_name'];
                        $price = $puzzle['price'];
                        $instcok = $puzzle['in_stock'];
                    ?>
                    <option value="<?= $productId ?>"
                            data-image-name='<?= $imageName ?>'
                            data-in-stock='<?= $instcok ?>'
                            ><?= $productName ?> - <?= $pieces ?> pieces - $<?= $price ?></option>
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

<script>
    function updateImage() {
        let productDropdown = document.getElementById('product');
        let selectedOption = productDropdown.options[productDropdown.selectedIndex];
        let imagePath = 'Images/' + selectedOption.getAttribute('data-image-name');
        let imgElem = document.getElementById('puzzleImage');
        
        // New element to display stock message
        let stockMessageElem = document.getElementById('stockMessage'); 
        // Getting the stock quantity as an integer
        let inStock = parseInt(selectedOption.getAttribute('data-in-stock'), 10); 

        if (selectedOption.value !== '') {
            imgElem.src = imagePath;
            imgElem.style.display = 'block';

            // Stock message logic
            if (inStock === 0) {
                stockMessageElem.textContent = "SOLD OUT";
            } else if (inStock < 5) {
                stockMessageElem.textContent = `Only ${inStock} left!`;
            } else {
                stockMessageElem.textContent = ""; // Clearing the message if there's plenty of stock
            }

        } else {
            imgElem.style.display = 'none';
        }
    }

    function updateMaxQuantity() {
        var selectedProduct = document.getElementById('product').selectedOptions[0];
        var maxStock = selectedProduct.getAttribute('data-in-stock');
        let product_quantity = document.getElementById('quantity');
        product_quantity.value = '1';  // 在这里显式地设置数量为1

        if (maxStock > 0) {
            product_quantity.setAttribute('max', maxStock);
            product_quantity.setAttribute('min', '1');
        }
        else {
            product_quantity.setAttribute('max', maxStock);
            product_quantity.setAttribute('min', maxStock);
            product_quantity.value = '0';  // 如果库存为0，设置数量为0
        }
    }
</script>

<?php
include 'Unit4_footer.php';
?>

</body>
</html>