<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Unit6_common.css">
    <title>The Puzzle and Game Store</title>
</head>
<body>
    
<nav>
    <!-- Common link accessible by anyone -->
    <a href="Unit6_index.php" class="nav-item">Home</a>
    
    <!-- Check if session role is not set or empty, display Store link for non-logged in users -->
    <?php if (!isset($_SESSION['role']) || empty($_SESSION['role'])): ?>
        <a href="Unit6_store.php" class="nav-item">Store</a>
    <?php endif; ?>

    <!-- Links for logged in Customer Service rep (role 1) -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
        <a href="Unit6_order_entry.php" class="nav-item">Order Entry</a>
        <a href="Unit6_admin.php" class="nav-item">Admin</a>
    <?php endif; ?>

    <!-- Links for logged in Admin (role 2) -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
        <a href="Unit6_store.php" class="nav-item">Store</a> <!-- Admins can act as a user -->
        <a href="Unit6_order_entry.php" class="nav-item">Order Entry</a>
        <a href="Unit6_adminProduct.php" class="nav-item">Products</a>
    <?php endif; ?>

    <!-- Logout link for logged-in users -->
    <?php if (isset($_SESSION['role'])): ?>
        <a href='Unit6_logout.php' style="float:right" class="nav-logout">Logout</a>
    <?php endif; ?>
</nav>



<header>
    <h1>The Puzzle and Game Store</h1>
    <h2>Proudly wasting your time since 2020</h2>
    <?php if (isset($_SESSION['firstName'])): ?>
        <div class="welcome-message">
            Welcome, <?= htmlspecialchars($_SESSION['firstName']); ?>
        </div>
<?php endif; ?>

</header>


</body>
</html>
