<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" Unit5_adminProduct.css">
    <link rel="stylesheet" href="Unit5_common.css">
    <title>The Puzzle Product</title>
</head>
<body>

<?php include 'Unit5_header.php';?>
<?php include 'Unit5_get_puzzle_table.php';?>

<div class="grid-container">
    <div class="Puzzle-form-display" id="Puzzle-form-display">
        <!-- Coustom form display-->
        <p>Puzzles</p>
        <div id="Puzzles_display">
            <span id = "puzzleTable">
                <?php echo createPuzzleTable($conn,$allPuzzles); ?>
            </span>
        </div>
    </div>  
    <div class="main-comtent" id="content">
        <div id="errorMessages">
            <p> </p>
        </div>
        <form id="puzzleForm" >
            <!-- Puzzle Information Fieldset -->
            <input type="hidden" id="puzzle_id" name="puzzle_id" value="">
            <fieldset>
                <legend>Puzzle Info</legend>
                <label class="required" for="puzzle_name">Puzzle Name: </label>
                <input type="text" id="puzzle_name" name="puzzle_name" required>

                <label class="required" for="puzzle_image">Puzzle Image: </label>
                <input type="text" id="puzzle_image" name="puzzle_image" required>

                <label class="required" for="pieces">Pieces:</label>
                <input type="number" id="pieces" name="pieces" required min="0" value=""><br><br>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="0" value=""><br><br>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" min="0" value=""><br><br>
                
                <label for="makeInactive" class="checkbox-label">Make Inactive:</label>
                <input type="checkbox" id="makeInactive" name="makeInactive" value="1" class="checkbox-input">
            </fieldset>

            <button type="button" id="addButton">Add</button>
            <button type="button" id="updateButton">Update</button>
            <button type="button" id="deleteButton">Delete</button>
        </form>
    </div>     
</div> 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="Unit5_script.js"></script>
<script>
$(document).ready(function() {
    // This function checks required fields and displays an error message if necessary
    function validateRequiredFields() {
        var isValid = true;
        $('#errorMessages p').empty(); // Clear previous error messages

        // List of required fields
        var requiredFields = [
            { id: '#puzzle_name', message: 'Puzzle name must not be blank' },
            { id: '#puzzle_image', message: 'Puzzle image must not be blank' },
            { id: '#pieces', message: 'Number of pieces must be provided' },
            // Add more required fields here
        ];

        for (var field of requiredFields) {
            var value = $(field.id).val().trim();
            if (!value) {
                $('#errorMessages p').text(field.message);
                $(field.id).focus();
                isValid = false;
                break; // Exit the loop as soon as a required field is empty
            }
        }

        return isValid;
    }

    // Bind the validation and AJAX call to the 'Add' button click event
    $('#addButton').on('click', function() {
        if (validateRequiredFields()) {
            // If validation passes, proceed with AJAX
            var postData = $('#puzzleForm').serializeArray();
            postData.push({name: "action", value: "create"});

            $.ajax({
                type: 'POST',
                url: 'Unit5_adminOperation.php', 
                data: postData,
                success: function(response) {
                    $('#puzzleTable').html(response);
                    bindTableHighlighting(); // Re-bind the click events to the table rows
                    $('#puzzleForm')[0].reset();
                    $('#errorMessages').empty(); 
                },
                error: function(xhr, status, error) {
                    $('#errorMessages p').text('Error: ' + error);
                }
            });
        }
    });

    // Similar binding for the 'Update' button
    $('#updateButton').on('click', function() {
        if (validateRequiredFields()) {
            var postData = $('#puzzleForm').serializeArray();
            postData.push({name: "action", value: "update"});
            $.ajax({
                type: 'POST',
                url: 'Unit5_adminOperation.php', 
                data: postData,
                success: function(response) {
                    $('#puzzleTable').html(response);
                    bindTableHighlighting(); // Re-bind the click events to the table rows
                    $('#puzzleForm')[0].reset();
                    $('#errorMessages').empty(); 
                },
                error: function(xhr, status, error) {
                    $('#errorMessages p').text('Error: ' + error);
                }
            });
        }
    });

     // Bind the validation and AJAX call to the 'Delete' button click event
   
    $('#deleteButton').on('click', function() {
        var selectedProductId = $('#puzzle_id').val(); // Get the selected product ID
        if (selectedProductId) {
            // First check for orders
            $.ajax({
                type: 'POST',
                url: 'Unit5_adminOperation.php',
                data: { action: 'checkForOrders', puzzle_id: selectedProductId },
                success: function(response) {
                    if (response == true) {
                        // Handle the case where orders exist
                        alert('Cannot delete the product because there are existing orders.');
                    } else {
                        // If there are no orders, ask the user to confirm the deletion
                        if (confirm('Are you sure you want to delete this product?')) {
                            // Perform the delete operation
                            $.ajax({
                                type: 'POST',
                                url: 'Unit5_adminOperation.php',
                                data: { action: 'delete', puzzle_id: selectedProductId },
                                success: function(deleteResponse) {
                                    $('#puzzleTable').html(deleteResponse);
                                    bindTableHighlighting(); // Re-bind the click events to the table rows
                                    $('#puzzleForm')[0].reset();
                                    $('#errorMessages').empty(); 
                                },
                                error: function(xhr, status, error) {
                                    $('#errorMessages p').text('Error: ' + error);
                                }
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors that occur during the AJAX call
                    console.error('Order check failed:', error);
                }
            });
        }
    });
});
   
</script>

<?php
include 'Unit5_footer.php';
?>

</body>
</html>