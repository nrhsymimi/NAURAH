<?php
session_start(); // Start session to access cart

$totalCost = 0; // Initialize total cost

// Handle item removal
if (isset($_GET['remove_item'])) {
    $itemIndex = $_GET['remove_item'];
    unset($_SESSION['cart'][$itemIndex]);
    header("Location: cart_summary.php");
    exit();
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $itemIndex = $_POST['item_index'];
    $newQuantity = intval($_POST['quantity']);
    if ($newQuantity > 0) {
        $_SESSION['cart'][$itemIndex]['quantity'] = $newQuantity;
    } else {
        unset($_SESSION['cart'][$itemIndex]); // Remove item if quantity is zero
    }
    header("Location: cart_summary.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Summary</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef6ec;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cart-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-items table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-items th, .cart-items td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-items th {
            background-color: #064420;
            color: white;
        }

        .total-cost {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Remove Button Styles */
        .btn-remove {
            background-color: #e63946;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #c02736;
        }

        /* Quantity Box Styles */
        .quantity-box {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .quantity-box input[type="number"] {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }

        .quantity-box button {
            background-color: #064420;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .quantity-box button:hover {
            background-color: #2d6a4f;
        }

        /* Back button styles */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            color: #064420;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-button:hover {
            color: #2d6a4f;
        }

    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="menu.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="cart-container">
        <h2>Cart Summary</h2>
        <div class="cart-items">
            <table>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>RM <?= number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" class="quantity-box">
                                    <button type="button" onclick="updateQuantity(<?= $index ?>, -1)">-</button>
                                    <input type="number" name="quantity" value="<?= $item['quantity']; ?>" readonly>
                                    <button type="button" onclick="updateQuantity(<?= $index ?>, 1)">+</button>
                                    <input type="hidden" name="item_index" value="<?= $index ?>">
                                    <input type="hidden" name="update_quantity" value="true">
                                </form>
                            </td>
                            <td>RM <?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td><a href="?remove_item=<?= $index ?>" class="btn-remove">Remove</a></td>
                        </tr>
                        <?php $totalCost += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Your cart is empty.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="total-cost">
            Total Cost: RM <span id="total-cost"><?= number_format($totalCost, 2); ?></span>
        </div>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div style="text-align: right; margin-top: 20px;">
                <a href="payment_confirmation.php" class="btn">Proceed to Payment</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateQuantity(index, delta) {
            const quantityInput = document.querySelectorAll('input[name="quantity"]')[index];
            const currentQuantity = parseInt(quantityInput.value);
            const newQuantity = currentQuantity + delta;

            if (newQuantity > 0) {
                quantityInput.value = newQuantity;
                document.querySelectorAll('form')[index].submit();
            }
        }
    </script>
</body>
</html>
