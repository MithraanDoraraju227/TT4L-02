<?php
session_start(); 

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = null; 
}

$servername = "localhost";
$db_username = "Mithran13";
$db_password = "Mithran01";
$dbname = "mmuecho";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Filter for "FOM"
$selectedTopic = 'FOM';

// Fetch comments for "FOM"
$comments = [];
$stmt = $conn->prepare("SELECT rating, comment FROM comments WHERE topic = ?");
$stmt->bind_param("s", $selectedTopic);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
} else {
    $comments[] = ["rating" => "No comments", "comment" => "No comments available"];
}

// Calculate average rating for "FOM"
$averageRating = 0;
$stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM comments WHERE topic = ?");
$stmt->bind_param("s", $selectedTopic);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $averageRating = $row['avg_rating'];
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FOM Ratings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-bar {
            margin-bottom: 20px;
        }

        .average-rating {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
            background-color: #007bff;
            color: #fff;
            font-size: 1.25rem;
            text-align: center;
        }

        .comments-section {
            margin-top: 20px;
        }

        .comments-section h2 {
            color: #007bff;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .comments-section .comment {
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comments-section .comment .rating {
            font-size: 1.25rem;
            color: #007bff;
        }

        .comments-section .comment .text {
            font-size: 1rem;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>FOM Ratings</h1>

        <div class="average-rating">
            Average Rating for FOM: <?php echo number_format($averageRating, 2); ?> / 5
        </div>

        <div class="comments-section">
            <h2>Comments for FOM</h2>
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="rating">Rating: <?php echo htmlspecialchars($comment['rating']); ?> / 5</div>
                        <div class="text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>No comments available.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
