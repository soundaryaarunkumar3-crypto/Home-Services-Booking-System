<?php
session_start();
include('../inc/connect.php');

// If admin not logged in, redirect to admin login to avoid undefined session key
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header('Location: login.php'); // change path if your admin login is elsewhere
    exit();
}

// Optionally check role if stored: uncomment if you set role in session
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
//}

// include admin header (adjust path if different)
include('../header_admin.php');
?>
<!DOCTYPE html>
<html>
<head id="head-dashboard">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body id="body-dashboard">

<section>
    <article>
        <div class="wrapper-dashboard">
            <h2 style="text-align: center; margin-bottom: 20px;">Client Booking (Pending)</h2>

            <?php
            // Pending bookings: use booking_date and booking_time columns
            // GROUP_CONCAT to combine multiple services for a booking
            $sql = "
                SELECT 
                    a.appointment_id,
                    a.booking_date,
                    a.booking_time,
                    a.status,
                    a.notes,
                    COALESCE(GROUP_CONCAT(DISTINCT service.service_name SEPARATOR ', '), '') AS service_name
                FROM appointment a
                LEFT JOIN appointment_service aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN service ON service.service_id = aps.service_id
                WHERE LOWER(a.status) = 'pending'
                GROUP BY a.appointment_id
                ORDER BY a.booking_date DESC, a.booking_time DESC
            ";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                echo "<table border='1' cellpadding='6' cellspacing='0' width='100%'>";
                echo "<tr>";
                echo "<th>No</th>";
                echo "<th>Booking Id</th>";
                echo "<th>Services</th>";
                echo "<th>Date</th>";
                echo "<th>Time</th>";
                echo "<th>Status</th>";
                echo "</tr>";

                $count = 1;
                while ($row = $result->fetch_assoc()) {
                    // Use booking_date and booking_time (match your DB columns)
                    $bookingDate = htmlspecialchars($row['booking_date']);
                    $bookingTime = htmlspecialchars($row['booking_time']);
                    $serviceName = htmlspecialchars($row['service_name']);
                    $status = htmlspecialchars($row['status']);
                    $appointmentId = (int)$row['appointment_id'];

                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . $appointmentId . "</td>";
                    echo "<td>" . $serviceName . "</td>";
                    echo "<td>" . $bookingDate . "</td>";
                    echo "<td>" . $bookingTime . "</td>";
                    echo "<td>";
                    // Inline form to update status
                    echo "<form method='post' action='../processdataPHP/update_status.php' style='display:inline-block;'>";
                    echo "<input type='hidden' name='appointment_id' value='" . $appointmentId . "'>";
                    echo "<select name='status'>";
                    echo "<option value='pending'" . (strtolower($status) === 'pending' ? " selected" : "") . ">Pending</option>";
                    echo "<option value='completed'" . (strtolower($status) === 'completed' ? " selected" : "") . ">Completed</option>";
                    echo "<option value='cancelled'" . (strtolower($status) === 'cancelled' ? " selected" : "") . ">Cancelled</option>";
                    echo "</select> ";
                    echo "<input type='submit' value='Update'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='text-align: center;'>No record found</p>";
            }
            ?>
        </div>

        <div class="wrapper-dashboard" style="margin-top:30px;">
            <h2 style="text-align: center; margin-bottom: 20px;">Completed Bookings</h2>

            <?php
            // Completed bookings (case-insensitive)
            $sql2 = "
                SELECT 
                    a.appointment_id,
                    a.booking_date,
                    a.booking_time,
                    a.status,
                    COALESCE(GROUP_CONCAT(DISTINCT service.service_name SEPARATOR ', '), '') AS service_name
                FROM appointment a
                LEFT JOIN appointment_service aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN service ON service.service_id = aps.service_id
                WHERE LOWER(a.status) = 'completed'
                GROUP BY a.appointment_id
                ORDER BY a.booking_date DESC, a.booking_time DESC
            ";

            $result2 = $conn->query($sql2);

            if ($result2 && $result2->num_rows > 0) {
                echo "<table border='1' cellpadding='6' cellspacing='0' width='100%'>";
                echo "<tr>";
                echo "<th>No</th>";
                echo "<th>Booking Id</th>";
                echo "<th>Services</th>";
                echo "<th>Date</th>";
                echo "<th>Time</th>";
                echo "<th>Status</th>";
                echo "</tr>";

                $count2 = 1;
                while ($row = $result2->fetch_assoc()) {
                    $bookingDate = htmlspecialchars($row['booking_date']);
                    $bookingTime = htmlspecialchars($row['booking_time']);
                    $serviceName = htmlspecialchars($row['service_name']);
                    $status = htmlspecialchars($row['status']);
                    $appointmentId = (int)$row['appointment_id'];

                    echo "<tr>";
                    echo "<td>" . $count2++ . "</td>";
                    echo "<td>" . $appointmentId . "</td>";
                    echo "<td>" . $serviceName . "</td>";
                    echo "<td>" . $bookingDate . "</td>";
                    echo "<td>" . $bookingTime . "</td>";
                    echo "<td>" . $status . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='text-align: center;'>No record found</p>";
            }
            ?>
        </div>
    </article>
</section>

</body>
</html>
