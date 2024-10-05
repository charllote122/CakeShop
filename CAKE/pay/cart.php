<?php
session_start();

// Initialize total price
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    // Calculate total price if the cart is not empty
    foreach ($_SESSION['cart'] as $item) {
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
        $totalPrice += $item['price'] * $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Cake Shop</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            font-size: 2.5em;
            color: #4a4a4a;
            text-align: center;
        }

        .cart {
            margin-top: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .cart ul {
            list-style-type: none;
            padding: 0;
        }

        .cart li {
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart img {
            width: 100px;
            height: auto;
            margin-right: 15px;
        }

        .total {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 20px;
        }

        input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-left: 10px;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }

        button {
            padding: 10px 15px;
            background-color: #4b3d73;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #3a2e5f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Cart</h1>

        <div class="cart">
            <form method="POST" action="update_cart.php"> 
                <ul>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                            <?php $quantity = isset($item['quantity']) ? $item['quantity'] : 1; ?>
                            <li>
                                <?php if (isset($item['image']) && !empty($item['image']) && file_exists($item['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
                                <?php else: ?>
                                    <span>No image available</span> <!-- Fallback if image does not exist -->
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($item['name']); ?> - KSH <?php echo htmlspecialchars($item['price']); ?></span>
                                <input type="number" name="quantity[<?php echo $key; ?>]" value="<?php echo $quantity; ?>" min="1">
                                <a href="delete_item.php?item=<?php echo $key; ?>" style="color: red;">Delete</a> <!-- Delete link -->
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <span>Your cart is empty.</span>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if (!empty($_SESSION['cart'])): ?>
                    <div class="total">
                        Total: KSH <?php echo htmlspecialchars($totalPrice); ?>
                    </div>
                    <button type="submit">Update Cart</button> <!-- Button to update cart -->
                <?php endif; ?>
            </form>

            <a href="product.php">Continue Shopping</a><br>
            <a href="payment.php">Proceed to Checkout</a>
        </div>
    </div>
</body>
</html>
