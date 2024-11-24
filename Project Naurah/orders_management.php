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

    // Fetch all orders with customer details
    $ordersQuery = $pdo->query("
        SELECT orders.id, orders.order_date, orders.status, orders.total_price, customer_detail.name AS customer_name
        FROM orders
        JOIN customer_detail ON orders.customer_id = customer_detail.id
    ");
    $orders = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);

    // Fetch order details for each order
    $orderDetailsQuery = $pdo->query("
        SELECT order_details.order_id, order_details.item_name, order_details.quantity, order_details.item_price
        FROM order_details
    ");
    $orderDetails = $orderDetailsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Organize order details by order_id for easy access
    $detailsByOrder = [];
    foreach ($orderDetails as $detail) {
        $detailsByOrder[$detail['order_id']][] = $detail;
    }

    // Handle status update
    if (isset($_GET['action']) && isset($_GET['order_id'])) {
        $orderId = intval($_GET['order_id']);
        $action = $_GET['action'];

        if ($action === 'approve') {
            $pdo->prepare("UPDATE orders SET status = 'Completed' WHERE id = ?")->execute([$orderId]);
        } elseif ($action === 'cancel') {
            $pdo->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?")->execute([$orderId]);
        }
        header("Location: orders_management.php"); // Refresh the page
        exit();
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef6ec;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #064420;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .order-items {
            margin-top: 10px;
            font-size: 0.9em;
            color: #555;
        }

        .order-items th {
            color: white;
            background-color: #28a745;
            padding: 5px;
        }

        .order-items td {
            color: #064420; /* Green color for item name, price, and quantity */
            padding: 5px;
        }

        a {
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .approve {
            background-color: #28a745;
        }

        .cancel {
            background-color: #dc3545;
        }

        .approve:hover {
            background-color: #218838;
        }

        .cancel:hover {
            background-color: #c82333;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            color: #064420;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .back-button:hover {
            color: #2d6a4f;
        }

        .back-button i {
            font-size: 30px;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            .approve, .cancel {
                padding: 5px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container">
        <h1>Orders Management</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>RM <?= htmlspecialchars(number_format($order['total_price'], 2)) ?></td>
                        <td>
                            <?php if ($order['status'] === 'Pending'): ?>
                                <a href="?action=approve&order_id=<?= $order['id'] ?>" class="approve">Approve</a>
                                <a href="?action=cancel&order_id=<?= $order['id'] ?>" class="cancel">Cancel</a>
                            <?php else: ?>
                                <span><?= htmlspecialchars($order['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <div class="order-items">
                                <strong>Items Ordered:</strong>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($detailsByOrder[$order['id']])): ?>
                                            <?php foreach ($detailsByOrder[$order['id']] as $item): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                                                    <td>RM <?= htmlspecialchars(number_format($item['item_price'], 2)) ?></td>
                                                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3">No items found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>
