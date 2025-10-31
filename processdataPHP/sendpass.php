<?php
include("../inc/connect.php");

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$newpwd = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';

if ($email === '' || $newpwd === '') {
    echo "<script>alert('Provide email and new password'); window.history.back();</script>";
    exit;
}

$hashpwd = password_hash($newpwd, PASSWORD_BCRYPT);

$update = $conn->prepare("UPDATE `user` SET password = ? WHERE email = ?");
$update->bind_param("ss", $hashpwd, $email);
if ($update->execute()) {
    echo "<script>alert('Password updated'); window.location='../login.php';</script>";
} else {
    echo "<script>alert('Failed to update password'); window.history.back();</script>";
}
?>
