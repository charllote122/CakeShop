<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $email = trim($_POST['email']); 
    $pwd = trim($_POST['pwd']);

    // Basic validation
    if (empty($email) || empty($pwd)) {
        die("Email and password are required.");
    }

    try {
        require_once 'model.php'; // Ensure this file sets up the $pdo connection
        
        // Prepare SQL statement to fetch user data based on email
        $query = "SELECT * FROM users WHERE email = :email"; 
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // Check if any user is found
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password (assuming it's plain text, replace this with password_verify() if hashed)
            if ($pwd === $user['pwd']) { // Check if entered password matches the stored one
                // Successful login
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                $_SESSION['username'] = $user['username']; // Optional: store username

                // Redirect to payment page after successful login
                header("Location: ../index.php");
                exit(); // Prevent further script execution
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "Email not found.";
        }

        // Clean up
        $stmt = null;
        $pdo = null;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle the case when the form is not submitted correctly
    die("Invalid request.");
}
?>
