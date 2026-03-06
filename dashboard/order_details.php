<?php
include '../connect.php';
include '../user_session.php';
checkSession();
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        /* Apply border and padding to the table */
        table {
            width: 120%;
            /* Increased width */
            border-collapse: collapse;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }

        /* Style for table headers */
        th {
            background-color: #f2f2f2;
            color: #333;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        /* Style for table data */
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        /* Adjust column spacing */
        th,
        td {
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Add some margin to the left and right of the table */
        table {
            margin-left: -200px;
            margin-right: auto;
        }

        /* Optional: Style table rows with alternating colors */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Add hover effect */
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
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
                        <span class="d-none d-md-block dropdown-toggle ps-2"> <?= $username ?> </span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= $username ?></h6>
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

            <li class="nav-item">
                <a class="nav-link " href="dashboard.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->



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

            <?php if ($role == "admin" || $role == "medicine_manager") { ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#medicine-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-menu-button-wide"></i><span>Medicine Details</span><i
                            class="bi bi-chevron-down ms-auto"></i>
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

                
            <?php } ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="order_details.php">
                    <i class="bi bi-file-earmark"></i>
                    <span>Order Details</span>
                </a>
            </li><!-- Order Reports Page Nav -->

        </ul>

    </aside><!-- End Sidebar-->

    <?php
    // Start the session to access session variables
    session_start();

    // Assuming user_id is stored in the session upon login
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];  // Get the user_id from session
    } else {
        // Handle the case where user_id is not set, e.g., redirect to login page
        header("Location: login.php");
        exit();
    }
    ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Order Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <main id="main" class="main">
            <table>
                <thead>
                    <tr>
                        <th class="cell">Order ID</th>
                        <th class="cell">Medicine Name</th>
                        <th class="cell">Category Name</th>
                        <th class="cell">Order Date</th>
                        <th class="cell">Quantity</th>
                        <th class="cell">Total Amount</th>
                        <th class="cell">Status</th>
                    </tr>
                </thead>
                <tbody class="datashow">
                    <?php
                    // SQL query to fetch order details for the current user
                    $sql = "
                    SELECT 
                        orders.order_id, 
                        medicines.name AS medicine_name, 
                        category.name AS category_name, 
                        orders.order_date, 
                        orders.quantity, 
                        orders.total_amount, 
                        orders.status
                    FROM orders
                    JOIN medicines ON orders.medicine_id = medicines.medicine_id
                    JOIN category ON medicines.category_id = category.category_id
                    WHERE orders.user_id = ?
                ";

                    // Prepare the SQL statement
                    if ($stmt = $conn->prepare($sql)) {
                        // Bind the user_id to the prepared statement
                        $stmt->bind_param("i", $user_id); // "i" is for integer type
                        // Execute the prepared statement
                        $stmt->execute();

                        // Get the result
                        $result = $stmt->get_result();

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            // Output data for each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>" . $row["order_id"] . "</td>
                                <td>" . $row["medicine_name"] . "</td>
                                <td>" . $row["category_name"] . "</td>
                                <td>" . $row["order_date"] . "</td>
                                <td>" . $row["quantity"] . "</td>
                                <td>" . $row["total_amount"] . "</td>
                                <td>" . $row["status"] . "</td>
                            </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'><center>No data found</center></td></tr>";
                        }

                        // Close the prepared statement
                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='7'><center>Error preparing statement</center></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>

    </main>
    <!-- End #main -->



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