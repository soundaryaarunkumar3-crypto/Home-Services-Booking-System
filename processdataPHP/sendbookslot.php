<?php
session_start();
include("../inc/connect.php");

// ensure user is logged in
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    echo "<script>alert('Please login first'); window.location='../login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

// get user id from DB (assuming primary key column is `id`)
$stmt = $conn->prepare("SELECT id, address FROM `user` WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User not found'); window.location='../login.php';</script>";
    exit;
}
$row = $result->fetch_assoc();
$user_id = $row['id'];           // use 'id' as PK (update if your DB uses different name)
$address_from_profile = $row['address'];

// obtain POST inputs safely and validate
$bookservice = isset($_POST['bookservice']) ? trim($_POST['bookservice']) : '';
$date = isset($_POST['datebook']) ? trim($_POST['datebook']) : '';
$timeservice = isset($_POST['timeservice']) ? trim($_POST['timeservice']) : '';
$descbook = isset($_POST['descbook']) ? trim($_POST['descbook']) : '';

if ($date === '' || $timeservice === '' || $bookservice === '') {
    echo "<script>alert('Please fill required fields'); window.history.back();</script>";
    exit;
}

// If address field present in form else use profile address
$address = isset($_POST['address']) && trim($_POST['address']) !== '' ? trim($_POST['address']) : $address_from_profile;

// Normalize status string
$status = 'Pending';

// Check existing appointment at same date/time
$avail_stmt = $conn->prepare("SELECT appointment_id FROM appointment WHERE booking_date = ? AND booking_time = ? AND status = 'Pending'");
$avail_stmt->bind_param("ss", $date, $timeservice);
$avail_stmt->execute();
$avail_res = $avail_stmt->get_result();

if ($avail_res->num_rows > 0) {
    echo "<script>alert('This time slot is already booked. Please choose another.'); window.history.back();</script>";
    exit;
}

// Start transaction to ensure both inserts succeed
$conn->begin_transaction();

try {
    // Insert into appointment (using the DB column names from your schema)
    $insert_appt = $conn->prepare("
        INSERT INTO appointment (user_id, booking_date, booking_time, notes, status, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $insert_appt->bind_param("issss", $user_id, $date, $timeservice, $descbook, $status);
    $insert_appt->execute();

    if ($insert_appt->affected_rows === 0) {
        throw new Exception("Failed to create appointment");
    }

    // get the new appointment id
    $appointment_id = $conn->insert_id;

    // If you also have appointment_service mapping table, insert mapping(s)
    // $bookservice might be a single id or an array — adjust accordingly.
    // Here I assume a single service id integer:
    $service_id = (int)$bookservice;

    $insert_map = $conn->prepare("INSERT INTO appointment_service (appointment_id, service_id) VALUES (?, ?)");
    $insert_map->bind_param("ii", $appointment_id, $service_id);
    $insert_map->execute();

    $conn->commit();

    echo "<script>alert('Booking successful'); window.location='../dashboard.php';</script>";
    exit;

} catch (Exception $e) {
    $conn->rollback();
    // You may want to log $e->getMessage() to a file in production
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit;
}
?>
