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
        $comments[] = ["rating" => "No comments", "comment" => "No comments available for this topic."];
    }
    $stmt->close();
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
        /* Add your existing CSS styles here */
    </style>
</head>
<body>
    <div class="main-content">
        <h2>Comments for <?php echo htmlspecialchars($selectedTopic); ?></h2>
        <div class="comments-section">
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="rating">Rating: <?php echo htmlspecialchars($comment['rating']); ?> ★</div>
                    <div class="text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="profile.php">Back to Dashboard</a>
    </div>
</body>
</html>
