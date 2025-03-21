<?php
$login = 0;
$role = 'U';
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
  $login = 1;
  $role = $_SESSION['role'];
  $username = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feeling Better? - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap CSS for hamburger menu -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f1f1f1;
        }

        .navbar {
            background: #00b4d8;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
            cursor: pointer;
        }

        .menu-btn {
            display: inline-block;
            padding: 10px;
            border: none;
            background: transparent;
            color: #fff;
            cursor: pointer;
        }

        .menu-btn span {
            display: block;
            width: 20px;
            height: 2px;
            background-color: #fff;
            margin-bottom: 5px;
            transition: all 0.3s;
        }

        .menu-btn:hover span {
            background-color: #ccc;
        }

        .sidebar {
            position: fixed;
            width: 250px;
            height: 100%;
            background: #00b4d8;
            color: #fff;
            transition: width 0.3s;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar h2 {
            text-align: center;
            margin: 20px 0 30px;
            display: block;
            /* Show sidebar title in expanded mode */
        }

        .sidebar.collapsed h2 {
            display: none;
            /* Hide sidebar title in collapsed mode */
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 12px 20px;
            /* Decreased padding to reduce the gap */
            border-bottom: 1px solid #007BFF;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            /* Align items to the left */
        }

        .sidebar ul li:hover {
            background: #0077b6;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            /* Ensure icons and text are displayed */
            align-items: center;
            width: 100%;
            /* Take full width */
            padding-left: 10px;
            /* Adjust left padding */
        }

        .sidebar ul li a .logo {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            /* Add margin between icon and text */
        }

        .sidebar ul li a .text {
            margin-left: 10px;
            /* Adjust left margin for text */
            display: block;
            /* Ensure text is always displayed */
        }

        .sidebar.collapsed ul li a .text {
            display: none;
            /* Hide text in collapsed mode */
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        header {
            background: #fff;
            padding: 20px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .cards {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
            flex: 1;
            min-width: 200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .icon-case {
            font-size: 2.5rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }

            .sidebar {
                position: fixed;
                width: 250px;
                height: 100%;
                background: #00b4d8;
                color: #fff;
                transition: width 0.3s, transform 0.3s;
                overflow: hidden;
            }

            .main-content {
                margin-left: 250px;
                padding: 20px;
                transition: margin-left 0.3s;
            }

            .sidebar.collapsed {
                width: 80px;
            }

            .sidebar h2 {
                display: block;
                /* Show sidebar title in expanded mode */
            }

            .sidebar.collapsed h2 {
                display: none;
                /* Hide sidebar title in collapsed mode */
            }

            .main-content {
                margin-left: 100px;
            }

            .sidebar ul li {
                text-align: center;
            }

            .sidebar ul li a {
                display: flex;
                /* Ensure icons and text are displayed */
                align-items: center;
                width: 100%;
                /* Take full width */
                padding-left: 10px;
                /* Adjust left padding */
            }

            .cards {
                flex-direction: column;
            }

            .card {
                width: 100%;
            }

            .navbar .menu-btn {
                display: block;
            }

            .navbar .hamburger-menu {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1 onclick="redirectToIndex()">Feeling Better?</h1>
        <!-- Bootstrap hamburger menu icon -->
        <button class="menu-btn" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="#" data-text="Home">
                    <div class="logo"><i class="fas fa-home"></i></div> <span class="text">Home</span>
                </a></li>
            <li><a href="#" data-text="Users">
                    <div class="logo"><i class="fas fa-users"></i></div> <span class="text">Users</span>
                </a></li>
            <li><a href="#" data-text="Medicines">
                    <div class="logo"><i class="fas fa-capsules"></i></div> <span class="text">Medicines</span>
                </a></li>
            <li><a href="#" data-text="Deliveries">
                    <div class="logo"><i class="fas fa-truck"></i></div> <span class="text">Deliveries</span>
                </a></li>
            <li><a href="#" data-text="Settings">
                    <div class="logo"><i class="fas fa-cog"></i></div> <span class="text">Settings</span>
                </a></li>
            <li><a href="#" data-text="Logout">
                    <div class="logo"><i class="fas fa-sign-out-alt"></i></div> <span class="text">Logout</span>
                </a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h2>Dashboard</h2>
        </header>
        <main>
            <div class="cards">
                <div class="card">
                    <div class="box">
                        <h3>1500</h3>
                        <p>Users</p>
                    </div>
                    <div class="icon-case">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <h3>1200</h3>
                        <p>Medicines</p>
                    </div>
                    <div class="icon-case">
                        <i class="fas fa-capsules"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <h3>300</h3>
                        <p>Deliveries</p>
                    </div>
                    <div class="icon-case">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <h3>50</h3>
                        <p>New Orders</p>
                    </div>
                    <div class="icon-case">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function redirectToIndex() {
            window.location.href = 'index.php';
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const menuLinks = document.querySelectorAll('.sidebar ul li a');
            if (sidebar.classList.contains('collapsed')) {
                // Expand the sidebar
                sidebar.classList.remove('collapsed');
                mainContent.style.marginLeft = '250px'; // Set to sidebar width when expanded
                // Show menu text
                menuLinks.forEach(link => {
                    link.querySelector('.text').style.display = 'block'; // Display text in expanded mode
                });
            } else {
                // Collapse the sidebar
                sidebar.classList.add('collapsed');
                mainContent.style.marginLeft = '80px'; // Set to sidebar width when collapsed
                // Hide menu text
                menuLinks.forEach(link => {
                    link.querySelector('.text').style.display = 'none'; // Hide text in collapsed mode
                });
            }
        }
    </script>
</body>

</html>