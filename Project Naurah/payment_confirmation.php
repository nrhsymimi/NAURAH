<?php
session_start();

// Initialize the variable to avoid undefined variable warning
$orderConfirmed = false;

// Database connection
$servername = "localhost";
$username = "root";
$password = "Hadirah07_";
$dbname = "warung_suhana";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve customer details
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $payment_method = "QR Payment"; // Fixed payment method
    $status = "Pending"; // Default order status

    // Validate input
    if (empty($name) || empty($phone) || empty($address)) {
        die("Please fill out all required fields.");
    }

    // Calculate total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

    // Insert customer details
    $customer_query = "INSERT INTO customer_detail (name, phone, address, status, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($customer_query);
    $stmt->bind_param("ssss", $name, $phone, $address, $status);
    $stmt->execute();
    $customer_id = $conn->insert_id; // Get the inserted customer ID

    // Insert order
    $order_query = "INSERT INTO orders (customer_id, total_price, payment_method, status, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("idss", $customer_id, $total_price, $payment_method, $status);
    $stmt->execute();
    $order_id = $conn->insert_id; // Get the inserted order ID

    // Insert order details
    $order_details_query = "INSERT INTO order_details (order_id, item_name, item_price, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($order_details_query);
    foreach ($_SESSION['cart'] as $item) {
        $stmt->bind_param("isdi", $order_id, $item['name'], $item['price'], $item['quantity']);
        $stmt->execute();
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Mark the order as confirmed
    $orderConfirmed = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
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
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            max-width: 750px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #064420;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
            font-size: 1rem;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        textarea {
            resize: none;
            height: 100px;
        }
        button {
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .success-message {
            color: #28a745;
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-align: center;
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
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="menu.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container">
        <?php if ($orderConfirmed): ?>
            <div class="success-message">
                Thank You for Your Order!
            </div>
            <p>Your order has been placed successfully.</p>
            <a href="menu.php" class="back-button">Back to Menu</a>
        <?php else: ?>
            <h1>Confirm Your Order</h1>
            <form method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" required>

                <label for="address">Address</label>
                <textarea id="address" name="address" required></textarea>

                <button type="submit">Confirm Order</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>
