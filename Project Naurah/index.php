<?php
// Optional: You can add PHP logic here, such as including other files, or fetching data
// For example: include("config.php"); or $menuItems = fetchMenuItemsFromDatabase();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Suhana</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        a {
            text-decoration: none;
        }

        header {
            background-color: #4CAF50;
            color: white;
            display: flex;
            justify-content: center;
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
            justify-content: center;
            flex-grow: 1;
        }

        .logo img {
            height: 90px;
            width: auto;
        }

        .logo h1 {
            font-size: 28px;
            font-weight: bold;
        }

        .menu-search-container {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: flex-end;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown button {
            padding: 15px 20px;
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

        .main-banner {
            position: relative;
            width: 100%;
            height: 400px;
            background-image: url('image/banner.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
        }

        .main-banner h2 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .main-banner p {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .main-banner .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f77f00;
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .main-banner .button:hover {
            background-color: #d97706;
            transform: scale(1.05);
        }

        .button {
            padding: 15px 20px;
            font-size: 16px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
        }

        .button:hover {
            background-color: #45a049;
        }

        .meal-categories {
            max-width: 1200px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .category-title {
            width: 100%;
            text-align: center;
            font-size: 2rem;
            margin: 40px 0 20px;
            color: #d32f2f;
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            text-transform: uppercase;
        }

        .meal-category {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            padding: 10px;
        }

        .meal-category:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .meal-category img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .meal-category h3 {
            font-size: 20px;
            margin: 15px 0 5px;
        }

        .meal-category .button {
            margin-top: auto;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            background-color: #ffa500;
            color: white;
        }

        .meal-category .button:hover {
            background-color: #ff8c00;
        }

        #contact-us-section {
            display: none;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }

    </style>
</head>
<body>

    <header>
        <div class="logo">
            <img src="image/logo.jpg" alt="Warung Suhana Logo">
            <h1>Warung Suhana</h1>
        </div>
        <div class="menu-search-container">
            <div class="dropdown">
                <button>Menu</button>
                <div class="dropdown-content">
                    <a href="index.php">Home</a>
                    <a href="menu.php">Menu</a>
                    <a href="order.php">Your Cart</a>
                    <a href="#contact-us-section">Contact Us</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-banner">
        <h2>Welcome to Warung Suhana</h2>
        <p>Indulge in the taste of authentic Malaysian dishes!</p>
        <a href="menu.php" class="button">View Menu</a>
    </div>

    <section id="contact-us-section">
        <h2>Contact Us</h2>
        <p>We would love to hear from you! Reach us via:</p>
        <p><strong>Email:</strong> info@warungsuhana.com</p>
        <p><strong>Phone:</strong> +6019-6062075</p>
        <p><strong>Address:</strong> 123 Jalan Siantan 3, Durian Tunggal, Melaka</p>
        <form action="submit_contact.php" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send</button>
        </form>
    </section>

    <div class="category-title">Let's Try Our Menu</div>
    <div class="meal-categories">
        <div class="meal-category">
            <img src="image/nasilemaktelur.jpg" alt="Nasi Lemak">
            <h3>Nasi Lemak</h3>
            <p>Fragrant rice cooked in coconut milk, served with sambal, peanuts, and boiled egg.</p>
            <a href="menu.php" class="button">Order Now</a>
        </div>
        <div class="meal-category">
            <img src="image/rotitelur.jpg" alt="Roti Canai">
            <h3>Roti Canai</h3>
            <p>A crispy flatbread served with dhal curry and sugar.</p>
            <a href="menu.php" class="button">Order Now</a>
        </div>
        <div class="meal-category">
            <img src="image/migoreng.jpg" alt="Malaysian Noodles">
            <h3>Malaysian Noodles</h3>
            <p>Stir-fried noodles with vegetables, meat, and savory sauces.</p>
            <a href="menu.php" class="button">Order Now</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Warung Suhana. All Rights Reserved.</p>
    </footer>

    <script>
        const contactUsButton = document.querySelector('a[href="#contact-us-section"]');
        const contactUsSection = document.getElementById('contact-us-section');

        contactUsButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default anchor click behavior
            if (contactUsSection.style.display === 'none' || contactUsSection.style.display === '') {
                contactUsSection.style.display = 'block';
            } else {
                contactUsSection.style.display = 'none';
            }
        });
    </script>

</body>
</html>
