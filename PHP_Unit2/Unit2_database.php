<?php
function getConnection() {
    // Include database credentials
    include 'Unit2_database_credentials.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
      
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Return the connection object
    return $conn;
}

function fetchCustomers($conn) {
    $sql = "SELECT id, last_name, first_name, email FROM Customer";
    return $conn->query($sql);
}

function fetchPuzzles($conn) {
    $sql = "SELECT id, product_name, puzzle_pieces, price, in_stock FROM Product";
    return $conn->query($sql);
}

function fetchOrders($conn) {
    $sql = "SELECT customer_id, product_id, timestamp, quantity, price, tax, donation FROM Orders";
    return $conn->query($sql);
}

function getAllCustomers($conn) {
    $sql = "SELECT * FROM Customer";
    return $conn->query($sql);
}

function getCustomerCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM Customer";
    $result = $conn->query($sql);
    return $result->fetch_assoc()["count"];
}

function findCustomerById($conn, $id) {
    $sql = "SELECT * FROM Customer WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function findCustomerByEmail($conn, $email) {
    $sql = "SELECT * FROM Customer WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function addCustomer($conn, $firstName, $lastName, $email) {
    $sql = "INSERT INTO Customer (first_name, last_name, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstName, $lastName, $email);
    $stmt->execute();
    return $stmt->affected_rows;
}
function getAllOrders($conn) {
    $sql = "SELECT * FROM Orders";
    return $conn->query($sql);
}

function getOrderCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM Orders";
    $result = $conn->query($sql);
    return $result->fetch_assoc()["count"];
}

function getProductNameById($conn, $productId) {
    $sql = "SELECT product_name FROM Product WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['product_name'];
}

function getCustomerNameById($conn, $customerId) {
    $sql = "SELECT CONCAT(first_name, ' ', last_name) as name FROM Customer WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['name'];
}

function getCurrentStock($conn, $productId) {
    $sql = "SELECT in_stock FROM Product WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['in_stock'];
}

function reduceStock($conn, $productId, $quantitySold) {
    $currentStock = getCurrentStock($conn, $productId);
    if($quantitySold > $currentStock) {
        // Maybe return a message or handle this case
        return "Cannot sell more than available in stock!";
    }
    $sql = "UPDATE Product SET in_stock = in_stock - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantitySold, $productId);
    $stmt->execute();
    return $stmt->affected_rows;
}

$conn = getConnection(); 
$allCustomers = getAllCustomers($conn)->fetch_all(MYSQLI_ASSOC);
$customerCount = getCustomerCount($conn);
$allOrders = getAllOrders($conn);
$ordersCount = getOrderCount($conn);
$allPuzzles = fetchPuzzles($conn);

?>


