<?php
session_start();
include("../inc/connect.php");

// Accept only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../feedback.php");
    exit;
}

// Ensure user logged in
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    $_SESSION['flash_error'] = "Please login to send feedback.";
    header("Location: ../login.php");
    exit;
}

$email = trim($_SESSION['email']);

// Find user id in `user` table
$stmt = $conn->prepare("SELECT id FROM `user` WHERE LOWER(TRIM(email)) = ? LIMIT 1");
$lower = mb_strtolower($email);
$stmt->bind_param("s", $lower);
$stmt->execute();
$stmt->bind_result($user_id);
$found = $stmt->fetch();
$stmt->close();

if (!$found || !$user_id) {
    $_SESSION['flash_error'] = "User not found. Please login.";
    header("Location: ../login.php");
    exit;
}

// Validate inputs
$rate = isset($_POST['rate']) ? (int)$_POST['rate'] : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($message === '') {
    $_SESSION['flash_error'] = "Please enter a message.";
    header("Location: ../feedback.php");
    exit;
}
$rate = max(1, min(5, $rate));

// Insert into feedback table
$insert = $conn->prepare("INSERT INTO `feedback` (`user_id`, `service_id`, `rating`, `comments`, `created_at`) VALUES (?, NULL, ?, ?, NOW())");
$insert->bind_param("iis", $user_id, $rate, $message);
$ok = $insert->execute();
$feedback_id = $conn->insert_id; // get inserted ID
$insert->close();
$conn->close();

if ($ok) {
    header("Location: ../feedback_success.php?id=" . $feedback_id);
    exit;
} else {
    $_SESSION['flash_error'] = "Could not save feedback. Try again later.";
    header("Location: ../feedback.php");
    exit;
}
?>
