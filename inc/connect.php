<?php
// inc/connect.php
$servername = "localhost";
$username   = "root";
$password   = "";                      // XAMPP default password is empty
$dbname     = "house-services-db";    // must match the DB you created in phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
