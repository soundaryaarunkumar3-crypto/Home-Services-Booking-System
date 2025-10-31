<?php
session_start();
include('inc/connect.php'); // must set $conn (mysqli)

// development: enable errors temporarily if needed
// ini_set('display_errors', 1); error_reporting(E_ALL);

// 1) Ensure user is logged in
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
$email = $_SESSION['email'];

// 2) Get logged-in user's id safely using prepared stmt
$user_id = null;
$stmt = $conn->prepare("SELECT id FROM `user` WHERE email = ?");
if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $userRow = $res->fetch_assoc();
        $user_id = (int)$userRow['id'];
    } else {
        echo "<p>User not found. Please login again.</p>";
        exit();
    }
    $stmt->close();
} else {
    echo "Database error: failed to prepare user query: " . htmlspecialchars($conn->error);
    exit();
}

// helper function to run booking query (returns array or ['error'=>...])
function getBookings($conn, $user_id, $status) {
    // Use booking_date and booking_time (your DB columns)
    // Collect service titles (if multiple) using GROUP_CONCAT
    $sql = "
        SELECT 
            a.appointment_id,
            a.booking_date,
            a.booking_time,
            a.notes,
            a.status,
            COALESCE(GROUP_CONCAT(DISTINCT s.title SEPARATOR ', '), '') AS service_name
        FROM appointment a
        LEFT JOIN appointment_service aps ON a.appointment_id = aps.appointment_id
        LEFT JOIN services s ON s.service_id = aps.service_id
        WHERE a.user_id = ? AND LOWER(a.status) = LOWER(?)
        GROUP BY a.appointment_id
        ORDER BY a.booking_date DESC, a.booking_time DESC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['error' => $conn->error];
    }

    // bind: user_id (int), status (string)
    $stmt->bind_param("is", $user_id, $status);

    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return ['error' => $err];
    }

    $result = $stmt->get_result();
    $rows = [];
    if ($result) {
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
    }
    $stmt->close();
    return $rows;
}

// fetch bookings (use status values that exist in your DB; case-insensitive match used)
$pending = getBookings($conn, $user_id, 'pending');
$completed = getBookings($conn, $user_id, 'completed');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php include('header.php'); ?>

<section>
    <article>
        <div class="wrapper-dashboard">
            <h2 style="text-align: center; margin-bottom: 20px;">Pending Bookings</h2>

            <?php if (isset($pending['error'])): ?>
                <p>Error fetching pending bookings: <?= htmlspecialchars($pending['error']) ?></p>
            <?php else: ?>
                <?php if (count($pending) > 0): ?>
                    <table border="1" cellpadding="6" cellspacing="0" width="100%">
                        <tr>
                            <th>No</th>
                            <th>Booking Id</th>
                            <th>Services</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                        <?php $count = 1; foreach ($pending as $row): ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= htmlspecialchars($row['appointment_id']) ?></td>
                                <td><?= htmlspecialchars($row['service_name']) ?></td>
                                <td><?= htmlspecialchars($row['booking_date']) ?></td>
                                <td><?= htmlspecialchars($row['booking_time']) ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p style="text-align:center;">No pending records found</p>
                <?php endif; ?>
            <?php endif; ?>

        </div>

        <div class="wrapper-dashboard" style="margin-top:30px;">
            <h2 style="text-align: center; margin-bottom: 20px;">Completed Bookings</h2>

            <?php if (isset($completed['error'])): ?>
                <p>Error fetching completed bookings: <?= htmlspecialchars($completed['error']) ?></p>
            <?php else: ?>
                <?php if (count($completed) > 0): ?>
                    <table border="1" cellpadding="6" cellspacing="0" width="100%">
                        <tr>
                            <th>No</th>
                            <th>Booking Id</th>
                            <th>Services</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                        <?php $count = 1; foreach ($completed as $row): ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= htmlspecialchars($row['appointment_id']) ?></td>
                                <td><?= htmlspecialchars($row['service_name']) ?></td>
                                <td><?= htmlspecialchars($row['booking_date']) ?></td>
                                <td><?= htmlspecialchars($row['booking_time']) ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p style="text-align:center;">No completed records found</p>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </article>
</section>
</body>
</html>
