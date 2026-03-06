<?php
include '../user_session.php';
include '../connect.php'; // Assuming this file establishes the database connection

checkSession(); // Check if the session is valid
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$email = $_SESSION['email'];

if ($role == 'G') {
  header("Location: ../index.php");
  exit;
}

// Query to get the user's profile image
$query = "SELECT profile_image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Set default profile image if no image is found
$profileImage = $user['profile_image'] ? "../" . $user['profile_image'] : 'assets/img/person.ico';

$stmt->close();


// Initialize variables to store user-specific details
$first_name = $last_name = $contact = $address = $gender = '';

if ($role == 'customer') {
  // Fetch customer-specific details
  $customerQuery = "SELECT first_name, last_name, contact, gender, address FROM customers WHERE customer_id = ?";
  if ($stmt = $conn->prepare($customerQuery)) {
    $stmt->bind_param("i", $user_id); // Bind the user ID
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $contact, $gender, $address);
    $stmt->fetch();
    $stmt->close();
  } else {
    echo "Error fetching customer details: " . $conn->error;
  }
} elseif ($role == 'medicine_manager') {
  // Fetch medicine manager-specific details
  $managerQuery = "SELECT first_name, last_name, contact, gender FROM medicine_manager WHERE manager_id = ?";
  if ($stmt = $conn->prepare($managerQuery)) {
    $stmt->bind_param("i", $user_id); // Bind the user ID
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $contact, $gender);
    $stmt->fetch();
    $stmt->close();
  } else {
    echo "Error fetching manager details: " . $conn->error;
  }
}

// // Display the user details
// echo "<h1>Profile Details</h1>";
// echo "<p>Username: $username</p>";
// echo "<p>Role: $role</p>";
// echo "<p>First Name: $first_name</p>";
// echo "<p>Last Name: $last_name</p>";
// echo "<p>Contact: $contact</p>";
// echo "<p>Gender: $gender</p>";

// if ($role == 'customer') {
//   echo "<p>Address: $address</p>";
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Feeling Better? </title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <!-- Favicons -->
  <link href="../images/logo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center bg-dark-subtle">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="../images/logo.png" alt="">
        <span class="d-none d-lg-block">Feeling Better?</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/person.ico" alt="Profile" class="rounded-circle">
            <span class="username d-none d-md-block dropdown-toggle ps-2"> <?= $username ?> </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6 class="username"><?= $username ?></h6>
              <span><?= $role ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="../logout.php?message=unset">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

  
    <ul class="sidebar-nav" id="sidebar-nav">
    <?php if($role == "admin" || $role == "medicine_manager"){ ?>
      <li class="nav-item">
        <a class="nav-link " href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <?php } ?>



      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="/medion/index.php">
          <i class="bi bi-question-circle"></i>
          <span>Home</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="/medion/medicine.php">
          <i class="bi bi-envelope"></i>
          <span>Medicines</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <?php if($role == "admin" || $role == "medicine_manager"){ ?>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#medicine-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Medicine Details</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="medicine-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="addmedicine.php">
              <i class="bi bi-circle"></i><span>Add Medicine</span>
            </a>
          </li>
          <li>
            <a href="viewmedicine.php">
              <i class="bi bi-circle"></i><span>View Medicine</span>
            </a>
          </li>
        </ul>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="user_details.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>User Details</span>
        </a>
      </li><!-- End Login Page Nav -->

      
      <?php }?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="order_details.php">
          <i class="bi bi-file-earmark"></i>
          <span>Order Details</span>
        </a>
      </li><!-- Order Reports Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="<?= $profileImage ?>" alt="Profile" height="120" width="120"
                class="currentProfileImage rounded-circle">
              <h2 class="username"><?= $username ?></h2>
              <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab"
                    data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <!-- <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                </li> -->

                <!-- <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change
                    Password</button>
                </li> -->

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <!-- <h5 class="card-title">About</h5>
                  <p class="small fst-italic">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</p> -->

                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Username</div>
                    <div class="username col-lg-9 col-md-8"><?= $username ?></div>
                  </div>
                  <?php if ($role == 'customer' || $role == 'medicine_manager') { ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Full Name</div>
                      <div class="col-lg-9 col-md-8" id="fullname"><?= $first_name . " " . $last_name ?></div>
                    </div>
                    <?php if ($role == 'customer') { ?>
                      <div class="row">
                        <div class="col-lg-3 col-md-4 label">Address</div>
                        <div class="col-lg-9 col-md-8" id="address"><?= $address ?></div>
                      </div>
                    <?php } ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Phone</div>
                      <div class="col-lg-9 col-md-8" id="contact"><?= $contact ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Gender</div>
                      <div class="col-lg-9 col-md-8" id="gender"><?= $gender ?></div>
                    </div>
                  <?php } ?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8" id="email"><?= $email ?></div>
                  </div>

                  <!--
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Company</div>
                    <div class="col-lg-9 col-md-8">Lueilwitz, Wisoky and Leuschke</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Job</div>
                    <div class="col-lg-9 col-md-8">Web Designer</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Country</div>
                    <div class="col-lg-9 col-md-8">USA</div>
                  </div>
                 -->
                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                      <img src="assets/img/profile-img.jpg" alt="Profile">
                      <div class="pt-2">
                        <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                        <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                      </div>
                    </div>
                  </div> -->
                  <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                      <img class="currentProfileImage" src="<?= $profileImage ?>" alt="Profile">
                      <div class="pt-2">
                        <form id="profileImageForm" enctype="multipart/form-data">
                          <input type="file" name="profile_image" accept="image/*" required>
                          <button type="submit" class="btn btn-primary btn-sm" title="Upload new profile image">
                            <i class="bi bi-upload"></i>
                          </button>
                        </form>
                        <!-- <a href="remove_profile_image.php" class="btn btn-danger btn-sm" title="Remove my profile image">
                          <i class="bi bi-trash"></i>
                        </a> -->
                      </div>
                    </div>
                  </div>

                  <!-- Profile Edit Form -->
                  <form id="userDetailsForm">
                    <div class="row mb-3">
                      <label for="userName" class="col-md-4 col-lg-3 col-form-label">Username</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="userName" type="text" class="username form-control" id="userName"
                          value="<?= $username ?>">
                      </div>
                    </div>

                    <?php if ($role == 'customer' || $role == 'medicine_manager') { ?>
                      <div class="row mb-3">
                        <label for="firstName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="firstName" type="text" class="form-control" id="firstName"
                            value="<?= $first_name ?>">
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label for="lastName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="lastName" type="text" class="form-control" id="lastName" value="<?= $last_name ?>">
                        </div>
                      </div>

                      <?php if ($role == 'customer') { ?>
                        <div class="row mb-3">
                          <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                          <div class="col-md-8 col-lg-9">
                            <input name="address" type="text" class="form-control" id="Address" value="<?= $address ?>">
                          </div>
                        </div>
                      <?php } ?>

                      <div class="row mb-3">
                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="phone" type="text" class="form-control" id="Phone" value="<?= $contact ?>">
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label">Gender</label>
                        <div class="col-md-8 col-lg-9">
                          <div>
                            <input type="radio" name="gender" id="male" value="male" <?= $gender == 'male' ? 'checked' : '' ?>>
                            <label for="male">Male</label>
                          </div>
                          <div>
                            <input type="radio" name="gender" id="female" value="female" <?= $gender == 'female' ? 'checked' : '' ?>>
                            <label for="female">Female</label>
                          </div>
                          <div>
                            <input type="radio" name="gender" id="other" value="other" <?= $gender == 'other' ? 'checked' : '' ?>>
                            <label for="other">Other</label>
                          </div>
                        </div>
                      </div>

                    <?php } ?>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="<?= $email ?>">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form>
                  <script>
                    $(document).ready(function () {
                      // AJAX for Profile Image Upload
                      $('#profileImageForm').on('submit', function (e) {
                        e.preventDefault(); // Prevent the form from submitting normally

                        $.ajax({
                          url: '../manage_user_details.php',
                          type: 'POST',
                          data: new FormData(this),
                          contentType: false,
                          processData: false,
                          success: function (response) {
                            if (response.success) {
                              $('.currentProfileImage').attr('src', "../" + response.imagePath);
                              alert('Profile image updated successfully!');
                            } else {
                              console.log(response.error);
                              alert('Error: ' + response.error);
                            }
                          },
                          error: function (jqXHR, textStatus, errorThrown) {
                            alert('AJAX Error: ' + textStatus);
                          }
                        });
                      });

                      // AJAX for User Details Update
                      $('#userDetailsForm').on('submit', function (e) {
                        e.preventDefault(); // Prevent the form from submitting normally

                        $.ajax({
                          url: '../manage_user_details.php',
                          type: 'POST',
                          data: $(this).serialize(),
                          success: function (response) {
                            if (response.success) {
                              alert('Profile details updated successfully!');
                              // $('#firstNameDisplay').text($('#firstName').val());
                              $('#fullname').text($('#firstName').val() + " " + $('#lastName').val());
                              $('#address').text($('#Address').val());
                              $('#contact').text($('#Phone').val());

                              let genderValue = $('input[name="gender"]:checked').val();
                              $('#gender').text(genderValue);

                              $('#email').text($('#Email').val());
                              $('.username').text($('#userName').val());
                            } else {
                              console.log(response.error);
                              alert('Error: ' + response.error);
                            }
                          },
                          error: function (jqXHR, textStatus, errorThrown) {
                            alert('AJAX Error: ' + textStatus);
                          }
                        });
                      });
                    });
                  </script>

                  <!-- End Profile Edit Form -->
                  <!--
                    <div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="about" class="form-control" id="about" style="height: 100px">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</textarea>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="company" type="text" class="form-control" id="company" value="Lueilwitz, Wisoky and Leuschke">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Job</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="job" type="text" class="form-control" id="Job" value="Web Designer">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="country" type="text" class="form-control" id="Country" value="USA">
                      </div>
                    </div>
                  
                    <div class="row mb-3">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="twitter" type="text" class="form-control" id="Twitter" value="https://twitter.com/#">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="facebook" type="text" class="form-control" id="Facebook" value="https://facebook.com/#">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="instagram" type="text" class="form-control" id="Instagram" value="https://instagram.com/#">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="linkedin" type="text" class="form-control" id="Linkedin" value="https://linkedin.com/#">
                      </div>
                    </div> 
                    -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-settings">

                  <!-- Settings Form -->
                  <!-- <form>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="changesMade" checked>
                          <label class="form-check-label" for="changesMade">
                            Changes made to your account
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="newProducts" checked>
                          <label class="form-check-label" for="newProducts">
                            Information on new products and services
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="proOffers">
                          <label class="form-check-label" for="proOffers">
                            Marketing and promo offers
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                          <label class="form-check-label" for="securityNotify">
                            Security alerts
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form> -->
                  <!-- End settings Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <!-- <form>

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                  </form>End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>