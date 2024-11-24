<?php
session_start(); // Start session to manage cart items across pages

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

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart item addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = 1;

    if ($quantity > 0) {
        $query = "SELECT name, price FROM menu_items WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $stmt->bind_result($item_name, $price);
        $stmt->fetch();
        $stmt->close();

        $_SESSION['cart'][] = [
            'name' => $item_name,
            'price' => $price,
            'quantity' => $quantity,
        ];

        echo "<script>alert('Item added to cart!');</script>";
    } else {
        echo "<script>alert('Invalid quantity.');</script>";
    }
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Suhana Menu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Suhana Menu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef6ec;
            color: #333;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Welcome text styles */
        header h1 {
            font-size: 2.5rem;
            color: #064420; /* Dark green */
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.6); /* Glow for better readability */
            margin: 0;
        }

        header p {
            font-size: 1.2rem;
            color: #064420; /* Dark green for consistency */
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.6); /* Glow effect */
            margin-top: 10px;
        }

        /* Back button styles */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            color: #064420;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-button:hover {
            color: #2d6a4f;
        }

        /* Category titles */
        .category-title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .meal-categories {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 items per row */
            gap: 20px;
            justify-items: center;
        }

        .meal-category {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }

        .meal-category img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .meal-category h3 {
            font-size: 20px;
            margin: 10px 0;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }

        .buttons {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .buttons button.add-to-cart {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .buttons button.add-to-cart:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        /* Floating cart button */
        .floating-cart {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #e63946;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            font-size: 1.5rem;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .floating-cart:hover {
            background-color: #d62839;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
       <!-- Back Button -->
       <a href="index.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>
    <header>
        <h1>Warung Suhana Menu</h1>
        <p>Choose your favorite dishes and add them to your cart!</p>
    </header>
    <div class="menu-container">
        <!-- Categories -->
        <?php
        $categories = [
            "Nasi Lemak" => [
                ['id' => 1, 'name' => 'Nasi Lemak Telur Mata', 'price' => 5.00, 'img' => 'image/nasilemaktelur.jpg'],
                ['id' => 2, 'name' => 'Nasi Lemak Ayam Goreng', 'price' => 8.00, 'img' => 'image/nasilemakayamgoreng.jpg'],
                ['id' => 3, 'name' => 'Nasi Lemak Ayam Rendang', 'price' => 8.00, 'img' => 'image/nasilemakrendangayam.jpg']
            ],
           "Roti Canai" => [
                ['id' => 4, 'name' => 'Roti Kosong', 'price' => 1.30, 'img' => 'image/rotikosong.jpg'],
                ['id' => 5, 'name' => 'Roti Telur', 'price' => 3.00, 'img' => 'image/rotitelur.jpg'],
                ['id' => 6, 'name' => 'Roti Planta', 'price' => 2.00, 'img' => 'image/rotikosong.jpg'],
                ['id' => 7, 'name' => 'Roti Bawang', 'price' => 2.00, 'img' => 'image/rotibawang.jpg'],
                ['id' => 8, 'name' => 'Roti Telur', 'price' => 3.00, 'img' => 'image/rotitelur.jpg'],
                ['id' => 9, 'name' => 'Roti Tampal', 'price' => 3.00, 'img' => 'image/rotitampal.jpg'],
                ['id' => 10, 'name' => 'Roti Telur Goyang', 'price' => 4.00, 'img' => 'image/rotitelurgoyang.jpg'],
                ['id' => 11, 'name' => 'Roti Sardin', 'price' => 4.50, 'img' => 'image/rotisardin.jpg']
                ],


            "Goreng Lambak" => [
             ['id' => 12, 'name' => 'Nasi Goreng', 'price' => 4.00, 'img' => 'image/nasigoreng.jpg'],
             ['id' => 13, 'name' => 'Mihun Goreng', 'price' => 4.00, 'img' => 'image/mihungoreng.jpg'],
             ['id' => 14, 'name' => 'Kue Tiau Goreng', 'price' => 4.00, 'img' => 'image/kuetiaugoreng.jpg'],
             ['id' => 15, 'name' => 'Mi Goreng', 'price' => 4.00, 'img' => 'image/migoreng.jpg']
            ],

            "Drinks (Hot)" => [
                ['id' => 16,'name' => 'Teh O', 'price' => 1.00, 'img' => 'image/teo.jpg'],
                ['id' => 17,'name' => 'Teh Tarik', 'price' => 1.50, 'img' => 'image/tehtarik.jpg'],
                ['id' => 18,'name' => 'Milo', 'price' => 1.80, 'img' => 'image/milo.jpg'],
                ['id' => 19,'name' => 'Neslo', 'price' => 1.80, 'img' => 'image/neslo.jpg'],
                ['id' => 20,'name' => 'Kopi O', 'price' => 1.30, 'img' => 'image/kopio.jpg'],
                ['id' => 21,'name' => 'Kopi', 'price' => 1.60, 'img' => 'image/kopi.jpg'],
                ['id' => 22,'name' => 'Nescafe', 'price' => 1.70, 'img' => 'image/nescafe.jpg'],
                ['id' => 23,'name' => 'Nescafe O', 'price' => 1.30, 'img' => 'image/nescafeo.jpg']
            ],
            "Drinks (Cold)" => [
            ['id' => 24,'name' => 'Teh O Ais', 'price' => 2.00, 'img' => 'image/tehoais.jpg'],
            ['id' => 25,'name' => 'Teh Ais', 'price' => 2.30, 'img' => 'image/tehais.jpg'],
            ['id' => 26,'name' => 'Sirap Ais', 'price' => 2.00, 'img' => 'image/sirapais.jpg'],
            ['id' => 27,'name' => 'Sirap Bandung Ais', 'price' => 2.30, 'img' => 'image/bandung.jpg'],
            ['id' => 28,'name' => 'Milo Ais', 'price' => 2.50, 'img' => 'image/miloais.jpg'],
            ['id' => 30,'name' => 'Nescafe Ais', 'price' => 2.50, 'img' => 'image/nescafeais.jpg'],
            ['id' => 31,'name' => 'Neslo Ais', 'price' => 2.50, 'img' => 'image/kopiais.jpg'],
            ['id' => 32,'name' => 'Kopi O Ais', 'price' => 2.30, 'img' => 'image/kopioais.jpg'],
            ['id' => 33,'name' => 'Kopi Ais', 'price' => 2.50, 'img' => 'image/kopiais.jpg'],
            ['id' => 34,'name' => 'Ais Kosong', 'price' => 0.00, 'img' => 'image/aiskosong.jpg']
            ]
        ];

        foreach ($categories as $category => $meals) {
            echo '<h2 class="category-title">' . htmlspecialchars($category) . '</h2>';
            echo '<div class="meal-categories">';
            foreach ($meals as $meal) {
                echo '
                <div class="meal-category">
                    <img src="' . $meal['img'] . '" alt="' . htmlspecialchars($meal['name']) . '">
                    <h3>' . htmlspecialchars($meal['name']) . '</h3>
                    <div class="price">RM ' . number_format($meal['price'], 2) . '</div>
                    <div class="buttons">
                        <form method="POST">
                            <input type="hidden" name="item_id" value="' . $meal['id'] . '">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" name="add_to_cart" class="add-to-cart">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>';
            }
            echo '</div>';
        }
        ?>
    </div>

    <!-- Floating Cart Button -->
    <a href="cart_summary.php" class="floating-cart">
        <i class="fas fa-shopping-cart"></i>
    </a>
</body>
</html>

