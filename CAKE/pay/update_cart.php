update_cart.php
<?php
session_start();

// Check if the cart exists in the session
if (isset($_SESSION['cart']) && isset($_POST['quantity'])) {
    // Loop through the quantity inputs
    foreach ($_POST['quantity'] as $key => $newQuantity) {
        // Ensure the new quantity is a valid positive integer
        $newQuantity = intval($newQuantity); // Convert to integer
        if ($newQuantity > 0 && isset($_SESSION['cart'][$key])) {
            // Update the quantity in the session cart
            $_SESSION['cart'][$key]['quantity'] = $newQuantity; // Update the quantity
        }
    }
}

// Redirect back to the cart page
header('Location: cart.php'); // Adjust the file name if necessary
exit;
?>
