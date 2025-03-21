<?php
// Start the session
session_start();

// Include database connection
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: registration_login.php");
    exit(); // Stop further execution
}

// Fetch user ID from the session
$user_id = $_SESSION['user_id'];

// Check if the necessary POST parameters are set
if (isset($_POST['medicine_id']) && isset($_POST['quantity'])) {
    $medicine_id = intval($_POST['medicine_id']);
    $quantity = intval($_POST['quantity']);
    $added_date = date("Y-m-d H:i:s"); // Current timestamp

    // Prepare the SQL statement
    $sql = "INSERT INTO cart (user_id, medicine_id, quantity, added_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iiis", $user_id, $medicine_id, $quantity, $added_date);

        if ($stmt->execute()) {
            // Redirect with success status
            header("Location: medicine.php?status=success");
            exit(); // Ensure no further code executes
        } else {
            // Redirect with error status
            header("Location: medicine.php?status=error");
            exit(); // Ensure no further code executes
        }
    } else {
        // Redirect with error status if query preparation fails
        header("Location: medicine.php?status=error");
        exit(); // Ensure no further code executes
    }
} else {
    // Redirect with invalid request status
    header("Location: medicine.php?status=invalid");
    exit(); // Ensure no further code executes
}