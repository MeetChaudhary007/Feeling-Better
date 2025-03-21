<?php
// Start the session to access the user data
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feeling_better";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX requests for updating quantity
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $cart_id = $_GET['cart_id'];
    $quantity = $_GET['quantity'];

    $query = "UPDATE cart SET quantity = $quantity WHERE cart_id = $cart_id";
    $conn->query($query);
    echo "Quantity updated";
    exit;
}

// Handle AJAX requests for removing an item
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $cart_id = $_GET['cart_id'];

    $query = "DELETE FROM cart WHERE cart_id = $cart_id";
    $conn->query($query);
    echo "Item removed";
    exit;
}

// Ensure that the user is logged in, otherwise handle the error
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in.");
}

// Fetch the user ID dynamically from the session
$user_id = $_SESSION['user_id'];

// Fetch cart items using a subquery
$query = "
        SELECT 
    c.cart_id, 
    c.medicine_id, 
    m.name AS medicine_name, 
    m.image, 
    m.price, 
    COALESCE(c.quantity, 1) AS quantity,
    m.category_id
FROM 
    cart c 
INNER JOIN 
    medicines m ON c.medicine_id = m.medicine_id
WHERE 
    c.user_id = $user_id";

$result = $conn->query($query);

// Initialize totals
$subtotal = 0;
$tax_rate = 0.05;
?>

<!DOCTYPE html>
<html lang="en">

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
    <title>Feeling Better?</title>

    <!-- Owl Carousel Stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.theme.default.min.css" />

    <!-- Font Awesome Style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- Fonts Style -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700|Roboto:400,700&display=swap"
        rel="stylesheet">

    <!-- Custom Styles for this Template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- Responsive Style -->
    <link href="css/responsive.css" rel="stylesheet" />

    <style>
        /* Styling for the cart icon */
        .cart-icon-container {
            position: relative;
        }

        .cart-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
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

        /* Custom styling for cart items */
        .cart-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .cart-item-name {
            flex: 2;
            font-weight: bold;
            color: #333;
        }

        .cart-item-price {
            flex: 1;
            text-align: right;
            color: #333;
        }

        .cart-item-quantity input {
            width: 60px;
            text-align: center;
        }

        .cart-item-remove a {
            color: #fff;
            background-color: #ff4c4c;
            border-radius: 5px;
            padding: 5px 10px;
            text-decoration: none;
        }

        .cart-item-remove a:hover {
            background-color: #ff1f1f;
        }

        /* Cart Summary */
        .cart-summary {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .cart-summary .d-flex {
            margin-bottom: 10px;
        }

        .cart-summary .d-flex span:first-child {
            color: #777;
            font-weight: bold;
        }

        .cart-summary .d-flex span:last-child {
            color: #333;
            font-weight: bold;
        }

        .cart-summary .cart-checkout-btn a {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        .cart-summary .cart-checkout-btn a:hover {
            background-color: #218838;
        }
    </style>
</head>

<body class="sub_page">
    <div class="hero_area">
        <!-- Header Section Starts -->
        <header class="header_section">
            <div class="container">
                <div class="top_contact-container">
                    <div class="tel_container">
                        <a href="tel:+011234567890">
                            <img src="images/telephone-symbol-button.png" alt="Call Icon"> Call : +01 1234567890
                        </a>
                    </div>
                    <div class="social-container">
                        <a href="#"><img src="images/fb.png" alt="Facebook" class="s-1"></a>
                        <a href="#"><img src="images/twitter.png" alt="Twitter" class="s-2"></a>
                        <a href="#"><img src="images/instagram.png" alt="Instagram" class="s-3"></a>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container pt-3">
                    <a class="navbar-brand" href="index.php">
                        <img src="images/logo.png" alt="Logo"><span>Feeling Better?</span>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="medicine.php">Medicine</a></li>
                            <li class="nav-item"><a class="nav-link" href="buy.php">Wishlist</a></li>
                            <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                            <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                        </ul>
                        <form class="form-inline">
                            <input type="search" class="form-control" placeholder="Search">
                            <button class="btn my-2 my-sm-0 nav_search-btn" type="submit"></button>
                        </form>


                        <!-- Shopping Cart Icon -->
                        <div class="cart-icon-container ml-3" style="margin-right: 20px;">
                            <a href="cart.php">
                                <img src="images/cart-icon.jpg" alt="Cart" class="cart-icon">
                                <span class="cart-count">
                                    <?php
                                    if (session_status() === PHP_SESSION_NONE) {
                                        session_start();
                                    }
                                    include 'connect.php';

                                    // Ensure user is logged in
                                    $user_id = $_SESSION['user_id'] ?? null;

                                    // Fetch the total number of items in the user's cart
                                    $cartCount = 0;
                                    if ($user_id) {
                                        $cartCountQuery = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = $user_id";
                                        $cartCountResult = $conn->query($cartCountQuery);
                                        $cartCount = $cartCountResult->fetch_assoc()['total_items'] ?? 0;
                                    }
                                    echo $cartCount;
                                    ?>
                                </span>
                            </a>
                        </div>



                        <?php
                        // Handle AJAX request for cart count dynamically
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $input = json_decode(file_get_contents('php://input'), true);

                            if ($input['action'] === 'getCartCount') {
                                $cartCount = 0;
                                if ($user_id) {
                                    $cartCountQuery = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = $user_id";
                                    $cartCountResult = $conn->query($cartCountQuery);
                                    $cartCount = $cartCountResult->fetch_assoc()['total_items'] ?? 0;
                                }
                                echo json_encode(['cartCount' => $cartCount]);
                                exit;
                            }
                        }
                        ?>

                    </div>
                </nav>
            </div>
        </header>
        <!-- End Header Section -->
    </div>



    <!--  cart Section -->






    <!-- Cart Page Section -->
    <section class="container cart_section py-5">
        <div class="row">
            <!-- Cart Items Column -->
            <div class="col-md-8">
                <h2>Your Cart</h2>

                <!-- Dynamic Cart items -->
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $item_total = $row['quantity'] * $row['price'];
                        $subtotal = $item_total;
                        ?>
                        <div class="cart-item card mb-4">
                            <div class="card-body d-flex justify-content-between">
                                <div class="cart-item-name d-flex align-items-center">
                                    <img src="<?php echo $row['image'] ? $row['image'] : 'default.jpg'; ?>" alt="Medicine"
                                        class="cart-item-img mr-3" style="width: 100px; height: auto;" />
                                    <span><?php echo $row['medicine_name']; ?></span>
                                </div>
                                <div class="cart-item-quantity d-flex align-items-center">
                                    <input type="number" class="form-control cart-item-quantity-input"
                                        value="<?php echo $row['quantity']; ?>" min="1" max="5"
                                        onchange="updateQuantity(<?php echo $row['cart_id']; ?>, this.value)" />
                                </div>
                                <div class="cart-item-price">
                                    <span id>₹<?php echo number_format($row['price'], 2); ?></span>
                                </div>
                                <div class="cart-item-remove">
                                    <button class="btn btn-danger btn-sm cart-item-remove-btn"
                                        onclick="removeItem(<?php echo $row['cart_id']; ?>)">Remove</button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>

            <!-- Cart Summary Column -->
            <div class="col-md-4">
                <div class="cart-summary card p-3">
                    <h3>Cart Summary</h3>
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span id="subtotal">₹</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax (5%):</span>
                        <span id="tax">₹<?php echo number_format($subtotal * $tax_rate, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <span id="total">₹<?php echo number_format($subtotal + ($subtotal * $tax_rate), 2); ?></span>
                    </div>
                    <div class="cart-checkout-btn mt-4">
                        <a href="checkout.php" class="btn btn-primary btn-block" id="proceedToCheckout">Proceed to
                            Checkout</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        function updateQuantity(cartId, quantity) {
            console.log("Testing");

            fetch(`cart.php?action=update&cart_id=${cartId}&quantity=${quantity}`)
                .then(response => response.text())
                .then(data => {
                    let subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('', ''));
                    let tax = parseFloat(document.getElementById('tax').textContent.replace('', ''));
                    let total = parseFloat(document.getElementById('total').textContent.replace('', ''));
                }
                );
        }

        function removeItem(cartId) {

            fetch(`cart.php?action=remove&cart_id=${cartId}`)
                .then(response => response.text())
                .then(data => location.reload());
        }
    </script>

    <!-- end of cart section -->


    <!-- Info Section -->
    <section class="info_section layout_padding2">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="info_contact">
                        <h4>
                            Contact
                        </h4>
                        <div class="box">
                            <div class="img-box">
                                <img src="images/telephone-symbol-button.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h6>
                                    +01 123567894
                                </h6>
                            </div>
                        </div>
                        <div class="box">
                            <div class="img-box">
                                <img src="images/email.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h6>
                                    feelingbetter@gmail.com
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info_menu">
                        <h4>
                            Menu
                        </h4>
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
                            <li class="nav-item">
                                <a class="nav-link" href="buy.php"> Wishlist </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info_news">
                        <h4>
                            newsletter
                        </h4>
                        <form action="">
                            <input type="text" placeholder="Enter Your email">
                            <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                <button>
                                    Subscribe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Info Section -->




    <!-- Footer Section -->
    <section class="container-fluid footer_section">
        <div class="container">
            <p>&copy; 2024 All Rights Reserved.</p>
        </div>
    </section>
    <!-- End Footer Section -->


    <!-- JS Files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
    <script src="js/bootstrap.js"></script>

    <script>
        $(document).ready(function () {
            $(".cart-item-remove-btn").click(function (e) {
                e.preventDefault();
                $(this).closest(".cart-item").remove();
                updateCartSummary();
            });

            $(".cart-item-quantity-input").change(function () {
                updateCartSummary();
            });

            let subtotal = 0;

            document.getElementById('proceedToCheckout').addEventListener('click', function (event) {
                // Check if the cart is empty (if subtotal is 0 or no items exist)
             // Fetch the PHP subtotal value into JavaScript

                if (subtotal <= 0) {
                    // Prevent the default action (redirecting to checkout)
                    event.preventDefault();

                    // Display an alert message to the user
                    alert('Your cart is empty. Please add items to the cart before proceeding to checkout.');
                }
            });

            function updateCartSummary() {

                subtotal = 0;
                console.log(subtotal);
                $(".cart-item").each(function () {
                    let price = parseFloat($(this).find(".cart-item-price span").text().replace('₹', '').replace(',', ''));
                    let quantity = parseInt($(this).find(".cart-item-quantity-input").val());
                    subtotal += price * quantity;
                });
                console.log(subtotal);

                let tax = subtotal * 0.05; // Assuming a 5% tax rate
                let total = subtotal + tax;

                // Update the cart summary in INR
                $("#subtotal").text('₹' + subtotal.toFixed(2));
                $("#tax").text('₹' + tax.toFixed(2));
                $("#total").text('₹' + total.toFixed(2));
            }

            updateCartSummary();
        });
    </script>


</body>

</html>