<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$servername = "localhost";
$db_username = "Mithran13";
$db_password = "Mithran01";
$dbname = "mmuecho";


$conn = new mysqli($servername, $db_username, $db_password, $dbname);


if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}


$response = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $topic = isset($_POST['topic']) ? $_POST['topic'] : '';

    if ($rating >= 1 && $rating <= 5 && !empty($comment) && !empty($topic)) {
        $stmt = $conn->prepare("INSERT INTO comments (rating, comment, topic) VALUES (?, ?, ?)");
        if ($stmt === false) {
            $response = ["status" => "error", "message" => "Prepare failed: " . $conn->error];
        } else {
            $stmt->bind_param("iss", $rating, $comment, $topic);
            if ($stmt->execute()) {
                $response = ["status" => "success", "message" => "Comment submitted successfully!"];
            } else {
                $response = ["status" => "error", "message" => "Failed to submit comment. Error: " . $stmt->error];
            }
            $stmt->close();
        }
    } else {
        $response = ["status" => "error", "message" => "Please provide a valid rating, comment, and topic."];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMU Echo</title>
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
    margin-bottom: 20px;
    text-align: center;
}

.rating input {
    display: none;
}

.rating label {
    color: #ffffff; 
    font-size: 2rem;
    cursor: pointer;
}

.rating input:checked ~ label {
    color: #ffcc00; 
}

.rating label:hover,
.rating label:hover ~ label {
    color: #cccccc; 
}


textarea {
    width: 100%;
    height: 120px;
    padding: 10px;
    border: 1px solid #3399ff; 
    border-radius: 4px;
    background-color: #ffffff; 
    color: #000000; 
    resize: vertical;
    box-sizing: border-box;
}


select {
    width: 100%;
    padding: 10px;
    border: 1px solid #3399ff; 
    border-radius: 4px;
    background-color: #ffffff; 
    color: #000000; 
    margin-bottom: 20px;
}


button[type="submit"] {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    background-color: #3399ff; 
    color: #ffffff; 
    cursor: pointer;
    font-size: 1rem;
    display: block;
    margin: 20px auto 0;
}

button[type="submit"]:hover {
    background-color: #0073e6; 
}


.popup {
    display: none;
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background: #333333; 
    color: #ffffff; 
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.popup.success {
    background: #2ecc71; 
}

.popup.error {
    background: #e74c3c; 
}


@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    .rating label {
        font-size: 1.5rem;
    }

    textarea {
        height: 100px;
    }
}


.home-button {
    text-align: center; 
    margin-bottom: 20px; 
}


.home-button a {
    display: inline-block; 
    text-decoration: none; 
    padding: 10px 20px; 
    border: none; 
    border-radius: 4px; 
    background-color: #3399ff; 
    color: #ffffff; 
    font-size: 1rem; 
    cursor: pointer; 
    transition: background-color 0.3s ease; 
}


.home-button a:hover {
    background-color: #0073e6; 
}


.home-button a:active {
    background-color: #005bb5; 
}

    </style>
</head>
<body>
<div class="container">
    <div class="home-button">
        <a href="profile.php">Home</a>
    </div>
    <h1>MMU Echo</h1>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div id="popup" class="popup <?php echo htmlspecialchars($response['status']); ?>">
            <?php echo htmlspecialchars($response['message']); ?>
        </div>
    <?php endif; ?>

    <form id="commentForm" method="POST" action="">
        <div class="rating">
            <input type="radio" id="star5" name="rating" value="5">
            <label for="star5" title="5 stars">★</label>
            <input type="radio" id="star4" name="rating" value="4">
            <label for="star4" title="4 stars">★</label>
            <input type="radio" id="star3" name="rating" value="3">
            <label for="star3" title="3 stars">★</label>
            <input type="radio" id="star2" name="rating" value="2">
            <label for="star2" title="2 stars">★</label>
            <input type="radio" id="star1" name="rating" value="1">
            <label for="star1" title="1 star">★</label>
        </div>
        <select name="topic" required>
            <option value="" disabled selected>Select a topic</option>
            <option value="FOM">FOM</option>
            <option value="Course Content">FCI</option>
            <option value="Instructor">FCA</option>
            <option value="Facilities">FAC</option>
            <option value="Other">FOE</option>
            <option value="Other">FCM</option>
            <option value="Other">LECTURERS</option>
        </select>
        <textarea name="comment" placeholder="Enter your comment here..." required></textarea>
        <button type="submit">Submit</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let popup = document.getElementById('popup');
        if (popup) {
            popup.style.display = 'block';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000); 
        }
    });

    
    window.addEventListener('resize', () => {
        let container = document.querySelector('.container');
        if (window.innerWidth <= 768) {
            container.style.padding = '10px';
        } else {
            container.style.padding = '20px';
        }
    });
</script>

</body>
</html>
