<?php
session_start();
include('inc/connect.php');

$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
if (!$email) {
    echo "<p style='color:red; text-align:center;'>⚠️ Please login first!</p>";
}
?>

<!DOCTYPE html>
<html>
<head id="head-dashboard">
    <title>Service details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="js/external.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/icon.png">
</head>

<body id="body-dashboard">
    <section>
        <article>
            <div class="wrapper-dashboard">
                <h2 style="text-align: center; margin-bottom: 20px;">Completed Booking</h2>
                <table border="1px">
                    <?php
                    $sql2 = "SELECT * FROM services"; // 👈 Correct table name
                    $result2 = $conn->query($sql2);

                    if ($result2->num_rows > 0) {
                        echo "<tr>";
                        echo "<th style='text-align:center;'>No</th>";
                        echo "<th style='text-align:center;'>Service Name</th>";
                        echo "<th style='text-align:center;'>Description</th>";
                        echo "<th style='text-align:center;'>Price</th>";
                        echo "</tr>";

                        $count = 1;
                        while ($row = $result2->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $count++ . "</td>";
                            echo "<td style='text-align:left;'>" . $row['title'] . "</td>";
                            echo "<td style='text-align:left;'>" . $row['description'] . "</td>";
                            echo "<td>RM " . number_format($row['price'], 2) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<p style='text-align:center;'>No record found</p>";
                    }
                    ?>
                </table>
            </div>
        </article>
    </section>
</body>
</html>
