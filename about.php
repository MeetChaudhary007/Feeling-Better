<?php
$login = 0;
$role = 'U';
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
  $login = 1;
  $role = $_SESSION['role'];
  $username = $_SESSION['username'];
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
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700|Roboto:400,700&display=swap" rel="stylesheet">

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
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                <?php if($login == 1){ ?>
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
              <?php if($login == 1){ ?>
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


  <!-- about section -->
  <section class="about_section layout_padding">
    <div class="container">
      <div class="custom_heading-container ">
        <h2>
          About Us
        </h2>
      </div>

      <div class="img-box">
        <img src="images/about-medicine.png" alt="">
      </div>
      <div class="detail-box">
        <p>
          "Feeling Better?" provides consumers with on-demand, home delivered access to a wide range of prescription,
          other consumer healthcare products and teleconsultations thereby serving their healthcare needs.
        </p>
        <div class="d-flex justify-content-center">
          <a href="">
            Read More
          </a>
        </div>
      </div>
    </div>
  </section>



  <!-- info section -->
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


  <!-- end info section -->

  <!-- footer section -->
  <section class="container-fluid footer_section">
    <p>
      &copy; 2024 All Rights Reserved.
      <!-- <a href="https://html.design/">Free Html Templates</a> -->
    </p>
  </section>
  <!-- footer section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js">
  </script>
  <script type="text/javascript">
    $(".owl-carousel").owlCarousel({
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