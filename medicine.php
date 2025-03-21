<?php
include 'connect.php';
$login = 0;
$role = 'U';
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
  $login = 1;
  $role = $_SESSION['role'];
  $username = $_SESSION['username'];
}
?>

<?php
//Check if a user is logged in
$login = false;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
  $login = true;
  $user_id = $_SESSION['user_id'];  // Assuming user ID is stored in session
} else {
  $user_id = null;
}

// Fetch data from the database for medicines
$sql = "SELECT 
    m.*, 
    c.name AS category_name
FROM 
    medicines m
JOIN 
    category c ON m.category_id = c.category_id;
";
$result = $conn->query($sql);

// Check if there are results
$medicines = [];
if ($result->num_rows > 0) {
  // Output data for each row
  while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
  }
} else {
  echo "<p>No medicines found.</p>";
}

// Handle the add-to-wishlist request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['medicine_id']) && $login) {
  $medicine_id = $_POST['medicine_id'];

  // Check if the medicine is already in the user's wishlist
  $check_wishlist_sql = "SELECT * FROM wishlist WHERE user_id = ? AND medicine_id = ?";
  $stmt = $conn->prepare($check_wishlist_sql);
  $stmt->bind_param("ii", $user_id, $medicine_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo 'already_added';  // Medicine already in wishlist
  } else {
    // Insert into the wishlist table
    $insert_sql = "INSERT INTO wishlist (user_id, medicine_id, added_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ii", $user_id, $medicine_id);
    if ($stmt->execute()) {
      echo 'success';  // Successfully added to wishlist
    } else {
      echo 'error';  // Error in adding to wishlist
    }
  }
  $stmt->close();
  return;
}
// Check for status in the URL
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = '';

    // Determine the message based on status
    if ($status === 'success') {
        $message = 'Item added to cart successfully!';
        $alertClass = 'alert-success';
    } elseif ($status === 'error') {
        $message = 'Failed to add item to cart. Please try again.';
        $alertClass = 'alert-danger';
    } elseif ($status === 'invalid') {
        $message = 'Invalid request. Please try again.';
        $alertClass = 'alert-warning';
    }

    // Display the alert
    if (!empty($message)) {
        echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
                $message
              </div>";
    }
}


// Close connection
$conn->close();
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
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700|Roboto:400,700&display=swap" rel="stylesheet">

  <!-- Custom Styles for this Template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- Responsive Style -->
  <link href="css/responsive.css" rel="stylesheet" />

  <!-- Custom Styles for Carousel (Optional) -->
  <style>
    .owl-carousel .item {
      padding: 15px;
    }

    .owl-carousel .card {
      border: 1px solid #ddd;
      border-radius: 5px;
      overflow: hidden;
    }

    .owl-carousel .card-img-top {
      width: 100%;
      height: auto;
    }
  </style>
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

    .card {
      position: relative;
      /* Required for absolute positioning of the icon */
    }

    .wishlist-icon {
      position: absolute;
      top: -25px;
      /* Adjust the distance from the top (slightly reduced) */
      right: 5px;
      /* Adjust the distance from the right (slightly reduced) */
      font-size: 1.0rem;
      /* Slightly smaller icon size */
      color: #f44336;
      /* Red color for the heart icon */
      text-decoration: none;
      /* Remove underline for the link */
      background: #fff;
      /* Keep background color as white for the rectangular area */
      border: 2px solid #f44336;
      /* Add border around the heart icon */
      padding: 5px;
      /* Reduced padding to make the rectangle smaller */
      border-radius: 100%;
      /* Make the background rectangular area rounded */
      display: inline-block;
      /* Keep it inline for proper positioning */
    }

    .wishlist-icon:hover {
      color: #e57373;
      /* Lighter red color on hover */
      transform: scale(1.1);
      /* Slight zoom effect on hover */
      transition: transform 0.3s ease, color 0.3s ease;
      /* Smooth hover effect */
    }

    .buttons-container {
      display: flex;
      gap: 10px;
      /* Adds space between the buttons */
      justify-content: space-between;
      margin-top: 10px;
      /* Adds space above the buttons */
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
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
            <a href="#">
              <img src="images/fb.png" alt="Facebook" class="s-1">
            </a>
            <a href="#">
              <img src="images/twitter.png" alt="Twitter" class="s-2">
            </a>
            <a href="#">
              <img src="images/instagram.png" alt="Instagram" class="s-3">
            </a>
          </div>
        </div>
      </div>
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container pt-3">
          <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="Logo">
            <span>Feeling Better?</span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
              <?php if ($login == 1) { ?>
                <li class="nav-item">
                  <a class="nav-link" href="buy.php"> Wishlist </a>
                </li>
              <?php } ?>
              <li class="nav-item">
                <a class="nav-link" href="news.php">News</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact Us</a>
              </li>
            </ul>
            <form class="form-inline">
              <input type="search" class="form-control" placeholder="Search">
              <button class="btn my-2 my-sm-0 nav_search-btn" type="submit"></button>
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
                  <img src="dashboard/assets/img/person.ico" alt="Profile" width="30" height="30" class="rounded-circle">
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
                  <img src="dashboard/assets/img/person.ico" alt="Profile" width="30" height="30" class="rounded-circle">
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
        </nav>
      </div>
    </header>
    <!-- End Header Section -->
  </div>

  <!-- Discount Section -->
  <div class="layout_padding-top">
    <section class="discount_section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-md-5 offset-md-2">
            <div class="detail-box">
              <h2>
                You get <br>
                any medicine <br>
                on
                <span>10% discount</span>
              </h2>
              <p>Grab the 10% Discount Offer Now</p>
              <div>
                <a href="#" class="btn btn-primary">Buy Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-7 col-md-5">
            <div class="img-box">
              <img src="images/medicines.jpg" alt="Medicines" class="img-fluid">
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- End Discount Section -->

  <!-- <?
  $sql = "SELECT 
    m.*, 
    c.name AS category_name
FROM 
    medicines m
JOIN 
    category c ON m.category_id = c.category_id;
"; // Adjust table name
  $result = $conn->query($sql);

  // Check if there are results
  $medicines = [];
  if ($result->num_rows > 0) {
    // Output data for each row
    while ($row = $result->fetch_assoc()) {
      $medicines[] = $row;
    }
  } else {
    echo "<p>No medicines found.</p>";
  }
  ?> -->

  <!-- Health Section -->
  <section class="health_section layout_padding">
    <div class="health_carousel-container">
      <h2 class="text-uppercase">Medicine & Health</h2>
      <div class="carousel-wrap layout_padding2">
        <div class="owl-carousel owl-theme">
          <?php
          // Loop through the fetched medicines and display them dynamically
          foreach ($medicines as $medicine) {
            ?>
            <div class="item">
              <div class="card">
                <!-- Default image, can make dynamic if available -->
                <div class="card-body">
                  <img src="<?= htmlspecialchars($medicine['image']); ?>" class="card-img-top img-fluid"
                    alt="<?= htmlspecialchars($medicine['name']); ?>">

                  <!-- Wishlist Icon in top-right corner -->
                  <?php if ($login == 1) { ?>
                    <a href="#" class="wishlist-icon" title="Add to Wishlist"
                      data-medicine-id="<?= $medicine['medicine_id']; ?>" data-user-id="<?= $user_id ?>">
                      <i class="fa fa-heart" aria-hidden="true"></i>
                    </a>
                  <?php } ?>

                  <div class="star_container">
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                  </div>

                  <!-- Display Medicine Name and Category Name -->
                  <p class="card-text category">Category: <?= htmlspecialchars($medicine['category_name']); ?></p>
                  <p class="card-text dosage">Dosage: <?= htmlspecialchars($medicine['dosage']); ?></p>
                  <p class="card-text quantity">Quantity: <?= htmlspecialchars($medicine['quantity']); ?></p>
                  <p class="card-text manufacture">Manufacture: <?= htmlspecialchars($medicine['manufacturing_date']); ?>
                  </p>
                  <p class="card-text expiration">Expiry: <?= htmlspecialchars($medicine['expiration_date']); ?></p>
                  <p class="card-text price">Price: <?= htmlspecialchars($medicine['price']); ?></p>

                  <!-- Buy Now button positioned at the bottom -->
                  <div class="buttons-container d-flex justify-content-between">
                    <form action="carthandle.php" method="POST">
                      <input type="hidden" name="medicine_id" value="<?= $medicine['medicine_id']; ?>">
                      <input type="hidden" name="quantity" value="1">
                      <?php if ($login == 1) {?>
                      <button type="submit" class="btn btn-primary btn-sm">Buy Now</button>
                      <?php } ?>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }
          ?>

        </div>
      </div>
    </div>
  </section>



  <!-- End Health Section -->

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

  <!-- Initialize Carousels -->
  <script type="text/javascript">
    $(document).ready(function () {
      $(".owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        navText: ["<", ">"],
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
    });


    document.getElementById('logoutsession').addEventListener('click', function () {
      var message = "unset";
      var encodedMessage = encodeURIComponent(message);
      window.location.href = './logout.php?message=' + encodedMessage;
    });
  </script>

  <script>
    // wishlist.js
    $(document).ready(function () {
      $(".wishlist-icon").click(function (e) {
        e.preventDefault();

        var medicineId = $(this).data("medicine-id");
        var userId = $(this).data("user-id");

        // Check if user is logged in
        if (userId) {
          $.ajax({
            url: '', // Current file, since we're processing on the same page
            method: 'POST',
            data: {
              medicine_id: medicineId
            },
            success: function (response) {
              console.log(response == 'already_added');
              if (response === 'success') {
                // Change the heart icon color to indicate it's in the wishlist
                $(this).css("color", "#e57373");  // Heart color changes
                alert('Added to wishlist!');
              } else if (response === 'already_added') {
                alert('This item is already in your wishlist!');
              } else {
                //alert('Error adding to wishlist.');
                alert(response);
              }
            },
            error: function () {
              alert('Error with AJAX request.');
            }
          });
        } else {
          alert('Please log in to add to wishlist.');
        }
      });
    });


  </script>

  <script src="https://chat.ordemio.com/lib/w.js" assistant-id="1bdc0083-c289-4415-8356-162e8b8e2288" async></script>
  

</body>

</html>