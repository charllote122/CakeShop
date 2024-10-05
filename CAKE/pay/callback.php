<?php
session_start();

// Read the input from the callback
$data = json_decode(file_get_contents('php://input'), true);

// Check if the payment was successful
if ($data['Body']['stkCallback']['ResultCode'] == '0') {
    // Payment successful
    $amount = $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
    $transactionId = $data['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];

    // Clear the cart after successful payment
    unset($_SESSION['cart']);
    header('Location: success.php?amount=' . $amount . '&transaction_id=' . $transactionId);
    exit;
} else {
    // Payment failed
    echo "Payment failed. Error: " . $data['Body']['stkCallback']['ResultDesc'];
}
