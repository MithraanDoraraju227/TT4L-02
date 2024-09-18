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

$selectedTopic = isset($_GET['topic']) ? $_GET['topic'] : '';

// Fetch comments for the selected topic
$comments = [];
if ($selectedTopic) {
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
    $stmt->close();
} else {
    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Comments for <?php echo htmlspecialchars($selectedTopic); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        :root {
            --primary-color: #000000; /* Black */
            --secondary-color: #0056b3; /* Blue */
            --text-light: #e0e0e0; /* Light Text */
            --white: #ffffff; /* White */
            --border-radius: 10px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--primary-color);
            color: var(--text-light);
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            padding: 20px;
        }

        h2 {
            color: var(--secondary-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .comments-section {
            max-width: 800px;
            margin: 0 auto;
        }

        .comment {
            background-color: var(--primary-color);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 15px;
            box-shadow: var(--box-shadow);
            transition: background-color 0.3s, transform 0.3s;
        }

        .comment:hover {
            background-color: var(--secondary-color);
            transform: scale(1.02);
        }

        .rating {
            font-size: 1.25rem;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }

        .text {
            font-size: 1rem;
            color: var(--text-light);
        }

        .topic {
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--white);
        }

        .topic a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .topic a:hover {
            color: var(--white);
        }
    </style>
</head>
<body>
    <h2>All Comments for <?php echo htmlspecialchars($selectedTopic); ?></h2>
    <div class="comments-section">
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <div class="rating">Rating: <?php echo htmlspecialchars($comment['rating']); ?> ★</div>
                <div class="text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                <div class="topic">
                    <a href="topic_comments.php?topic=<?php echo urlencode($selectedTopic); ?>">
                        View comments for <?php echo htmlspecialchars($selectedTopic); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
