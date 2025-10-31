<?php
session_start();
include('inc/connect.php'); // must set $conn (mysqli)

// If user is not logged in, redirect to login
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    // optional flash message before redirect
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Use prepared statement to fetch user safely
$sql = "SELECT id, firstname, lastname, email, address FROM `user` WHERE email = ? LIMIT 1";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
} else {
    // DB prepare failed
    die("Database error: " . htmlspecialchars($conn->error));
}

if (!$user) {
    // If session email exists but user row not found, destroy session and force login
    session_unset();
    session_destroy();
    echo "<p>User not found. Please <a href='login.php'>login</a> again.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head id="head-dashboard">
    <title>Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="js/external.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/icon.png">
</head>

<body id="body-dashboard">
    <?php include('header.php'); ?>

    <section>
        <article class="box">
            <header>
                <h2 class="profile">PROFILE USER</h2>
            </header>
            <div class="whitebox">
                <form id="myForm" name="myForm" method="post" action="processdataPHP/sendupdateprofile.php" onsubmit="return validateForm()">
                    <table>
                        <tr>
                            <td>First Name:</td>
                            <td><input required="required" type="text" name="fname" id="fname" value="<?= htmlspecialchars($user['firstname']) ?>" autofocus=""></td>
                        </tr>
                        <tr>
                            <td>Last Name:</td>
                            <td><input required="required" type="text" name="lname" id="lname" value="<?= htmlspecialchars($user['lastname']) ?>"></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input required="required" type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"></td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td><input required="required" type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']) ?>"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: left;">
                                <a href="updatepass.php"><input style="font-weight: bold;" type="button" name="btnchangepass" id="btnchangepass" value="Change Password"></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <!-- include hidden field with user id to allow update -->
                                <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                                <input class="btn" type="submit" name="submit" value="Save">
                                <a href="services.php"><input type="button" class="btn" name="back" value="Back"></a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </article>
    </section>
</body>
</html>
<?php
$conn->close();
?>
