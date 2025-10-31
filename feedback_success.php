<?php
session_start();
include("inc/connect.php");

// Check if feedback ID is passed
if (!isset($_GET['id'])) {
    echo "<h3>No feedback ID provided.</h3>";
    exit;
}

$feedback_id = (int)$_GET['id'];

// Fetch feedback details
$query = $conn->prepare("SELECT f.feedback_id, f.rating, f.comments, f.created_at, u.email 
                         FROM feedback f 
                         JOIN user u ON f.user_id = u.id 
                         WHERE f.feedback_id = ?");
$query->bind_param("i", $feedback_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "<h3>Feedback not found.</h3>";
    exit;
}

$row = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Submitted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link your main CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Small extra style for success message */
        .success-msg {
            background-color: rgba(179, 163, 141, 0.742);
            color: black;
            padding: 15px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .feedback-details {
            font-size: 18px;
            line-height: 1.6;
        }

        .feedback-details strong {
            color: #000;
        }

        .btn-back {
            border: none;
            outline: none;
            height: 50px;
            background: rgba(179, 163, 141);
            font-size: 18px;
            max-width: 150px;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-back:hover {
            background: rgb(228, 199, 159);
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <section>
        <div class="wrapper-feedback">
            <div class="success-msg">
                ✅ Feedback Submitted Successfully!
            </div>

            <div class="feedback-details">
                <p><strong>Feedback ID:</strong> <?php echo htmlspecialchars($row['feedback_id']); ?></p>
                <p><strong>User Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($row['rating']); ?></p>
                <p><strong>Comments:</strong> <?php echo htmlspecialchars($row['comments']); ?></p>
                <p><strong>Submitted At:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
            </div>

            <form action="feedback.php" method="get">
                <input type="submit" class="btn-back" value="Back to Feedback">
            </form>
        </div>
    </section>
</body>
</html>
