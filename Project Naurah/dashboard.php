<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'warung_suhana';
$username = 'root';
$password = 'Hadirah07_';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data for the dashboard
    $adminsQuery = $pdo->query("SELECT COUNT(*) AS total_admins FROM admins");
    $totalAdmins = $adminsQuery->fetch(PDO::FETCH_ASSOC)['total_admins'];

    $menuItemsQuery = $pdo->query("SELECT COUNT(*) AS total_menu_items FROM menu_items");
    $totalMenuItems = $menuItemsQuery->fetch(PDO::FETCH_ASSOC)['total_menu_items'];

    $ordersQuery = $pdo->query("SELECT COUNT(*) AS total_orders FROM orders");
    $totalOrders = $ordersQuery->fetch(PDO::FETCH_ASSOC)['total_orders'];

    $pendingOrdersQuery = $pdo->query("SELECT COUNT(*) AS pending_orders FROM orders WHERE status = 'Pending'");
    $pendingOrders = $pendingOrdersQuery->fetch(PDO::FETCH_ASSOC)['pending_orders'];
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #4CAF50;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo img {
            height: 50px;
        }

        .logo h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .menu-search-container {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown button {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .dropdown button:hover {
            background-color: #45a049;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1;
        }

        .dropdown-content a {
            padding: 12px 16px;
            display: block;
            color: #333;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: #4CAF50;
            color: white;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .card {
            flex: 0 1 calc(25% - 20px);
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            font-size: 2rem;
            color: #333;
        }

        .card p {
            font-size: 1.2rem;
            color: #555;
        }

        .manage-orders {
            text-align: center;
            margin-top: 20px;
        }

        .manage-orders a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .manage-orders a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.jpg" alt="Warung Suhana Logo">
            <h1>Admin Dashboard</h1>
        </div>
        <div class="menu-search-container">
            <div class="dropdown">
                <button>Menu</button>
                <div class="dropdown-content">
                    <a href="dashboard.php">Dashboard</a>
                    <a href="orders_management.php">Manage Orders</a>
                    <a href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="dashboard-row">
            <div class="card">
                <h2><?= htmlspecialchars($totalAdmins) ?></h2>
                <p>Total Admins</p>
            </div>
            <div class="card">
                <h2><?= htmlspecialchars($totalMenuItems) ?></h2>
                <p>Menu Items</p>
            </div>
            <div class="card">
                <h2><?= htmlspecialchars($totalOrders) ?></h2>
                <p>Total Orders</p>
            </div>
            <div class="card">
                <h2><?= htmlspecialchars($pendingOrders) ?></h2>
                <p>Pending Orders</p>
            </div>
        </div>
        <div class="manage-orders">
            <a href="orders_management.php">Go to Order Management</a>
        </div>
    </div>
</body>
</html>
