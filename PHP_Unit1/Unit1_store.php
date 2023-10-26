<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" Unit1_store.css">
    <link rel="stylesheet" href="Unit1_common.css">
    <title>The Puzzle and Game Store</title>
</head>
<body>

<?php
include 'Unit1_header.php';
?>
<div class="grid-container">
    <div class="main-comtent" id="content">
        <form action="Unit1_process_order.php" method="post" onchange="updateImage()">
            <!-- Personal Information Fieldset -->
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
                <select id="product" name="product" required>
                    <option value="" disabled selected>Select a puzzle</option>
                    <option value="product1" data-image="puzzle_Mermaid.png">Product 1 - $9.99</option>
                    <option value="product2" data-image="puzzle_Ocean.png">Product 2 - $14.99</option>
                    <option value="product3" data-image="puzzle_Pony.png">Product 3 - $19.99</option>
                </select><br><br>

                <label class="required" for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" max="100" value="1"><br><br>
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
    </div>  
</div> 

<script>
    function updateImage() {
        let productDropdown = document.getElementById('product');
        let selectedOption = productDropdown.options[productDropdown.selectedIndex];
        let imagePath = 'Images/' + selectedOption.getAttribute('data-image');
        let imgElem = document.getElementById('puzzleImage');
        
        if (selectedOption.value !== '') {
            imgElem.src = imagePath;
            imgElem.style.display = 'block';
        } else {
            imgElem.style.display = 'none';
        }
    }
</script>

<?php
include 'Unit1_footer.php';
?>

</body>
</html>