<?php
include 'connect.php';
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

// Fetch cart items to check if the cart is empty
$query = "SELECT * FROM cart WHERE user_id = $user_id";
$result = $conn->query($query);

// If the cart is empty, redirect to the cart page with an error message
if ($result->num_rows == 0) {
    $_SESSION['cart_error'] = "Your cart is empty. Please add items to your cart before proceeding to checkout.";
    header("Location: medicine.php");
    exit;
}

?>



<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/png" href="images/logo.png" sizes="32x32" />
    <style>
        /* Styling for the logout session container */
        #logoutsession {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 8px 16px;
            /* Smaller padding for a more compact size */
            border-radius: 8px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        #logoutsession:hover {
            transform: translateY(-2px);
            /* Slight lift on hover */
        }

        #logoutsession img {
            width: 24px;
            /* Smaller icon size */
            height: 24px;
            margin-right: 8px;
            /* Less space between the image and text */
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        #logoutsession:hover img {
            transform: scale(1.1);
            /* Slight zoom effect on hover */
        }

        #logoutsession span {
            font-size: 16px;
            /* Slightly smaller text */
            font-weight: 600;
            color: #fff;
            /* White text color */
            transition: color 0.3s ease;
        }

        #logoutsession:hover span {
            color: #007bff;
            /* Change the text color to blue on hover */
        }

        .login_btn-container {
            position: relative;
            display: inline-block;
            /* Ensures the dropdown aligns correctly */
        }

        .dropdown-content {
            display: none;
            /* Hide the dropdown by default */
            position: absolute;
            /* Position it absolutely */
            background-color: #fff;
            /* Background color */
            min-width: 150px;
            /* Set a minimum width */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Add shadow */
            z-index: 1;
            border-radius: 10px;
            /* Ensure it appears above other elements */
        }

        .login_btn-container:hover .dropdown-content {
            display: block;
            /* Show the dropdown on hover */
        }

        .dropdown-content a {
            color: black;
            /* Text color */
            padding: 12px 16px;
            /* Padding */
            text-decoration: none;
            /* Remove underline */
            display: block;
            /* Display as block */
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            border-radius: 10px;
            /* Change background on hover */
        }

        /* Styling for the cart icon */
        .cart-icon-container {
            position: relative;
        }

        .cart-icon {
            width: 30px;
            /* Fixed width */
            height: 30px;
            /* Fixed height */
            border-radius: 50%;
            /* Makes the icon round */
            object-fit: cover;
            /* Ensures the icon fits within the defined size without distortion */
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #f00;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }


        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Checkout Page Container */
        .checkout-container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .checkout-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .checkout-table th,
        .checkout-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        .checkout-table th {
            background-color: #f1f1f1;
            color: #333;
        }

        .checkout-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .checkout-actions {
            text-align: center;
            margin-top: 20px;
        }

        .checkout-actions .btn {
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px;
        }

        .checkout-actions .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            transition: background-color 0.3s;
        }

        .checkout-actions .btn-primary:hover {
            background-color: #0056b3;
        }

        .checkout-actions .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border: none;
            transition: background-color 0.3s;
        }

        .checkout-actions .btn-secondary:hover {
            background-color: #5a6268;
        }


        /* Customer Details Section */
        .customer-details {
            margin-top: 30px;
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .customer-details input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #ffffff;
        }

        .customer-details input[type="email"] {
            font-family: Arial, sans-serif;
        }

        .customer-details form button {
            width: 100%;
            padding: 14px;
            background-color: #28a745;
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .customer-details form button:hover {
            background-color: #218838;
        }

        .customer-details p {
            font-size: 16px;
            color: #333;
        }

        #edit-details {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #ffc107;
            color: #fff;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        #edit-details:hover {
            background-color: #e0a800;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .checkout-container {
                width: 95%;
                padding: 15px;
            }

            .checkout-actions .btn {
                width: 100%;
                font-size: 14px;
            }

            .customer-details input {
                font-size: 14px;
            }

            .customer-details form button {
                font-size: 14px;
            }
        }


        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
            animation: fadeIn 0.3s;
            /* Fade-in animation */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* This will give some width to the modal */
            max-width: 500px;
            /* Max width of the modal */
            animation: slideIn 0.5s ease-out;
            /* Slide-in animation */
            box-sizing: border-box;
            /* Ensure padding is inside the width */
            border-radius: 8px;
            /* Optional: Rounded corners */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Close button (x) */
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 15px;
            top: 10px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* Slide-in animation for modal content */
        @keyframes slideIn {
            from {
                transform: translateY(-50%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Fade-in animation for modal background */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Form input fields styling */
        form input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Button styling */
        .btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #218838;
        }

        .modal-content {
            padding: 20px;
            text-align: center;
        }

        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .payment-option {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .payment-option:hover {
            background-color: #0056b3;
        }

        /* Style for the Thank You message */
        .thank-you-message {
            display: none;
            /* Initially hidden */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Center the message */
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            /* Dark background */
            color: white;
            font-size: 20px;
            border-radius: 8px;
            text-align: center;
            z-index: 1000;
            /* Ensure it's above other content */
            max-width: 90%;
            width: 400px;
        }

        /* Ensure the footer is fixed at the bottom */
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>

    <title>Feeling Better?</title>

    <!-- slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700|Roboto:400,700&display=swap"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
    <div class="hero_area">
        <!-- header section strats -->
        <header class="header_section">
            <div class="container">
                <div class="top_contact-container">
                    <div class="tel_container">
                        <a href="">
                            <img src="images/telephone-symbol-button.png" alt=""> Call : +01 1234567890
                        </a>
                    </div>
                    <div class="social-container">
                        <a href="">
                            <img src="images/fb.png" alt="" class="s-1">
                        </a>
                        <a href="">
                            <img src="images/twitter.png" alt="" class="s-2">
                        </a>
                        <a href="">
                            <img src="images/instagram.png" alt="" class="s-3">
                        </a>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container pt-3">
                    <a class="navbar-brand" href="index.php">
                        <img src="images/logo.png" alt="">
                        <span>
                            Feeling Better?
                        </span>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-flex  flex-column flex-lg-row align-items-center w-100 justify-content-between">
                            <ul class="navbar-nav  ">
                                <li class="nav-item active">
                                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="about.php"> About </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="medicine.php"> Medicine </a>
                                </li>
                                <?php if ($login == 1) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="buy.php"> Wishlist </a>
                                    </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="news.php"> News </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.php">Contact us</a>
                                </li>
                            </ul>
                            <form class="form-inline ">
                                <input type="search" placeholder="Search">
                                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit"></button>
                            </form>

                            <!-- Shopping Cart Icon -->
                            <?php if ($login == 1) { ?>
                                <div class="cart-icon-container ml-3" style="margin-right: 20px;">
                                    <a href="cart.php">
                                        <img src="images/cart-icon.jpg" alt="Cart" class="cart-icon">
                                        <!-- <span class="cart-count">3</span> Dynamic cart count -->
                                    </a>
                                </div>
                            <?php } ?>

                            <?php
                            if ($login == 0) {
                                ?>
                                <div class="login_btn-container ml-0 ml-lg-2 ">
                                    <a href="registration_login.php" class="text-white">
                                        <img src="dashboard/assets/img/person.ico" alt="Profile" width="30" height="30"
                                            class="rounded-circle">
                                        <span>
                                            Login
                                        </span>
                                    </a>
                                </div>

                                <?php
                            }
                            ?>

                            <!-- profile picture part    -->


                            <?php
                            if ($login == 1) {
                                ?>

                                <!-- login_btn-contanier ml-0 ml-lg-5  -->

                                <div class="login_btn-container ml-0 ml-lg-2">
                                    <a href="#" class="text-white">
                                        <img src="dashboard/assets/img/person.ico" alt="Profile" width="30" height="30"
                                            class="rounded-circle">
                                        <span>
                                            <?= $username ?>
                                        </span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="dashboard/users-profile.php">Profile</a>
                                        <a href="#" id="logoutsession">Logout</a>
                                    </div>
                                </div>

                                <?php
                            }
                            ?>
                        </div>
                    </div>

                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>




    <!-- Checkout Page Start -->
    <div class="checkout-container">
        <h1>Checkout</h1>
        <br>
        <!-- Medicine Table Start -->

        <table class="checkout-table">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Category Name</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_amount = 0;

                if ($login) {
                    // Get user_id from session
                    $user_id = $_SESSION['user_id'];

                    // Fetch cart data for the logged-in user, join with the medicines table to get details
                    $query = "SELECT 
            cart.*, 
            medicines.name AS medicine_name, 
            medicines.price, 
            category.name AS category_name, 
            medicines.medicine_id, 
            category.category_id
        FROM 
            cart
        JOIN 
            medicines ON cart.medicine_id = medicines.medicine_id
        JOIN 
            category ON medicines.category_id = category.category_id
        WHERE 
            cart.user_id = ?";

                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('i', $user_id); // Use user_id to fetch cart items
                    $stmt->execute();
                    $cart_result = $stmt->get_result();

                    // Check if cart has items
                    if ($cart_result->num_rows > 0) {
                        // Loop through cart items and display them
                        while ($item = $cart_result->fetch_assoc()) {
                            $medicine_name = $item['medicine_name']; // From the medicines table (name column)
                            $category_name = $item['category_name']; // From the category table (name column)
                            $price = $item['price']; // From the medicines table (price column)
                            $quantity = $item['quantity']; // From the cart table (quantity column)
                            $medicine_id = $item['medicine_id']; // From the medicines table (medicine_id column)
                            $category_id = $item['category_id']; // From the category table (category_id column)
                            $total = $price * $quantity;
                            $total_amount += $total;

                            echo "
                <tr data-medicine-id='$medicine_id' data-category-id='$category_id'>
                    <td>$medicine_name</td>
                    <td>$category_name</td>
                    <td>$price</td>
                    <td>$quantity</td>
                    <td>$total</td>
                </tr>";
                        }
                    } else {
                        // If no cart items are found, display a message
                        echo "<tr><td colspan='4'>Your cart is empty.</td></tr>";
                    }
                }

                // Apply 5% tax on the total amount
                $tax = $total_amount * 0.05; // 5% tax
                $grand_total = $total_amount + $tax; // Grand total after tax
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total-label">Subtotal:</td>
                    <td class="total-value"><?= number_format($total_amount, 2) ?></td> <!-- Subtotal -->
                </tr>
                <tr>
                    <td colspan="3" class="total-label">Tax (5%):</td>
                    <td class="total-value"><?= number_format($tax, 2) ?></td> <!-- Tax -->
                </tr>
                <tr>
                    <td colspan="3" class="total-label">Grand Total:</td>
                    <td class="total-value"><?= number_format($grand_total, 2) ?></td> <!-- Grand total after tax -->
                </tr>
            </tfoot>
        </table>
        <!-- Medicine Table End -->


        <?php
        // Start the session only once
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Assuming $conn is your database connection
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $user_id = $_SESSION['user_id'];  // Fetching the user_id from session
        
            // Fetch customer details from the database using user_id
            $query = "SELECT * FROM customers WHERE customer_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a customer record is found
            if ($result->num_rows > 0) {
                $customer = $result->fetch_assoc(); // Fetch customer data
                // Check if address details exist
                $address_exists = !empty($customer['address']) && !empty($customer['city']) && !empty($customer['state']) && !empty($customer['pincode']);
            } else {
                echo "<p>No customer data found for user ID: $user_id</p>";
            }

            // Fetch email from the users table
            $email_query = "SELECT email FROM users WHERE user_id = ?";
            $email_stmt = $conn->prepare($email_query);
            $email_stmt->bind_param('i', $user_id);
            $email_stmt->execute();
            $email_result = $email_stmt->get_result();

            if ($email_result->num_rows > 0) {
                $user_data = $email_result->fetch_assoc();
                $user_email = $user_data['email'];
            } else {
                echo "<p>No email found for user ID: $user_id</p>";
            }
        }

        // Handle form submission to update customer details
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Fetch the submitted form data
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $contact = $_POST['contact'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $pincode = $_POST['pincode'];

            // Update the customer's details in the database (exclude email from being updated)
            $update_query = "UPDATE customers SET first_name = ?, last_name = ?, contact = ?, address = ?, city = ?, state = ?, pincode = ? WHERE customer_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param('sssssssi', $first_name, $last_name, $contact, $address, $city, $state, $pincode, $user_id);

            if ($stmt->execute()) {
                // After updating, re-fetch the updated customer details
                $query = "SELECT * FROM customers WHERE customer_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    $customer = $result->fetch_assoc(); // Fetch updated customer data
                    echo "<p>Your details have been updated successfully.</p>";
                } else {
                    echo "<p>Error: Could not fetch updated details.</p>";
                }
            } else {
                echo "<p>Error updating details: " . $stmt->error . "</p>";
            }
        }
        ?>

        <!-- Customer Details Form Start -->
        <div id="customer-details-container" class="customer-details">
            <?php
            // Display customer details if logged in
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                if (isset($customer)) {
                    echo "<h5><b>Your saved address and contact details:</b></h5>" . "<br>";
                    echo "<p>Name: " . $customer['first_name'] . ' ' . $customer['last_name'] . "</p>";
                    echo "<p>Contact: " . $customer['contact'] . "</p>";
                    echo "<p>Address: " . $customer['address'] . "</p>";
                    echo "<p>City: " . $customer['city'] . "</p>";
                    echo "<p>State: " . $customer['state'] . "</p>";
                    echo "<p>Pincode: " . $customer['pincode'] . "</p>";
                    echo "<p>Email: " . $user_email . "</p>";  // Display email from the users table
                    echo "<button id='edit-details' class='btn btn-warning'>Edit Details</button>";
                }
            }
            ?>
        </div>
        <!-- Customer Details Form End -->


        <!-- Modal for editing details -->
        <div id="edit-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Please fill your details:</p>
                <form id="checkout-form" method="POST">
                    <input type="text" name="first_name" placeholder="First Name"
                        value="<?= isset($customer['first_name']) ? $customer['first_name'] : '' ?>" required>
                    <input type="text" name="last_name" placeholder="Last Name"
                        value="<?= isset($customer['last_name']) ? $customer['last_name'] : '' ?>" required>
                    <input type="text" name="contact" placeholder="Contact Number"
                        value="<?= isset($customer['contact']) ? $customer['contact'] : '' ?>" required>
                    <input type="text" name="address" placeholder="Address"
                        value="<?= isset($customer['address']) ? $customer['address'] : '' ?>" required>
                    <input type="text" name="city" placeholder="City"
                        value="<?= isset($customer['city']) ? $customer['city'] : '' ?>" required>
                    <input type="text" name="state" placeholder="State"
                        value="<?= isset($customer['state']) ? $customer['state'] : '' ?>" required>
                    <input type="text" name="pincode" placeholder="Pincode"
                        value="<?= isset($customer['pincode']) ? $customer['pincode'] : '' ?>" required>
                    <input type="email" name="email" placeholder="Email"
                        value="<?= isset($user_email) ? $user_email : '' ?>" required readonly> <!-- Display email -->
                    <button type="submit" class="btn btn-primary">Save Details</button>
                </form>
            </div>
        </div>

        <?php
        // Check if the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            // Get the parameters from the POST request
            $action = $_POST['action'];

            // If the action is to place an order
            if ($action == 'place_order') {
                $user_id = $_POST['user_id'];
                $medicine_id = $_POST['medicine_id'];
                $category_id = $_POST['category_id'];
                $total_amount = $_POST['total_amount']; // Total amount for the order
        
                // Set order status to 'pending' and current date and time for order_date
                $order_date = date('Y-m-d H:i:s');
                $quantity = $_POST['quantity'];
                $status = 'pending'; // Order status is 'pending' for COD
        
                // Prepare SQL query to insert the order
                $query = "INSERT INTO orders (user_id, medicine_id, category_id, order_date, quantity, total_amount, status) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($query)) {
                    // Bind parameters
                    $stmt->bind_param("iiisids", $user_id, $medicine_id, $category_id, $order_date, $quantity, $total_amount, $status);

                    if ($stmt->execute()) {
                        echo json_encode(['success' => true]);
                    } else {
                        error_log($stmt->error); // Log error for debugging
                        echo json_encode(['success' => false, 'message' => 'Error placing order']);
                    }
                    $stmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error preparing SQL query']);
                }
                $conn->close();

            }
        }
        ?>
        <!-- Payment Modal -->
        <div id="payment-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Select Payment Method</h2>
                <div class="payment-options">
                    <button class="btn btn-primary payment-option" id="cod">Cash on Delivery</button>
                    <button class="btn btn-primary payment-option" id="razorpay">Pay with Razorpay</button>
                </div>
            </div>
        </div>

        <!-- Thank You Message -->
        <div id="thank-you-message" class="thank-you-message">
            <p>Thank you for your order! Your order is placed successfully.</p>
        </div>




        <!-- Checkout Actions -->
        <div class="checkout-actions">
            <button id="proceed-payment" class="btn btn-primary">Proceed to Payment</button>
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
        </div>

    </div>
    <!-- Checkout Page End -->


    <!-- JavaScript for modals -->
    <script>
        // Modal for editing details
        var editModal = document.getElementById("edit-modal");
        var editBtn = document.getElementById("edit-details");
        var closeBtn = editModal.querySelector(".close");

        editBtn.onclick = function () {
            editModal.style.display = "block";
        }

        closeBtn.onclick = function () {
            editModal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target === editModal) {
                editModal.style.display = "none";
            }
        }

        // Modal for payment
        var paymentModal = document.getElementById("payment-modal");
        var proceedPaymentBtn = document.getElementById("proceed-payment");
        var paymentCloseBtn = paymentModal.querySelector(".close");

        var codButton = document.getElementById("cod");
        var razorpayButton = document.getElementById("razorpay");

        proceedPaymentBtn.onclick = function () {
            paymentModal.style.display = "block";
        }

        paymentCloseBtn.onclick = function () {
            paymentModal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target === paymentModal) {
                paymentModal.style.display = "none";
            }
        }

        // Handle payment method selections
        codButton.onclick = function () {
            // Code for handling "Cash on Delivery"
            alert("Cash on Delivery selected. Proceed to delivery.");
            paymentModal.style.display = "none";  // Close the modal
        }

        razorpayButton.onclick = function () {
            // Code for handling Razorpay (you can integrate Razorpay API here)
            alert("Proceeding with Razorpay payment.");
            paymentModal.style.display = "none";  // Close the modal
        }

        // When Cash on Delivery is selected
        document.getElementById('cod').addEventListener('click', function () {
            var totalAmount = <?= $grand_total ?>;
            var userId = <?= $user_id ?>;
            var medicineIds = []; // To store medicine ids
            var categoryIds = []; // To store category ids
            var quantities = []; // To store quantities
            var totalAmountForOrder = totalAmount;

            // Collect data from the cart items in the table
            var cartRows = document.querySelectorAll('.checkout-table tbody tr');
            cartRows.forEach(function (row) {
                var medicineId = row.dataset.medicineId; // Fetch medicine_id from data attribute
                var categoryId = row.dataset.categoryId; // Fetch category_id from data attribute
                var quantity = row.cells[3].textContent; // Get quantity from the row (4th column)

                // Add the values to the respective arrays
                medicineIds.push(medicineId);
                categoryIds.push(categoryId);
                quantities.push(quantity);
            });

            // Prepare data to send
            var data = {
                action: 'place_order',
                user_id: userId,
                total_amount: totalAmountForOrder,
                medicine_id: JSON.stringify(medicineIds), // Send as JSON array
                category_id: JSON.stringify(categoryIds), // Send as JSON array
                quantity: JSON.stringify(quantities) // Send as JSON array
            };

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'place_order.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Sending the data to the server
            var params = new URLSearchParams(data).toString();
            xhr.send(params);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var thankYouMessage = document.getElementById('thank-you-message');
                        thankYouMessage.style.display = 'block';
                        thankYouMessage.classList.add('fade-in');

                        var cartTable = document.querySelector('.checkout-table tbody');
                        cartTable.innerHTML = ''; // Clear the cart table

                        var customerDetailsContainer = document.getElementById('customer-details-container');
                        if (customerDetailsContainer) {
                            customerDetailsContainer.style.display = 'none';
                        }

                        setTimeout(function () {
                            window.location.href = 'index.php'; // Redirect to homepage after 3 seconds
                        }, 3000);
                    } else {
                        alert(response.message);
                    }
                }
            };
        });


    </script>



    <!-- Info Section -->
    <section class="info_section layout_padding2">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="info_contact">
                        <h4>Contact</h4>
                        <div class="box">
                            <div class="img-box">
                                <img src="images/telephone-symbol-button.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h6>+01 123567894</h6>
                            </div>
                        </div>
                        <div class="box">
                            <div class="img-box">
                                <img src="images/email.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h6>feelingbetter@gmail.com</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info_menu">
                        <h4>Menu</h4>
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.php">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="medicine.php">Medicine</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="buy.php">Wishlist</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info_news">
                        <h4>Newsletter</h4>
                        <form action="">
                            <input type="text" placeholder="Enter Your email">
                            <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                <button>Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <section class="container-fluid footer_section">
        <p>&copy; 2024 All Rights Reserved.</p>
    </section>
    <!-- Footer Section End -->

    <!-- JavaScript for Modal -->
    <script>
        // Get the modal
        var modal = document.getElementById("edit-modal");

        // Get the button that opens the modal
        var btn = document.getElementById("edit-details");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js">
    </script>
    <script type="text/javascript">
        $(".owl-2").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            navText: [],
            autoplay: true,

            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 4
                }
            }
        });
        document.getElementById('logoutsession').addEventListener('click', function () {
            var message = "unset";
            var encodedMessage = encodeURIComponent(message);
            window.location.href = './logout.php?message=' + encodedMessage;
        });
    </script>


    <script src="https://chat.ordemio.com/lib/w.js" assistant-id="1bdc0083-c289-4415-8356-162e8b8e2288" async></script>

</body>

</html>