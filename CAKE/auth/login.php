<?php
// Start the session if needed, for example, to handle login state
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #6A0DAD; /* Purple color */
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        form input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #6A0DAD; /* Purple background */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #5A0A94; 
        }

        .signup-link {
            margin-top: 20px;
        }

        .signup-link a {
            color: #6A0DAD;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            color: #5A0A94;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <form action="../include/login.inc.php" method="POST">
            <input type="email" name="email" placeholder="email" required><br> 
            <input type="password" name="pwd" placeholder="Pwd" required><br>
            <input type="submit" value="Login">
        </form>

        <div class="signup-link">
            <h2>Don't have an account?</h2> <a href="signup.php">Sign Up</a>
        </div>
    </div>
</body>
</html>
