<?php
// processdataPHP/sendregister.php
include("../inc/connect.php");

// sanitize POST
$fn = isset($_POST['fname']) ? trim($_POST['fname']) : '';
$ln = isset($_POST['lname']) ? trim($_POST['lname']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$psw = isset($_POST['mypassword']) ? $_POST['mypassword'] : '';

if ($fn === '' || $email === '' || $psw === '') {
    echo "<script>alert('Please fill required fields.'); window.history.back();</script>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format'); window.history.back();</script>";
    exit;
}

// hash password
$hashpwd = password_hash($psw, PASSWORD_BCRYPT);

// check if email exists
$check = $conn->prepare("SELECT id FROM `user` WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "<script>alert('Email already registered'); window.history.back();</script>";
    exit;
}

// insert user
$stmt = $conn->prepare("INSERT INTO `user` (firstname, lastname, address, email, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$address = isset($_POST['address']) ? trim($_POST['address']) : '-';
$stmt->bind_param("sssss", $fn, $ln, $address, $email, $hashpwd);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful. Please login.'); window.location='../login.php';</script>";
} else {
    echo "<script>alert('Registration failed.'); window.history.back();</script>";
}
?>
