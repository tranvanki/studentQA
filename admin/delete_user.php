<?php 
session_start(); // Start the session
require "admin_function.php";
require "delete_user.html.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Check the contents of $_POST
    error_log(print_r($_POST, true)); // Log the POST data for debugging

    if (isset($_POST['action']) && $_POST['action'] === 'delete_user' && isset($_POST['id'])) {
        $userid = (int)$_POST['id'];
        if (deleteUser ($pdo, $userid)) {
            header("Location: admin.html.php"); // Redirect after deletion
            exit;
        } else {
            die("Error deleting user.");
        }
    } else {
        die("Invalid request.");
    }}
// } else {
//     die("Invalid request method.");
// }