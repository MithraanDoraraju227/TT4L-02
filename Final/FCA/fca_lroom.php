<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "mmu echo";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected topic from the URL
$selectedTopic = isset($_GET['topic']) ? $_GET['topic'] : 'FCA'; // Default to 'FCA'

// Fetch comments based on the selected topic
$comments = [];
$sql = "SELECT rating, comment FROM comments WHERE topic = 'FCA' AND sector = 'Lecture Rooms' ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
} else {
    $comments[] = ["rating" => "No rating", "comment" => "No comments available for this topic"];
}

// Calculate average rating for the selected topic
$averageRating = 0;
$sql = "SELECT AVG(rating) AS avg_rating FROM comments WHERE topic = 'FCA' AND sector ='Lecture Rooms' ";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
    $averageRating = $row['avg_rating'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>EchoMMU: FCA</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Internal CSS if needed (or use external style.css) */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000000;
            color: #e0e0e0;
            padding: 20px;
        }

        ul {
            list-style-type: none;
            background-color: #0056b3;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        ul li {
            display: inline-block;
            position: relative;
        }

        ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            transition: 0.3s;
        }

        ul li a:hover {
            background-color: #fff;
            color: #0056b3;
            border-radius: 5px;
        }

        ul .dropdown {
            display: none;
            position: absolute;
            background-color: #0056b3;
            border-radius: 5px;
            z-index: 1;
        }

        ul li:hover .dropdown {
            display: block;
        }

        ul .dropdown li {
            display: block;
            padding: 10px;
        }

        ul .dropdown li a {
            display: block;
            color: #fff;
        }

        .icon {
            flex-grow: 1;
        }

        .logo {
            color: #ffffff;
            font-size: 1.5rem;
            margin-left: 20px;
        }

        .navbar a {
            text-decoration: none;
            color: #fff;
        }

        .comments-section {
            max-width: 800px;
            margin: 0 auto;
        }

        .comment {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .rating {
            color: #0056b3;
            font-size: 1.5rem;
        }

        .text {
            color: #000;
            margin-top: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <ul>
        <div class="icon">
            <h2 class="logo">FCA - Lecture Rooms</h2>
        </div>

        <!-- Home button -->
        <li><a href="profile.php">Home</a></li>

        <li>
            <a href="#">Sector/Category</a>
            <ul class="dropdown">
                <li><a href="fca_croom.php">Classrooms</a></li>
                <li><a href="fca_lroom.php">Lecture Room</a></li>
                <li><a href="fca_toilet.php">Toilets</a></li>
                <li><a href="fca_lect.php">Lecturers</a></li>
                <li><a href="fca_syl.php">Syllabus</a></li>
                <li><a href="fca_admin.php">Administration</a></li>
            </ul>
        </li>
    </ul>

    <!-- Comments Section -->
    <h2>All Comments for <?php echo htmlspecialchars($selectedTopic); ?></h2>

    <div class="average-rating" style="color: white;">
        <?php if ($averageRating): ?>
            Average Rating FCA - Lecture Rooms: <?php echo number_format($averageRating, 2); ?> / 5
        <?php else: ?>
            No ratings available for FCA - Lecture Rooms.
        <?php endif; ?>
    </div>  

    <div class="comments-section">
        <?php foreach ($comments as $comment): ?>
            
            <div class="comment">
                <div class="rating">Rating: <?php echo htmlspecialchars($comment['rating']); ?> ★</div>
                <div class="text"><?php echo htmlspecialchars($comment['comment']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
