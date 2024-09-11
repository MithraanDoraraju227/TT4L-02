<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$username = $_SESSION['username'];

$servername = "localhost";
$db_username = "Mithran13";
$db_password = "Mithran01";
$dbname = "mmuecho";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT rating, comment, topic FROM comments WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
} else {
    $comments[] = ["rating" => "No comments", "comment" => "You have not left any comments."];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Comments</title>
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
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
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
            margin-left: 270px; 
            width: calc(100% - 270px);
        }

        .main-content h2 {
            color: var(--secondary-color);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color-extra-light);
            padding-bottom: 0.5rem;
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
            padding: 10px;
            border-radius: var(--border-radius);
            margin-bottom: 10px;
            box-shadow: var(--box-shadow);
        }

        .comments-section .comment .rating {
            font-size: 1.25rem;
            color: var(--secondary-color);
        }

        .comments-section .comment .text {
            font-size: 1rem;
            color: var(--text-light);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1 class="logo-container">
            <img src="https://cdn.dribbble.com/users/959248/screenshots/18277942/media/112c109026356162c4acb46a49627971.jpg" alt="Logo" class="logo">
            <div class="logo-text">MMU ECHO</div>
        </h1>
        
        <ul>
            <li><a href="profile.php"><i class="ri-dashboard-line"></i>Dashboard</a></li>
            <li class="active"><a href="user_comment.php"><i class="ri-user-line"></i>My Comments</a></li>
            <li><a href="feedback_comments.php"><i class="ri-wallet-line"></i>Add Comments</a></li>
            <li><a href="FrontPage.html"><i class="ri-wallet-line"></i>Log Out</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2>My Comments</h2>
        <div class="comments-section">
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="rating">Rating: <?php echo htmlspecialchars($comment['rating']); ?> ★</div>
                    <div class="text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
