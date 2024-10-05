delete_item.php
<?php
session_start();

// Check if the item index is set in the URL
if (isset($_GET['item'])) {
    $itemKey = $_GET['item'];

    // Check if the cart exists and the item key is valid
    if (isset($_SESSION['cart'][$itemKey])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$itemKey]);
        
        // Re-index the cart to maintain numerical keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Redirect back to the cart page
header('Location: cart.php');
exit;
?>
