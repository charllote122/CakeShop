<?php
session_start();


// M-Pesa credentials
$consumerKey = 'OpAEKPvyFH0oEOPIE3NBAjaj8gikND2EIWiLoXITfSFqImJf'; 
$consumerSecret = '85pjjTxDQs8KScHFuwGaxLm0EbYGjyulosUBbjJCvP1sZG91XaE2IZj8txRRky9h'; 
$shortcode = '174379'; 
$passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; 
$callbackUrl = 'https://yourdomain.com/callback.php'; 

// Initialize total price
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    // Calculate total price if the cart is not empty
    foreach ($_SESSION['cart'] as $item) {
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
        $totalPrice += $item['price'] * $quantity;
    }
} else {
    die('Your cart is empty. Please add products to the cart.');
}

// Function to get M-Pesa access token
function getAccessToken($consumerKey, $consumerSecret) {
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Error:' . curl_error($curl);
        return null;
    }
    curl_close($curl);

    $json = json_decode($response, true);
    return isset($json['access_token']) ? $json['access_token'] : null;
}

// Function to initiate M-Pesa STK Push
function lipaNaMpesaOnline($phoneNumber, $totalPrice, $accessToken, $shortcode, $passkey, $callbackUrl) {
    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $timestamp = date('YmdHis');
    $password = base64_encode($shortcode . $passkey . $timestamp);

    $payload = array(
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $totalPrice, 
        'PartyA' => $phoneNumber,
        'PartyB' => $shortcode, 
        'PhoneNumber' => $phoneNumber, 
        'CallBackURL' => $callbackUrl, 
        'AccountReference' => 'CakeShop', 
        'TransactionDesc' => 'Payment for cakes'
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken, 'Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

// Handle form submission for payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumber = $_POST['phone_number'];
    $phoneNumber = preg_replace('/^0/', '254', $phoneNumber); // Convert 07XXXXXXXX to 254XXXXXXXXX format

    if (empty($phoneNumber)) {
        die('Phone number is required.');
    }

    // Get M-Pesa access token
    $accessToken = getAccessToken($consumerKey, $consumerSecret);
    if (!$accessToken) {
        die('Failed to get M-Pesa access token.');
    }

    // Initiate M-Pesa payment
    $response = lipaNaMpesaOnline($phoneNumber, $totalPrice, $accessToken, $shortcode, $passkey, $callbackUrl);

    // Check response from M-Pesa
    if (isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {
        echo 'Payment initiated successfully. Please check your phone for the payment prompt.';
    } else {
        echo 'Failed to initiate payment: ' . (isset($response['errorMessage']) ? $response['errorMessage'] : 'Unknown error');
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cake Shop</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 500px;
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

        .total {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            background-color: #4b3d73;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3a2e5f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <p>Total Amount to Pay: KSH <?php echo number_format($totalPrice, 2); ?></p>

        <form method="POST">
            <label for="phone_number">Enter your M-Pesa phone number:</label>
            <input type="text" id="phone_number" name="phone_number" placeholder="e.g. 07XXXXXXXX" required>
            <input type="submit" value="Proceed with Payment">
        </form>
        <div><a href="../index.php">Go to Home</a></div>
    </div>
</body>
</html>
