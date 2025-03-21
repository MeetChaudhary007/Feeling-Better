<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feeling_better";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Ensure the POST request contains the product_id
if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID not received']);
    exit();
}

$medicine_id = $_POST['medicine_id'];

// Check if the product is already in the wishlist
$checkQuery = "SELECT * FROM wishlist WHERE user_id = ? AND medicine_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Item already in wishlist']);
    exit();
}

// Insert the product into the wishlist
$insertQuery = "INSERT INTO wishlist (user_id, medicine_id) VALUES (?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Item added to wishlist']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add to wishlist']);
}

$stmt->close();
$conn->close();
?>
