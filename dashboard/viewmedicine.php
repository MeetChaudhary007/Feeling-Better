<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "feeling_better";

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch medicines from the database
$sql = "SELECT medicine_id, name, dosage, quantity, manufacturing_date, expiration_date, price, created_at FROM medicines";
$result = $conn->query($sql);

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM medicines WHERE medicine_id = $delete_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Medicine deleted successfully');</script>";
        echo "<script>window.location.href = 'viewmedicine.php';</script>";
    } else {
        echo "<script>alert('Error deleting medicine: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Medicines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <!-- Favicons -->
    <link href="../images/logo.png" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Main Container */
        .view-medicine-container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
        }

        /* Page Title */
        .view-medicine-title {
            font-size: 24px;
            font-weight: 600;
            color: #012970;
            margin-bottom: 20px;
        }

        /* Button Styles */
        .btn-update {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            margin-right: 5px;
            transition: background-color 0.3s;
        }

        .btn-update:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            transition: background-color 0.3s;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-back {
            background-color: #28a745;
            /* Green for "Add New Medicine" */
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #218838;
        }

        /* Table Styles */
        .table {
            margin-top: 20px;
        }

        .table th {
            background-color: #f4f6f9;
            color: #012970;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        /* Sidebar Styles */
        .sidebar {
            background: #f8f9fa;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        /* Sidebar Items */
        .nav-item {
            margin: 10px 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 4px;
            color: #444;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: #e2e3e5;
        }

        /* Sidebar Icons */
        .nav-link i {
            margin-right: 10px;
        }

        /* Sidebar Heading */
        .nav-heading {
            margin: 20px 0 10px;
            font-weight: bold;
            color: #012970;
        }

        /* Hamburger Menu */
        .hamburger {
            position: absolute;
            top: 15px;
            /* Adjust this value for vertical alignment */
            left: 20px;
            /* Adjust as needed */
            cursor: pointer;
            z-index: 1000;
            /* Ensure it's above other elements */
        }

        /* Main Content Adjustment */
        #mainContent {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        #mainContent.collapsed {
            margin-left: 0;
        }

        /* Header Styles */
        .header {
            background-color: #f8f9fa;
            padding: 15px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 18px;
        }

        .header .logo {
            font-size: 35px;
            /* Adjust this value for the logo text */
        }

        /* Main Content Adjustment */
        .main-content {
            margin-left: 270px;
            /* Increase this value slightly from the original */
            transition: margin-left 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: 0;
        }
    </style>
</head>

<body>

    <!-- Hamburger Menu Icon -->
    <div class="hamburger" onclick="toggleSidebar()">
        <button class="btn btn-outline-secondary">
            <i class="bi bi-list" style="font-size: 24px;"></i> <!-- Increased font size -->
        </button>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar"><br><br>
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-heading">Pages</li>
            <li class="nav-item">
                <a class="nav-link" href="users-profile.php">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/medion/index.php">
                    <i class="bi bi-question-circle"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addmedicine.php">
                    <i class="bi bi-card-list"></i>
                    <span>Add Medicines</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="viewmedicine.php">
                    <i class="bi bi-envelope"></i>
                    <span>View Medicines</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user_details.php">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>User Details</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link collapsed" href="order_details.php">
                    <i class="bi bi-file-earmark"></i>
                    <span>Order Details</span>
                </a>
            </li><!-- Order Reports Page Nav -->
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <header class="d-flex align-items-center header justify-content-between">
            <h1 class="logo mx-auto">View Medicine Details</h1> <!-- Added mx-auto for centering -->
            <nav>
                <ul class="d-flex list-unstyled mb-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <img src="assets/img/person.ico" alt="Profile" class="rounded-circle" style="width: 30px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="users-profile.php">My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php?message=unset">Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        <br>


        <h1 class="view-medicine-title">List of Medicines</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Medicine Name</th>
                    <th>Dosage</th>
                    <th>Quantity</th>
                    <th>Manufacturing Date</th>
                    <th>Expiration Date</th>
                    <th>Price</th>
                    <th>Added On</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                    <td>" . $row['medicine_id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['dosage'] . "</td>
                    <td>" . $row['quantity'] . "</td>
                    <td>" . $row['manufacturing_date'] . "</td> <!-- New data cell -->
                    <td>" . $row['expiration_date'] . "</td>
                    <td>" . $row['price'] . "</td>
                    <td>" . $row['created_at'] . "</td>
                    <td>
                        <a href='updatemedicine.php?medicine_id=" . $row['medicine_id'] . "' class='btn-update'>Update</a>
                        <a href='viewmedicine.php?delete_id=" . $row['medicine_id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this medicine?\");'>Delete</a>
                    </td>
                  </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No medicines found</td></tr>";
                }
                ?>
            </tbody>

        </table>

        <button class="btn btn-back" onclick="window.location.href='addmedicine.php';">Add New Medicine</button>
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content'); // Fix the ID here

            // Toggle the sidebar's visibility
            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('collapsed');
        }

    </script>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>