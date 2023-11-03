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
    xhttp.open("GET", "Unit4_get_customer_table.php?q=" + str + "&type=" + type, true);
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
    
    xmlhttp.open("GET", "Unit4_get_quantity.php?productId=" + productId, true);
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
        if (quantity > available) {
            alert("Quantity entered (" + quantity + ") is greater than available (" + available + ") !");
            return false;
        }
        // AJAX Code To Submit Form.
        $.ajax({
            type: "POST",
            url: "Unit4_ajaxsubmit.php",
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
