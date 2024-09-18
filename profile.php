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

$selectedTopic = isset($_POST['topic']) ? $_POST['topic'] : '';

// Fetch comments
$comments = [];
$sql = "SELECT rating, comment, topic FROM comments";
if ($selectedTopic) {
    $stmt = $conn->prepare("SELECT rating, comment FROM comments WHERE topic = ?");
    $stmt->bind_param("s", $selectedTopic);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
} else {
    $comments[] = ["rating" => "No comments", "comment" => "No comments available"];
}

// Calculate average rating for the selected topic
$averageRating = 0;
if ($selectedTopic) {
    $stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM comments WHERE topic = ?");
    $stmt->bind_param("s", $selectedTopic);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $averageRating = $row['avg_rating'];
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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <style>
          @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    :root {
        --primary-color: #000000;
        --primary-color-light: #1a1a1a;
        --primary-color-extra-light: #333333;
        --secondary-color: #0056b3;
        --secondary-color-dark: #003d80;
        --text-light: #e0e0e0;
        --white: #ffffff;
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
        background-color: var(--primary-color-light);
        color: var(--text-light);
        display: flex;
        min-height: 100vh;
        margin: 0;
    }

    .sidebar {
        width: 250px;
        background-color: var(--primary-color);
        display: flex;
        flex-direction: column;
        padding: 2rem 1rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        color: var(--white);
    }

    .sidebar .logo-container {
        text-align: center;
        margin-bottom: 2rem;
    }

    .sidebar .logo-container img.logo {
        width: 200px;
        height: auto;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .sidebar .logo-container .logo-text {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--white);
        margin-top: 10px;
    }

    .sidebar ul {
        list-style: none;
    }

    .sidebar ul li {
        margin: 1rem 0;
    }

    .sidebar ul li a {
        color: var(--white);
        text-decoration: none;
        font-size: 1rem;
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius);
        transition: 0.3s;
    }

    .sidebar ul li a:hover {
        background-color: var(--primary-color-extra-light);
        transform: scale(1.05);
    }

    .sidebar ul li.active a {
        background-color: var(--secondary-color);
        color: var(--white);
    }

    .main-content {
        flex-grow: 1;
        padding: 2rem;
        background-color: var(--white);
        color: black;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        max-width: 800px;
        margin: 50px auto;
    }

    .main-content h2 {
        color: var(--secondary-color);
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border-bottom: 2px solid var(--primary-color-extra-light);
        padding-bottom: 0.5rem;
    }

    .welcome-message {
        padding: 20px;
        background: linear-gradient(135deg, var(--secondary-color), var(--secondary-color-dark));
        border-radius: var(--border-radius);
        color: var(--white);
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 20px;
        box-shadow: var(--box-shadow);
    }

    .welcome-message h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: var(--white);
    }

    .filter-bar {
        margin-bottom: 20px;
    }

    .filter-bar label {
        font-weight: 600;
        margin-right: 10px;
    }

    .filter-bar select {
        padding: 10px 15px;
        border: 1px solid var(--secondary-color);
        border-radius: var(--border-radius);
        background-color: var(--white);
        color: var(--primary-color);
        font-size: 1rem;
        box-shadow: var(--box-shadow);
        transition: background-color 0.3s, border-color 0.3s;
    }

    .filter-bar select:hover {
        background-color: var(--primary-color-extra-light);
        border-color: var(--secondary-color-dark);
    }

    .average-rating {
        margin-top: 20px;
        padding: 15px;
        border-radius: var(--border-radius);
        background-color: var(--secondary-color);
        color: var(--white);
        font-size: 1.25rem;
        text-align: center;
        box-shadow: var(--box-shadow);
    }

    .comments-section {
        margin-top: 20px;
    }

    .comments-section h2 {
        color: var(--secondary-color);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .comments-section .comment {
        background-color: var(--primary-color-extra-light);
        padding: 15px;
        border-radius: var(--border-radius);
        margin-bottom: 15px;
        box-shadow: var(--box-shadow);
        transition: background-color 0.3s, transform 0.3s;
    }

    .comments-section .comment:hover {
        background-color: var(--primary-color);
        transform: scale(1.02);
    }

    .comments-section .comment .rating {
        font-size: 1.25rem;
        color: var(--secondary-color);
        margin-bottom: 5px;
    }

    .comments-section .comment .text {
        font-size: 1rem;
        color: var(--text-light);
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            padding: 1rem;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 1000;
            overflow: auto;
            transition: transform 0.3s ease;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        .sidebar ul li {
            margin: 0.5rem 0;
        }

        .sidebar ul li a {
            padding: 0.5rem 1rem;
        }

        .main-content {
            padding: 1rem;
            margin: 0;
        }
    }

    @media (max-width: 600px) {
        .sidebar {
            display: flex;
            justify-content: space-around;
            flex-direction: row;
            height: auto;
            position: static;
        }

        .sidebar ul {
            display: flex;
            justify-content: space-around;
            width: 100%;
        }

        .sidebar ul li {
            margin: 0;
        }

        .sidebar ul li a {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>

</head>
<body>
    <div class="sidebar" id="sidebar">
        <h1 class="logo-container">
            <img src="https://cdn.dribbble.com/users/959248/screenshots/18277942/media/112c109026356162c4acb46a49627971.jpg" alt="Logo" class="logo">
            <div class="logo-text">MMU ECHO</div>
        </h1>
        
        <ul>
            <li class="active"><a href="profile.php"><i class="ri-dashboard-line"></i>Dashboard</a></li>
            <li><a href="user_comment.php"><i class="ri-wallet-line"></i>My Comments</a></li>
            <li><a href="feedback_comments.php"><i class="ri-wallet-line"></i>Add Comments</a></li>
            <li><a href="FrontPage.html"><i class="ri-wallet-line"></i>Log Out</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2>Dashboard</h2>
        <div class="welcome-message">
            <?php if (isset($username) && $username !== null): ?>
                Welcome, <?php echo htmlspecialchars($username); ?>!
            <?php else: ?>
                Welcome!
            <?php endif; ?>
        </div>
    
        <form id="commentForm" method="GET" action="comments.php">
    <div class="filter-bar">
        <label for="topic">Filter by Topic:</label>
        <select name="topic" id="topic" onchange="this.form.submit()">
            <option value="">--Select a topic--</option>
            <option value="Other" <?php if ($selectedTopic === 'Other') echo 'selected'; ?>>Other</option>
            <option value="FCI" <?php if ($selectedTopic === 'FCI') echo 'selected'; ?>>FCI</option>
            <option value="FOM" <?php if ($selectedTopic === 'FOM') echo 'selected'; ?>>FOM</option>
            <option value="FCA" <?php if ($selectedTopic === 'FCA') echo 'selected'; ?>>FCA</option>
            <option value="FAC" <?php if ($selectedTopic === 'FAC') echo 'selected'; ?>>FAC</option>
            <option value="FOE" <?php if ($selectedTopic === 'FOE') echo 'selected'; ?>>FOE</option>
            <option value="FCM" <?php if ($selectedTopic === 'FCM') echo 'selected'; ?>>FCM</option>
            <option value="LECTURERS" <?php if ($selectedTopic === 'LECTURERS') echo 'selected'; ?>>LECTURERS</option>
        </select>
    </div>
</form>



        <?php if ($selectedTopic): ?>
            <div class="average-rating">
                <?php if ($averageRating): ?>
                    Average Rating for <?php echo htmlspecialchars($selectedTopic); ?>: <?php echo number_format($averageRating, 2); ?> / 5
                <?php else: ?>
                    No ratings available for <?php echo htmlspecialchars($selectedTopic); ?>.
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <h2>All Comments</h2>
        <div class="comments-section">
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="rating">Rating: <?php echo htmlspecialchars($comment['rating']); ?> ★</div>
                    <div class="text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.createElement('button');
            toggleButton.style.position = 'fixed';
            toggleButton.style.top = '10px';
            toggleButton.style.left = '10px';
            toggleButton.style.backgroundColor = 'var(--primary-color)';
            toggleButton.style.color = 'var(--white)';
            toggleButton.style.border = 'none';
            toggleButton.style.padding = '10px';
            toggleButton.style.borderRadius = '5px';
            toggleButton.style.cursor = 'pointer';
            document.body.appendChild(toggleButton);

            toggleButton.addEventListener('click', function () {
                sidebar.classList.toggle('closed');
            });
        });
    </script>
</body>
</html>
