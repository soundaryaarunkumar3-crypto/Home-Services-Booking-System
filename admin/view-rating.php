<?php
session_start();
include('../inc/connect.php');
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head id="head-dashboard">
    <title>Rating</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="../js/external.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php include('../header_admin.php'); ?>

<body id="body-dashboard">
<section>
    <article>
        <div class="wrapper-dashboard">
            <h2 style="text-align: center; margin-bottom: 20px;">Rating</h2>
            <table border="1px" style="width:100%; border-collapse: collapse; text-align:center;">
                <?php
                $sql = "SELECT * FROM feedback";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<tr>
                            <th>No</th>
                            <th>User ID</th>
                            <th>Rating</th>
                            <th>Comments</th>
                          </tr>";

                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $count++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['comments']) . "</td>";
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
