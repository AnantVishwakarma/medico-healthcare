<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

if(!(isset($_POST['app_date']) && isset($_POST['app_time'])))
{
    header("Location: patient_dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="./css/cancel_appointment.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Appointment Cancellation</title>
    
</head>

<body>
    <?php include('header.php'); ?>
    <div class="container">
        <?php
        include('connect.php');

        $patient_id = $_SESSION['id'];

        $app_date = $_POST['app_date'];
        $app_time = $_POST['app_time'];
        $query = "UPDATE appointments SET `status` = 0 WHERE patient_id = $patient_id AND app_date = '$app_date' AND app_time = '$app_time';";
        if ($conn->query($query) === TRUE) {
            echo "<h3>Appointment On<br><strong>" . date("D, d M Y", strtotime($app_date)) . ", " . date("h:i a", strtotime($app_time)) . "</strong><br>Cancelled Successfully</h3>";
        } else {
            //echo $conn->error;
            echo "<h3>Error Occured in Deleting Appointment</h3>";
        }

        $conn->close();

        ?>
        <a class='link' href='patient_dashboard.php'>Go Back To Dashboard</a>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>