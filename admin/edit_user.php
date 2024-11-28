<?php 
session_start();
require "admin_function.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Fetch all users to display
$users = getAllUsers($pdo); // Ensure this function fetches all users

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "This page must be accessed via a form submission.";
    header("Location: admin.html.php");
    exit();
}

// Sanitize and validate input
$userid = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT);
$username = htmlspecialchars(filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW));
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = htmlspecialchars(filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW));
$role = htmlspecialchars(filter_input(INPUT_POST, 'role', FILTER_UNSAFE_RAW));

if (!$userid || !$username || !$email || !$role) {
    die("Invalid form data.");
}

// Prepare data for update
$data = array_filter([
    'username' => $username,
    'email' => $email,
    'password' => !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null,
    'role' => $role,
]);

// Update user in database
if (editUser($pdo, $userid, $data)) {
    header("Location: admin.html.php");
    exit(); // No unreachable code after this
}

// Handle failure
echo "Failed to edit user.";
