<?php
session_start(); // Start the session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sample product data with updated IDs and images
$products = [
    [
        'id' => 21,
        'name'=> 'vanilla',
        'description' => 'Classic vanilla cake with creamy vanilla icing.',
        'price' => '200.00',
        'image' => '../images/cake2.jpg',
    ],
    [
        'id' => 31,
        'name' => 'Lemon Cake',
        'description' => 'Zesty lemon cake with lemon frosting.',
        'price' => '22.00',
        'image' => '../images/cake3.jpg',
    ],
    [
        'id' => 41,
        'name' => 'Fruit Cake',
        'description' => 'Moist cake filled with assorted fruits and nuts.',
        'price' => '28.00',
        'image' => '../images/cake4.jpeg',
    ],
    [
        'id' => 51,
        'name' => 'Carrot Cake',
        'description' => 'Moist carrot cake topped with cream cheese frostin....',
        'price' => '26.00',
        'image' => '../images/cake5.jpeg',
    ],
    
    [
        'id' => 31,
        'name' => 'Black forest Cake',
        'description' => 'Rich chocolate cake with cherries and whipped crea....',
        'price' => '32.00',
        'image' => '../images/cake6.jpeg',
    ],
    [
        'id' => 41,
        'name' => 'Pinneapple Cake',
        'description' => 'A classic pineapple upside-down cake..',
        'price' => '27.00',
        'image' => '../images/cake8.jpeg',
    ],
   
     [
        'id' => 21,
        'name'=> 'Cheese cake',
        'description' => 'Creamy cheesecake with a graham cracker crust..',
        'price' => '35.00',
        'image' => '../images/cake9.jpeg',
    ],
    [
        'id' => 31,
        'name' => 'Matcha Cake',
        'description' => '  Green tea flavored cake with matcha frosting..',
        'price' => '29.00',
        'image' => '../images/cake10.jpeg',
    ],
    [
        'id' => 41,
        'name' => 'Red berry Cake',
        'description' => 'Vanilla cake topped with mixed red berries.',
        'price' => '31.00',
        'image' => '../images/cake11.jpeg',
    ],
   
];

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $alreadyInCart = false;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['id']) && $item['id'] == $product_id) {
            $alreadyInCart = true;
            break;
        }
    }

    // If the product is not already in the cart, add it
    if (!$alreadyInCart) {
        // Find the product by ID
        foreach ($products as $product) {
            if ($product['id'] == $product_id) {
                // Add the product to the cart
                $_SESSION['cart'][] = $product;
                break;
            }
        }
    }

    // Redirect to the same page to avoid resubmission
    header("Location: product.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page - Cakes</title>
    <style>
        body {
            background-color: #e5e0f1; /* Light purple background */
            color: #4b3d73; /* Darker purple text */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        header {
            background-color: #4b3d73; /* Header background color */
            padding: 10px 0;
            margin-bottom: 20px;
        }
        header ul {
            list-style: none; /* Remove bullets */
            padding: 0;
            display: flex; /* Flexbox for horizontal layout */
            justify-content: center; /* Center align */
            margin: 0;
        }
        header li {
            margin: 0 15px; /* Spacing between links */
        }
        header a {
            color: white; /* White text for links */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Bold text */
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .product-card {
            background-color: #ffffff; /* White background for cards */
            border: 2px solid #4b3d73; /* Border color */
            border-radius: 10px; /* Rounded corners */
            width: 240px; /* Fixed width for product cards */
            margin: 10px; /* Space between cards */
            padding: 10px; /* Inner padding */
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow effect */
            transition: transform 0.2s; /* Animation on hover */
        }
        .product-card:hover {
            transform: scale(1.05); /* Scale effect on hover */
        }
        img {
            max-width: 100%; /* Responsive images */
            border-radius: 5px; /* Rounded corners for images */
        }
        .price {
            font-size: 1.5em; /* Larger font for price */
            color: #e74c3c; /* Red color for price */
        }
        .buy-button {
            background-color: #4b3d73; /* Purple button */
            color: white; /* White text */
            border: none;
            border-radius: 5px; /* Rounded corners */
            padding: 10px;
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s; /* Smooth transition */
            margin-top: 10px; /* Space above button */
        }
        .buy-button:hover {
            background-color: #3a2e5f; /* Darker purple on hover */
        }
        .cart-button {
            position: fixed; /* Fixed position */
            top: 20px; /* Position from the top */
            right: 20px; /* Position from the right */
            padding: 10px 15px; /* Padding */
            background-color: #4b3d73; /* Purple */
            color: white; /* White text */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
        }
        .cart-button:hover {
            background-color: #3a2e5f; /* Darker purple on hover */
        }
    </style>
</head>
<body>
    <header>
        <ul>
            <li><a href="../index.php">HOME</a></li>
            <li><a href="contact.html">CONTACT</a></li>
            <li><a href="signup.php">REGISTER</a></li>
            <li><a href="payment.php">PROCEED TO PAYMENT</a></li>
            <li><a href="about.html">ABOUT</a></li>
        </ul>
        <h1>Our Delicious Cakes</h1>
        <!-- Cart Button -->
        <a href="cart.php" class="cart-button">View Cart (<?php echo count($_SESSION['cart']); ?>)</a>
    </header>

    <div class="product-container">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <?php 
                // Define a default image in case the product image is not available
                $defaultImage = 'images/default.jpg'; // Path to your default image

                // Check if the product image exists
                if (file_exists($product['image'])) {
                    $imageSrc = $product['image'];
                } else {
                    $imageSrc = $defaultImage; // Use default image if the product image doesn't exist
                }
                ?>
               


                <img src="<?php echo $imageSrc; ?>" alt='<?php echo htmlspecialchars($product['name']); ?>'>
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="price">KSh <?php echo number_format($product['price'], 2); ?></p>

                <form method="POST" action="product.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="buy-button">Add to Cart</button>
                </form>
                <?php
                // Check if the product is already in the cart for display
                $alreadyInCart = false;
                foreach ($_SESSION['cart'] as $item) {
                    if (isset($item['id']) && $item['id'] == $product['id']) {
                        $alreadyInCart = true;
                        break;
                    }
                }
                ?>
                <?php if ($alreadyInCart): ?>
                    <p style="color: red;">This item is already in your cart.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
