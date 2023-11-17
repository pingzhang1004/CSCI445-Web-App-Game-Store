<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include database functions
include 'Unit6_database.php';

// Check if email and password have been submitted
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    // Retrieve user from the database
    $user = fetchUserByEmailAndPassword($conn, $_POST['email'], $_POST['password']);

    // Check if user exists
    if ($user) {
        // Set session variables
        $_SESSION['role'] = $user['role'];
        $_SESSION['firstName'] = $user['first_name'];
        $_SESSION['lastName'] = $user['last_name'];

        // Redirect to the appropriate page based on role
        if ($_SESSION['role'] == 1) {
            header("Location: Unit6_order_entry.php");
        } elseif ($_SESSION['role'] == 2) {
            header("Location: Unit6_adminProduct.php");
        }
    } else {
        // User not found, redirect with error
        header("Location: Unit6_index.php?err=Invalid User");
    }
} else {
    // Email or password empty, redirect with error
    header("Location: Unit6_index.php?err=Invalid User");
}