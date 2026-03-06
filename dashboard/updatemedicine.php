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

// Check if the form is submitted to update medicine
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $dosage = $conn->real_escape_string($_POST['dosage']);
    $quantity = intval($_POST['quantity']);
    $manufacturing_date = $conn->real_escape_string($_POST['manufacturing_date']);
    $expiration_date = $conn->real_escape_string($_POST['expiration_date']);
    $price = floatval($_POST['price']);

    // Handle image upload
    $imagePath = $medicine['image']; // Default to current image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/"; // Ensure this directory exists and is writable
        $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name']);

        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            echo "<script>alert('Error uploading image.');</script>";
        }
    }

    // Update the medicine in the database
    $update_sql = "UPDATE medicines 
    SET name='$name', dosage='$dosage', quantity=$quantity, manufacturing_date='$manufacturing_date', expiration_date='$expiration_date', price=$price, image='$imagePath' 
    WHERE medicine_id=$id";


    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Medicine updated successfully');</script>";
        echo "<script>window.location.href = 'viewmedicine.php';</script>";
    } else {
        echo "<script>alert('Error updating medicine: " . $conn->error . "');</script>";
    }
}

// Check if an id is passed to fetch the current medicine details
if (isset($_GET['medicine_id'])) {
    $id = intval($_GET['medicine_id']);
    $sql = "SELECT * FROM medicines WHERE medicine_id = $id";
    $result = $conn->query($sql);

    // Check if the medicine exists
    if ($result->num_rows > 0) {
        $medicine = $result->fetch_assoc();
    } else {
        echo "<script>alert('No medicine found with this ID');</script>";
        echo "<script>window.location.href = 'viewmedicine.php';</script>";
    }
} else {
    echo "<script>alert('No medicine ID provided');</script>";
    echo "<script>window.location.href = 'viewmedicine.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Medicine</title>
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
        .update-medicine-container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 5px;
            max-width: 1700px;
            width: 100%;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
            margin-left: 20px;
            /* Adjust this value as needed */
        }

        /* Page Title */
        .update-medicine-title {
            font-size: 24px;
            font-weight: 600;
            color: #012970;
            margin-bottom: 20px;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: #444444;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 10px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.2rem rgba(65, 84, 241, 0.25);
        }

        /* Button Styles */
        .btn-container {
            display: flex;
            justify-content: space-between;
            /* Space between buttons */
            margin-top: 20px;
            /* Optional: Add some space above the buttons */
        }


        .btn-submit {
            background-color: #4154f1;
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            margin-right: 10px;
            /* Add some space between buttons */
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #6776f4;
        }

        .btn-back {
            background-color: #e2e3e5;
            color: #444444;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #d3d3d4;
        }

        /* Alert Messages */
        .alert {
            margin-top: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Add Medicine Form */
        .add-medicine-form {
            display: flex;
            flex-direction: column;
        }

        /* Notes Section */
        .notes {
            margin-top: 20px;
        }

        .notes-title {
            font-weight: 600;
            color: #012970;
            margin-bottom: 10px;
        }

        .notes-text {
            font-size: 14px;
            color: #444444;
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

        #mainContent {
            margin-left: 250px;
            /* Sidebar width */
            transition: margin-left 0.3s ease;
        }

        #mainContent.collapsed {
            margin-left: 0;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header d-flex justify-content-between align-items-center">
        <div class="flex-grow-1 text-center">
            <h1 class="logo">Update Medicine Details</h1>
        </div>
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
                <a class="nav-link" href="users-profile.html">
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

    <!-- Main Content Wrapper -->
    <div id="mainContent">
        <div class="container update-medicine-container">
            <h1 class="update-medicine-title">Update Medicine</h1>

            <form method="POST" action="updatemedicine.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $medicine['medicine_id']; ?>">

                <label for="medicine-name" class="form-label">Medicine Name</label>
                <input type="text" id="medicine-name" name="name" class="form-control"
                    value="<?php echo $medicine['name']; ?>" required>

                <label for="dosage" class="form-label">Dosage</label>
                <input type="text" id="dosage" name="dosage" class="form-control"
                    value="<?php echo $medicine['dosage']; ?>" required>

                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control"
                    value="<?php echo $medicine['quantity']; ?>" required>

                <label for="manufacturing-date" class="form-label">Manufacturing Date</label>
                <input type="date" id="manufacturing-date" name="manufacturing_date" class="form-control"
                    value="<?php echo $medicine['manufacturing_date']; ?>" required>

                <label for="expiration-date" class="form-label">Expiration Date</label>
                <input type="date" id="expiration-date" name="expiration_date" class="form-control"
                    value="<?php echo $medicine['expiration_date']; ?>" required>

                <label for="price" class="form-label">Price</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01"
                    value="<?php echo $medicine['price']; ?>" required>


                <label for="image" class="form-label">Medicine Image</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">

                <div class="btn-container">
                    <button type="submit" name="update" class="btn btn-submit">Update Medicine</button>
                    <button class="btn btn-back" onclick="window.location.href='viewmedicine.php';">Back</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

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