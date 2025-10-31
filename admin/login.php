<?php
session_start();
include('../inc/connect.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ✅ Check admin table
    $sql = "SELECT * FROM admin WHERE admin_email='$email' AND admin_password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        header("Location: dashboard-admin.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Keep same look as feedback.php -->
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: url('../images/bg.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    section {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .login-wrapper {
      background-color: rgba(179, 163, 141, 0.85);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
      text-align: center;
      max-width: 400px;
      width: 90%;
    }

    .login-wrapper h2 {
      color: #000;
      margin-bottom: 20px;
    }

    .login-wrapper input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    .login-wrapper input[type="submit"] {
      background-color: rgba(179, 163, 141);
      border: none;
      color: #000;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .login-wrapper input[type="submit"]:hover {
      background: rgb(228, 199, 159);
    }

    .error-msg {
      color: red;
      font-weight: bold;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <!-- ❌ Removed header include -->
  <!-- <?php include('../header.php'); ?> -->

  <section>
    <div class="login-wrapper">
      <h2>Admin Login</h2>

      <?php if (!empty($error)): ?>
        <p class="error-msg"><?php echo $error; ?></p>
      <?php endif; ?>

      <form method="post">
        <input type="text" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" name="login" value="Login">
      </form>
    </div>
  </section>
</body>
</html>
