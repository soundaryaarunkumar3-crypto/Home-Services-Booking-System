<?php
include('inc/connect.php');

$admin_name = "Admin";
$admin_email = "admin@example.com";
$plain = "Admin@123";
$hash = password_hash($plain, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO admin (admin_name, admin_email, admin_password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $admin_name, $admin_email, $hash);
if ($stmt->execute()) {
    echo "Admin created: $admin_email with password $plain";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
