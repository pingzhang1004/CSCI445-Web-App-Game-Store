<?php
include_once 'Unit6_get_puzzle_table.php';

function createItem($conn, $data) {
    // Validate and sanitize input data
    $productName = $data['puzzle_name'];
    $imageName = $data['puzzle_image'];
    $price = $data['price'];
    $puzzlePieces = $data['pieces'];
    $inStock = $data['quantity'];
    $inactive = isset($data['makeInactive']) ? 1 : 0;
    addProduct($conn, $productName, $imageName, $price, $puzzlePieces, $inStock, $inactive);
    $allPuzzles = fetchPuzzles($conn);
    echo createPuzzleTable($conn,$allPuzzles);
}

function updateItem($conn, $data) {
     // Validate and sanitize input data
     $productID = $data['puzzle_id'];
     $productName = $data['puzzle_name'];
     $imageName = $data['puzzle_image'];
     $price = $data['price'];
     $puzzlePieces = $data['pieces'];
     $inStock = $data['quantity'];
     $inactive = isset($data['makeInactive']) ? 1 : 0;
     updateProduct($conn, $productID, $productName, $imageName, $price, $puzzlePieces, $inStock, $inactive);
     $allPuzzles = fetchPuzzles($conn);
     echo createPuzzleTable($conn,$allPuzzles);
}

function deleteItem($conn, $productID) {
    // First, let's check for orders
    // No orders, try to delete the product
    deleteProduct($conn, $productID);
    $allPuzzles = fetchPuzzles($conn);
    echo createPuzzleTable($conn,$allPuzzles);
}


function checkForOrders($conn, $productID) {
    // Call the function that checks if there are any orders for the product
    // Assuming checkOrders function returns true if orders exist, false otherwise
    echo checkOrders($conn, $productID);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'create':
            createItem($conn, $_POST);
            break;
        case 'update':
            updateItem($conn, $_POST);
            break;
        case 'checkForOrders':  // Add this case to handle order checks
            $productID = $_POST['puzzle_id'] ?? 0;
            checkForOrders($conn, $productID);
            break;
        case 'delete':
            $productID = $_POST['puzzle_id'] ?? 0;
            deleteItem($conn, $productID);
            break;
        default:
            break;
    }
}

?>
