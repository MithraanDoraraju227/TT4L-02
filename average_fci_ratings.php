<?php
session_start(); // Start the session to access session variables

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$db_username = "Mithran13";
$db_password = "Mithran01";
$dbname = "mmuecho";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the average rating variable
$averageRating = 0;
$totalRatings = 0;

$sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS count FROM comments WHERE topic = 'FCI'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $averageRating = $row['avg_rating'];
    $totalRatings = $row['count'];
} else {
    $averageRating = 'No ratings';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Average FCI Ratings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000000; 
            color: #ffffff; 
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #003366; 
            border-radius: 8px;
        }
        h1 {
            color: #ffffff; 
            text-align: center;
            margin-bottom: 20px;
        }
        .rating {
            font-size: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Average FCI Ratings</h1>
        <div class="rating">
            <?php if ($totalRatings > 0): ?>
                Average Rating for FCI: <?php echo number_format($averageRating, 2); ?> / 5
            <?php else: ?>
                No ratings available for FCI.
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
