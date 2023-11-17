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

function showHint(str, type) {
    document.getElementById('orderMessage').innerHTML = "";
    if (str.length == 0) {
        document.getElementById("customerTable").style.display = "none"; 
        return;
    }
    
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const data = JSON.parse(this.responseText);
            updateHintsTable(data);
        }
    };
    xhttp.open("GET", "Unit6_get_customer_table.php?q=" + str + "&type=" + type, true);
    xhttp.send();
}

function updateHintsTable(data) {
    const tableBody = document.getElementById("customerTable").getElementsByTagName("tbody")[0];
    tableBody.innerHTML = ''; 

    if (data.length == 0) {
        const row = tableBody.insertRow();
        const cell = row.insertCell(0);
        cell.colSpan = 3; 
        cell.innerHTML = "No customers found";
        document.getElementById("customerTable").style.display = "table";
    } else {
        for (let item of data) {
            const row = tableBody.insertRow();
            row.insertCell(0).innerHTML = item.first_name;
            row.insertCell(1).innerHTML = item.last_name;
            row.insertCell(2).innerHTML = item.email;

            row.addEventListener('click', function() {
            const highlightedRows = tableBody.getElementsByClassName('highlighted');
            for (let highlightedRow of highlightedRows) {
                highlightedRow.classList.remove('highlighted');
            }
            this.classList.add('highlighted');
            document.getElementById('first_name').value = item.first_name;
            document.getElementById('last_name').value = item.last_name;
            document.getElementById('email').value = item.email;
            });
        }
        
        document.getElementById("customerTable").style.display = "table";
    }
}


function showAvailable() {
    var productId = document.getElementById('product').value;  
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('availableStock').value = this.responseText;
        }
    };
    
    xmlhttp.open("GET", "Unit6_get_quantity.php?productId=" + productId, true);
    xmlhttp.send();
}

function updateMaxQuantity() {
    var selectedProduct = document.getElementById('product').selectedOptions[0];
    var maxStock = selectedProduct.getAttribute('data-in-stock');
    let product_quantity = document.getElementById('quantity');
    product_quantity.value = '1';  

    if (maxStock > 0) {
        product_quantity.setAttribute('max', maxStock);
        product_quantity.setAttribute('min', '1');
    }
    else {
        product_quantity.setAttribute('max', maxStock);
        product_quantity.setAttribute('min', maxStock);
        product_quantity.value = '0';  
    }
}

// table-highlighting.js

function bindTableHighlighting() {
    var table = document.querySelector('.puzzleTable');
    if (table){
        var rows = table.getElementsByTagName('tr');
        for (var i = 1; i < rows.length; i++) { // Start from 1 to skip the table header
            rows[i].addEventListener('click', function() {
                // Remove highlighting from previously selected row and highlight the new one
                var current = table.getElementsByClassName('highlighted');
                if(current.length > 0){
                    current[0].className = current[0].className.replace('highlighted', '');
                }
                this.className += ' highlighted';

                // Set the data in form inputs
                document.getElementById('puzzle_id').value = this.getAttribute('data-id');
                document.getElementById('puzzle_name').value = this.cells[0].innerText;
                document.getElementById('puzzle_image').value = this.cells[1].innerText;
                document.getElementById('pieces').value = this.cells[2].innerText;
                document.getElementById('quantity').value = this.cells[3].innerText;
                document.getElementById('price').value = this.cells[4].innerText;
                
                // Check the checkbox if the Inactive column says 'Yes'
                document.getElementById('makeInactive').checked = this.cells[5].innerText === 'Yes';
            });
        }
    }
    
}

function setCookie(name, value, daysToLive) {
    // Encode value in order to escape semicolons, commas, and whitespace
    var cookie = name + "=" + encodeURIComponent(value);
    
    if(typeof daysToLive === "number") {
        /* Sets the max-age attribute so that the cookie expires
        after the specified number of days */
        cookie += "; max-age=" + (daysToLive*24*60*60);
        
        document.cookie = cookie;
    }
}

function getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");
    
    // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return
            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    // Return null if not found
    return null;
}

// function checkCookie() {
//     // Get cookie using our custom function
//     var firstName = getCookie("firstName");
    
//     if(firstName != "") {
//         alert("Welcome again, " + firstName);
//     } else {
//         firstName = prompt("Please enter your first name:");
//         if(firstName != "" && firstName != null) {
//             // Set cookie using our custom function
//             setCookie("firstName", firstName, 30);
//         }
//     }
// }

// This function could be called each time an item is selected from the dropdown
function trackViewedItem(itemId) {
    //console.log('Tracking item:', itemId);
    // Get the existing viewed items from the cookie
    var viewedItemsJson = getCookie('viewedItems');
    var viewedItems = viewedItemsJson ? JSON.parse(viewedItemsJson) : [];

    //console.log('Current viewed items before adding:', viewedItems); 

    // Add the new item to the array if it's not already included
    if (!viewedItems.includes(itemId)) {
        viewedItems.push(itemId);
    }

    //console.log('Viewed items after adding:', viewedItems); 

    // Store the updated array back in the cookie
    var newViewedItemsJson = JSON.stringify(viewedItems);
    setCookie('viewedItems', newViewedItemsJson, 7); // Expires in 7 days
}


// Bind the highlighting when the document is ready
document.addEventListener('DOMContentLoaded', bindTableHighlighting);



$(document).ready(function(){
    $("#submit").click(function(e){
        e.preventDefault(e); 
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var email = $("#email").val();
        var product = $("#product").val();
        var quantity = parseInt($("#quantity").val(), 10);  
        var available = parseInt($("#availableStock").val(), 10);
        var receivedTimestamp = $("#orderTimestamp").val();

        var dataString = 'first_name='+ first_name + '&last_name='+ last_name + '&email='+ email + '&product='+ product + '&quantity='+ quantity +'&orderTimestamp='+ receivedTimestamp;

        if(first_name=='' || last_name=='' || email==''){
            alert("Please Fill All Fields");
            return false;
        } 
        if (product == null || product =='') {
            alert("Must select a puzzle");
            return false;
        }
        if (quantity > available || available ==0) {
            alert("Quantity entered (" + quantity + ") is greater than available (" + available + ") !");
            return false;
        }
        // AJAX Code To Submit Form.
        $.ajax({
            type: "POST",
            url: "Unit6_ajaxsubmit.php",
            data: dataString,
            dataType: 'json', 
            cache: false,
            success: function(result){
                if (result.success) {
                    // Display the order message
                    $('#orderMessage').html(result.message);
                                
                    // Reset the form
                    $('#orderForm')[0].reset();

                    // Clear the customer table
                    // Assuming you have a tbody element for the customer table
                    $('#customerTable').hide();

                } else {
                    alert(result.message);
                }
            }
        });
    });
});
