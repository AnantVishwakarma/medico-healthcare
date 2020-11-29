<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

$app_date = $_GET['date'];
$app_time = $_GET['time'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css">
    <link rel="stylesheet" href="./css/booked_appointment.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Success</title>
</head>

<body>
    <?php
    include('header.php');
    ?>
    <div class="container">
        <i class="fal fa-check-circle"></i>
        <h3>Appointment Successfully Scheduled At <br><strong><?php echo date("D, d M Y", strtotime($app_date)) . ", " . date("h:i a", strtotime($app_time)); ?></strong></h3>
        <a href="patient_dashboard.php">Go Back To Dashboard</a>
    </div>
    <?php
    include('footer.php');
    ?>
</body>

</html>

<!-- 
    Pending:
    Logged in user can edit the page by changing url
 -->