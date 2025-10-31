<?php
session_start();
include('../inc/connect.php');

// Validate POST input
if (!isset($_POST['appointment_id']) || !isset($_POST['status'])) {
    die("Invalid request. Missing appointment_id or status.");
}

$app_id = (int) $_POST['appointment_id'];
$status = trim($_POST['status']);

// Use a prepared statement (safe and fast)
$stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE appointment_id = ?");
$stmt->bind_param("si", $status, $app_id);

if ($stmt->execute()) {
    echo "<script>alert('Status updated successfully!');</script>";
    echo "<meta http-equiv='refresh' content='0.5;URL=../admin/dashboard-admin.php'>";
} else {
    echo "<p>Error updating record: " . htmlspecialchars($stmt->error) . "</p>";
}

$stmt->close();
$conn->close();
?>
