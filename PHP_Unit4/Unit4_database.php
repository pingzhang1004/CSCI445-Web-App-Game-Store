<?php
function getConnection() {
    // Include database credentials
    include 'Unit4_database_credentials.php';

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
    $sql = "SELECT id, product_name, image_name, puzzle_pieces, price, in_stock FROM Product";
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

function addOrder($conn, $customerId, $productId, $quantity, $price, $tax, $donationAmount, $receivedTimestamp) {
    $stmt = $conn->prepare("INSERT INTO ORDERS (product_id, customer_id, quantity, price, tax, donation, time_stamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiidddi", $productId, $customerId, $quantity, $price, $tax, $donationAmount, $receivedTimestamp);
    $stmt->execute();
    $stmt->close();
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

function getProductPirceById($conn, $productId) {
    $sql = "SELECT price FROM Product WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['price'];
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

function reduceStock($conn, $productId, $quantityToReduce) {
    $currentStock = getCurrentStock($conn, $productId);
    // Calculate the new stock value after reduction
    $newStock = $currentStock - $quantityToReduce;
    // Ensure stock doesn't go below 0
    if ($newStock < 0) {
        $newStock = 0;
    }

    $sql = "UPDATE Product SET in_stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newStock, $productId);
    $stmt->execute();
    return $stmt->affected_rows;
}

function orderExists($conn, $customerId, $productId, $time_stamp) {
    $query = "SELECT 1 FROM orders WHERE customer_Id = ? AND product_Id = ? AND time_stamp = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $customerId, $productId, $time_stamp);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}


function getCustomersByFirstName($conn, $firstName) {
    $stmt = $conn->prepare("SELECT first_name, last_name, email FROM Customer WHERE first_name LIKE ? LIMIT 10");
    $param = $firstName . "%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    return $stmt->get_result();
}

function getCustomersByLastName($conn, $lastName) {
    $stmt = $conn->prepare("SELECT first_name, last_name, email FROM Customer WHERE last_name LIKE ? LIMIT 10");
    $param = $lastName . "%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    return $stmt->get_result();
}

$conn = getConnection(); 
$allCustomers = getAllCustomers($conn)->fetch_all(MYSQLI_ASSOC);
$customerCount = getCustomerCount($conn);
$allOrders = getAllOrders($conn);
$ordersCount = getOrderCount($conn);
$allPuzzles = fetchPuzzles($conn);

?>


