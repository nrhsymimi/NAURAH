<?php
session_start();  // Start session to manage cart data

// Initialize total cost
$totalCost = 0; // Ensure this variable is initialized

// Handle quantity updates via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity_update'])) {
    $itemIndex = $_POST['item_index'];
    $newQuantity = intval($_POST['new_quantity']);
    if (isset($_SESSION['cart'][$itemIndex])) {
        $_SESSION['cart'][$itemIndex]['quantity'] = $newQuantity;
    }
    echo json_encode(['status' => 'success']);
    exit();
}

// Handle item removal
if (isset($_GET['remove_item'])) {
    $itemIndex = $_GET['remove_item'];
    unset($_SESSION['cart'][$itemIndex]);
    header("Location: " . $_SERVER['PHP_SELF']); // Reload the page after removal
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        .checkout-container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .checkout-container h2 {
            text-align: center;
            color: #4CAF50;
        }
        .checkout-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .checkout-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 15px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .checkout-item:last-child {
            border-bottom: none;
        }
        .checkout-total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .checkout-button {
            margin-top: 20px;
            text-align: center;
        }
        .checkout-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
        }
        .checkout-button a:hover {
            background-color: #45a049;
        }
        .back-to-menu {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-align: center;
        }
        .back-to-menu:hover {
            background-color: #0056b3;
        }
        .quantity-controls input[type="number"] {
            width: 40px;
            height: 30px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button-danger {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .button-danger:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <a href="menu.php" class="back-to-menu">← Back to Menu</a>
        <h2>Checkout Summary</h2>

        <ul class="checkout-items" id="cart-items">
            <?php if (empty($_SESSION['cart'])): ?>
                <li>Your cart is empty.</li>
            <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                    <li class="checkout-item">
                        <div><strong><?= htmlspecialchars($item['name']) ?></strong></div>
                        <div>RM <?= number_format($item['price'], 2) ?></div>
                        <div>
                            <input
                                type="number"
                                class="quantity"
                                data-index="<?= $index ?>"
                                value="<?= $item['quantity'] ?>"
                                min="1"
                            />
                        </div>
                        <div>RM <span class="item-total"><?= number_format($item['price'] * $item['quantity'], 2) ?></span></div>
                        <div>
                            <a href="?remove_item=<?= $index ?>" class="button-danger">Remove</a>
                        </div>
                    </li>
                    <?php $totalCost += $item['price'] * $item['quantity']; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <div class="checkout-total">
            <p>Total: RM <span id="total-cost"><?= number_format($totalCost, 2) ?></span></p>
        </div>

        <!-- Checkout Button -->
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="checkout-button">
                <a href="successful.php">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const quantities = document.querySelectorAll(".quantity");

        quantities.forEach(input => {
            input.addEventListener("change", function () {
                const index = this.dataset.index;
                const newQuantity = this.value;

                if (newQuantity < 1) return;

                // Send AJAX request to update quantity
                fetch("summary.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `quantity_update=1&item_index=${index}&new_quantity=${newQuantity}`,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            // Recalculate total
                            const itemRow = this.closest(".checkout-item");
                            const itemPrice = parseFloat(itemRow.children[1].innerText.replace("RM", "").trim());
                            const itemTotal = itemRow.querySelector(".item-total");
                            const totalCost = document.getElementById("total-cost");

                            // Update the item total
                            itemTotal.innerText = (itemPrice * newQuantity).toFixed(2);

                            // Update the total cost
                            let newTotal = 0;
                            document.querySelectorAll(".item-total").forEach(item => {
                                newTotal += parseFloat(item.innerText);
                            });
                            totalCost.innerText = newTotal.toFixed(2);
                        }
                    })
                    .catch(err => console.error("Error:", err));
            });
        });
    </script>
</body>
</html>
