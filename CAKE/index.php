<?php
session_start();

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "cake"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding products to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?: 1; 

    if ($productId && isset($products[$productId - 1])) {
        $product = $products[$productId - 1]; // Get product details from the array

        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 0,
                'image' => $product['image'], // Store image in cart
            ];
        }

        // Update product quantity in the cart
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    }

    // Redirect to avoid resubmission on refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch reviews from messages table
$reviews = [];
$sql = "SELECT name, message AS review FROM messages ORDER BY created_at DESC"; // Fetch from messages table
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch each review
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cake Shop</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            background-color: purple;
            padding: 10px;
            border-radius: 5px;
        }

        header a {
            margin: 0 15px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        h1, h2 {
            text-align: center;
        }

        .reviews {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .reviews .review {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            margin-top: 40px;
            padding: 20px;
            background-color: purple;
            color: white;
            text-align: center;
        }

        .featured-cakes {
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            text-align: center;
        }

        .featured-cakes h2 {
            color: purple;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .featured-cakes p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 20px;
        }

        .cake-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
        }

        .cake-item {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            width: calc(25% - 20px); /* Four cakes per row */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cake-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .cake-item img {
            width: 100%;
            border-radius: 10px;
            height: auto;
            max-height: 150px;
            object-fit: cover;
        }

        .cake-item h3 {
            color: green;
            font-size: 1.5em;
            margin: 10px 0;
        }

        .cake-item p {
            font-size: 1em;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <a href="pay/about.html">ABOUT</a>
        <a href="pay/contact.html">CONTACT</a>
        <a href="auth/signup.php">REGISTER</a>
        <a href="pay/payment.php">PROCEED TO PAYMENT</a>
        <a href="pay/product.php">PRODUCT</a>
    </header>

    <div class="container">
        <h1>Cake Shop</h1>

        <div class="featured-cakes">
            <h2>Welcome to Our Cake Shop</h2>
            <p>Discover a world of delightful cakes, crafted with love and the finest ingredients. Whether you're celebrating a birthday, anniversary, or just satisfying your sweet tooth, we have the perfect cake for you!</p>

            <div class="cake-gallery">
                <div class="cake-item">
                    <img src="images/cake2.jpg" alt="Delicious Chocolate Cake">
                    <h3>Rich Chocolate Cake</h3>
                    <p>Indulge in the rich flavors of our chocolate cake, topped with a smooth chocolate ganache. Perfect for any chocolate lover!</p>
                </div>

                <div class="cake-item">
                    <img src="images/cake3.jpg" alt="Fresh Strawberry Cake">
                    <h3>Fresh Strawberry Delight</h3>
                    <p>A light and fluffy cake layered with fresh strawberries and cream, offering the perfect balance of sweetness and tartness.</p>
                </div>

                <div class="cake-item">
                    <img src="images/cake4.jpeg" alt="Red Velvet Cake">
                    <h3>Classic Red Velvet</h3>
                    <p>Our signature red velvet cake is a favorite, with a hint of cocoa and velvety cream cheese frosting. A timeless classic!</p>
                </div>

                <div class="cake-item">
                    <img src="images/cake5.jpeg" alt="Vanilla Cake">
                    <h3>Elegant Vanilla Cake</h3>
                    <p>For those who love simplicity, our vanilla cake is soft, moist, and full of rich vanilla flavor, beautifully decorated to impress.</p>
                </div>
            </div>
        </div>

        <div class="reviews">
            <h2>Customer Reviews</h2>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <strong><?php echo htmlspecialchars($review['name']); ?></strong>
                    <p><?php echo htmlspecialchars($review['review']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Cake Shop. All rights reserved.</p>
    </footer>
</body>
</html>
