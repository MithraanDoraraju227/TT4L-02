<?php

$servername = "localhost";
$db_username = "Mithran13";
$db_password = "Mithran01";
$dbname = "mmuecho";


$conn = new mysqli($servername, $db_username, $db_password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    
    if ($stmt->execute()) {
        $message = "Registration successful!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    
    $stmt->close();

    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body, h1, h2, p {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0; 
            color: #333; 
        }

        .container {
            width: 80%;
            margin: 0 auto;
            overflow: hidden;
        }

        .register {
            padding: 2rem 0;
            text-align: center;
            background: #e6f2ff; 
        }

        .register h2 {
            color: #003366; 
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background: #ffffff; 
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333; 
        }

        form input {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #007bff; 
            background: #f9f9f9; 
            color: #333; 
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 1rem; /* Space between buttons */
        }

        form button, .home-button {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background: #007bff; /* Same color for both buttons */
            border: none;
        }

        form button:hover, .home-button:hover {
            background: #0056b3; /* Darker shade on hover */
        }

        .message {
            margin-top: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <section class="register">
        <div class="container">
            <h2>Register</h2>
            <form action="register.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <div class="button-group">
                    <button type="submit">Register</button>
                    <a href="FrontPage.html" class="home-button">Home</a>
                </div>
            </form>
            <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        </div>
    </section>
</body>
</html>
