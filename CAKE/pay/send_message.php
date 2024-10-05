<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $host = 'localhost'; // Your database host
    $db = 'cake'; // Your database name
    $user = 'root'; // Your database username
    $pass = ''; // Your database password

    // Create connection
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and bind
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)");
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':message', $_POST['message']);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Message sent successfully!'); window.location.href='contact.html';</script>";
        } else {
            echo "<script>alert('Failed to send message. Please try again.'); window.location.href='contact.html';</script>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $pdo = null;
}
?>
