<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feeling_better";
$conn = new
    mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $medicine_name = $conn->real_escape_string($_POST['name']);
    $dosage = $conn->real_escape_string($_POST['dosage']);
    $quantity = intval($_POST['quantity']);
    $category_id = intval($_POST['category_id']);
    $manufacturing_date = $conn->real_escape_string($_POST['manufacturingDate']);
    $expiration_date = $conn->real_escape_string($_POST['expirationDate']);
    $price = floatval($_POST['price']);

    // Validate dates (convert to DateTime objects for accurate comparison)
    $current_date = new DateTime();
    $manuf_date = new DateTime($manufacturing_date);
    $exp_date = new DateTime($expiration_date);

    if ($manuf_date > $current_date) {
        die(json_encode(['error' => 'Manufacturing date cannot be in the future.']));
    }
    if ($exp_date < $manuf_date) {
        die(json_encode(['error' => 'Expiration date cannot be before manufacturing date.']));
    }

    // Handle image upload (same as original code)
    if (isset($_FILES['medicine_image']) && $_FILES['medicine_image']['error'] === 0) {
        $image_name = $_FILES['medicine_image']['name'];
        $image_tmp_name = $_FILES['medicine_image']['tmp_name'];
        $image_size = $_FILES['medicine_image']['size'];
        $image_error = $_FILES['medicine_image']['error'];
        $image_type = $_FILES['medicine_image']['type'];

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($image_type, $allowed_types)) {
            die("Invalid image type. Only JPG, PNG, and GIF are allowed.");
        }

        $upload_dir = 'images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_new_name = uniqid('', true) . "." . pathinfo($image_name, PATHINFO_EXTENSION);
        $image_destination = $upload_dir . $image_new_name;

        if (move_uploaded_file($image_tmp_name, $image_destination)) {
            $sql = "INSERT INTO medicines (category_id, name, dosage, quantity, manufacturing_date, expiration_date, price,
        image)
        VALUES ($category_id, '$medicine_name', '$dosage', $quantity, '$manufacturing_date', '$expiration_date',
        $price, '$image_destination')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['message' => 'Medicine added successfully']);
            } else {
                echo json_encode(['error' => 'Error: ' . $conn->error]);
            }
        } else {
            echo json_encode(['error' => 'Failed to upload image.']);
        }
    } else {
        echo json_encode(['error' => 'Image file is required.']);
    }

    $conn->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
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
        .add-medicine-container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
        }

        /* Page Title */
        .add-medicine-title {
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
            margin-top: 20px;
        }

        .btn-submit {
            background-color: #4154f1;
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
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
            /* Adjust sidebar width if needed */
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
            margin-left: 260px;
            /* Add extra space for margin */
            transition: margin-left 0.3s ease;
        }

        #mainContent.collapsed {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
                /* Reduce width on smaller screens */
            }

            #mainContent {
                margin-left: 210px;
                /* Adjust margin accordingly */
            }
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
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header d-flex justify-content-between align-items-center">
        <div class="flex-grow-1 text-center">
            <h1 class="logo">Add Medicine Details</h1>
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

    <!-- Main Content -->
    <div id="mainContent" class="add-medicine-container">
        <h1 class="add-medicine-title">Add Medicine</h1>

        <form class="add-medicine-form" id="addMedicineForm">
            <label for="medicine-name" class="form-label">Medicine Name</label>
            <input type="text" id="medicine-name" class="form-control" required>

            <label for="dosage" class="form-label">Dosage</label>
            <input type="text" id="dosage" class="form-control" required>

            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" id="quantity" class="form-control" required>

            <label for="manufacturing-date" class="form-label">Manufacturing Date</label>
            <input type="date" id="manufacturing-date" class="form-control" required>

            <label for="expiration-date" class="form-label">Expiration Date</label>
            <input type="date" id="expiration-date" class="form-control" required>

            <label for="price" class="form-label">Price</label>
            <input type="number" id="price" class="form-control" step="0.01" required><br>

            <label for="cateogry_id" class="form-label">Select Cateogry</label>
            <select id="category_id">
                <option>--- SELECT ---</option>
                <?php
                $sql = "SELECT category_id, name  From category";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['category_id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select><br>

            <label for="medicine-image" class="form-label">Upload Medicine Image</label>
            <input type="file" id="medicine-image" class="form-control" accept="image/*" required>

            <div class="btn-container">
                <button type="submit" class="btn btn-submit">Add Medicine</button>
                <button type="button" class="btn btn-back" onclick="goBack()">Back</button>
            </div>

            <div class="notes">
                <h5 class="notes-title">Notes:</h5>
                <p class="notes-text">Please ensure all information is correct before submitting.</p>
            </div>

            <!-- Alert messages -->
            <div class="alert alert-success" role="alert" style="display:none;">
                Medicine added successfully!
            </div>
            <div class="alert alert-error" role="alert" style="display:none;">
                An error occurred while adding medicine.
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const form = document.getElementById('addMedicineForm');
        const successAlert = document.querySelector('.alert-success');
        const errorAlert = document.querySelector('.alert-error');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const today = new Date().toISOString().split('T')[0]; // Moved this line up

            // Get values
            const medicineName = document.getElementById('medicine-name').value.trim();
            const dosage = document.getElementById('dosage').value.trim();
            const quantity = document.getElementById('quantity').value.trim();
            const manufacturingDate = document.getElementById('manufacturing-date').value.trim();
            const expirationDate = document.getElementById('expiration-date').value.trim();
            const categoryId = document.getElementById('category_id').value;
            const price = document.getElementById('price').value.trim();

            // Validate fields
            if (!medicineName || !dosage || !quantity || !expirationDate || !manufacturingDate) {
                alert('All fields are required.');
                return;
            }

            // Validate medicine name (no special characters)
            const namePattern = /^[A-Za-z0-9 ]+$/;
            if (!namePattern.test(medicineName)) {
                alert('Medicine name must contain only letters, numbers, and spaces.');
                return;
            }

            // Validate dosage
            const dosagePattern = /^[0-9]+ ?(mg|g|ml|L|units)$/i;
            if (!dosagePattern.test(dosage)) {
                alert('Dosage must be in a valid format (e.g., "500 mg", "5 g").');
                return;
            }

            // Validate quantity
            const maxQuantity = 500; // Set maximum capacity
            if (quantity <= 0 || quantity > maxQuantity) {
                alert(`Quantity must be greater than zero and less than or equal to ${maxQuantity}.`);
                return;
            }

            // Validate manufacturing date (cannot be in the future)
            if (manufacturingDate > today) {
                alert('Manufacturing date cannot be in the future.');
                return;
            }

            // Validate expiration date (should not be before manufacturing date)
            if (expirationDate < manufacturingDate) {
                alert('Expiration date cannot be before the manufacturing date.');
                return;
            }

            // Validate expiration date (should not be in the past)
            if (expirationDate < today) {
                alert('Expiration date cannot be in the past.');
                return;
            }

            const formData = new FormData();
            formData.append('name', medicineName);
            formData.append('dosage', dosage);
            formData.append('quantity', quantity);
            formData.append('manufacturingDate', manufacturingDate);
            formData.append('expirationDate', expirationDate);
            formData.append('category_id', categoryId);
            formData.append('price', price);
            const imageInput = document.getElementById('medicine-image');
            formData.append('medicine_image', imageInput.files[0]);

            fetch('addmedicine.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        successAlert.style.display = 'block';
                        errorAlert.style.display = 'none';
                        setTimeout(() => {
                            form.reset();
                            successAlert.style.display = 'none';
                        }, 2000);
                    } else if (data.error) {
                        errorAlert.innerText = data.error;
                        errorAlert.style.display = 'block';
                        successAlert.style.display = 'none';
                    }
                })
                .catch(() => {
                    errorAlert.innerText = 'An error occurred while adding medicine.';
                    errorAlert.style.display = 'block';
                    successAlert.style.display = 'none';
                });
        });

        function goBack() {
            window.history.back();
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('collapsed');
        }
    </script>


</body>

</html>