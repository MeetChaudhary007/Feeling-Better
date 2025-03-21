<?php
// Include database connection
include 'connect.php'; // Make sure your DB connection file is correct
session_start();

// Initialize variables
$login = 0;
$role = 'U';
$username = '';
$user_id = null; // Change variable to user_id

// Check if user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    $login = 1;
    $role = $_SESSION['role'] ?? 'U';
    $username = $_SESSION['username'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null; // Use user_id instead of customer_id
}

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
// Ensure user_id is set
if (is_null($user_id)) {
    echo "Error: User ID is not set.";
    exit;
}

// Check if the request is a POST request and action is 'place_order'
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'place_order') {
    // Decode the JSON arrays
    $medicine_ids = json_decode($_POST['medicine_id']);
    $category_ids = json_decode($_POST['category_id']);
    $quantities = json_decode($_POST['quantity']);

    // Get user ID and total amount
    $user_id = $_POST['user_id'];
    $total_amount = $_POST['total_amount'];

    // Order date and status
    $order_date = date('Y-m-d H:i:s');
    $status = 'pending';

    // Loop through each medicine and insert the order record
    for ($i = 0; $i < count($medicine_ids); $i++) {
        $medicine_id = $medicine_ids[$i];
        $category_id = $category_ids[$i];
        $quantity = $quantities[$i];

        // Insert into the orders table
        $query = "INSERT INTO orders (user_id, medicine_id, category_id, order_date, quantity, total_amount, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) {
            // Bind the parameters and execute the query
            $stmt->bind_param("iiisids", $user_id, $medicine_id, $category_id, $order_date, $quantity, $total_amount, $status);

            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Error placing order']);
                exit;
            }
            else
            {
                $sql = "DELETE FROM cart WHERE user_id = $user_id";
                if ($conn->query($sql) === TRUE) {
                    echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
                }
            }
        }
    }
    // // If the loop completes successfully
    // echo json_encode(['success' => true]);
}
?>