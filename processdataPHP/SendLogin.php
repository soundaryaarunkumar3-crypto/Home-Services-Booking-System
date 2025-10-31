<?php
// processdataPHP/SendLogin.php
session_start();
include('../inc/connect.php');

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$passwd = isset($_POST['mypassword']) ? $_POST['mypassword'] : '';

if ($email === '' || $passwd === '') {
    echo "<script>alert('Please provide email and password'); window.location='../login.php';</script>";
    exit;
}

// 1) Try user
$stmt = $conn->prepare("SELECT id, firstname, email, password FROM user WHERE email = ?");
if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($passwd, $row['password'])) {
            // success
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email']   = $row['email'];
            $_SESSION['user_name'] = $row['firstname'];
            header("Location: ../dashboard.php");
            exit;
        }
    }
    $stmt->close();
}

// 2) Try admin (expects hashed admin_password)
$stmt2 = $conn->prepare("SELECT admin_id, admin_name, admin_email, admin_password FROM admin WHERE admin_email = ?");
if ($stmt2) {
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    if ($res2 && $res2->num_rows === 1) {
        $row2 = $res2->fetch_assoc();
        if (password_verify($passwd, $row2['admin_password'])) {
            $_SESSION['admin_id'] = $row2['admin_id'];
            $_SESSION['admin_email'] = $row2['admin_email'];
            $_SESSION['admin_name'] = $row2['admin_name'];
            header("Location: ../admin/dashboard-admin.php");
            exit;
        }
    }
    $stmt2->close();
}

// no match
echo "<script>alert('Invalid email or password. Please try again.'); window.location='../login.php';</script>";
$conn->close();
exit;
?>
