<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

include('connect.php');

$patient_id = $_SESSION['id'];

$app_date = $_GET['date'];
$app_time = $_GET['time'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="./css/booked_appointment.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Success</title>
</head>

<body>
    <?php
    include('header.php');

    ?>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <p>Do you want to cancel appointment on <br><strong><?php echo date("D, d M Y", strtotime($app_date)) . ", " . date("h:i a", strtotime($app_time)); ?></strong></p>
            <form action="cancel_appointment.php" method="post" id="cancellation-form">
                <input type="hidden" name="app_date" value=<?php echo $app_date; ?>>
                <input type="hidden" name="app_time" value=<?php echo $app_time; ?>>
                <button class="btn danger" type="submit">Confirm Cancellation</button>
            </form>
            <button id="abort-cancellation" class="btn safe">Abort Cancellation</button>
        </div>
    </div>

    <div class="container">
        <?php
        $stmt = $conn->prepare(
            "SELECT * 
            FROM appointments 
            WHERE patient_id = $patient_id
            AND app_date = ? 
            AND app_time = ?
            AND ((app_date = CURRENT_DATE() AND app_time > CURRENT_TIME()) OR app_date > CURRENT_DATE());"
        );
        $stmt->bind_param("ss", $app_date, $app_time);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
        ?>
                <i class="fal fa-check-circle"></i>
                <h3>Appointment Scheduled At <br><strong><?php echo date("D, d M Y", strtotime($app_date)) . ", " . date("h:i a", strtotime($app_time)); ?></strong></h3>
                <button id="cancel-appointment" class='btn danger' href='patient_dashboard.php'>Cancel Appointment</button><br>
        <?php
            } else {
                echo "<h3><strong>Appointment either expired or doesn't exist</strong></h3>";
            }

            echo "<a class='link' href='patient_dashboard.php'>Go Back To Dashboard</a>";
        } else {
            echo "<h3>Internal Server Error</h3>";
        }
        ?>

    </div>
    <?php
    include('footer.php');
    ?>
    <script src="./js/booked_appointment.js"></script>
</body>

</html>

<!-- 
    Pending:
 -->