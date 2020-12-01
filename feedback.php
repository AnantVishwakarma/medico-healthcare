<?php


session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

include('connect.php');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <!-- <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./css/feedback.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Feedback</title>
</head>

<body>
    <?php
    include ('header.php');
    ?>
    <div class="container">
        <?php
        if (isset($_POST['message'])) {            
            $patient_id = $_SESSION['id'];
            $message = $_POST['message'];
            $timestamp = date("c");

            $stmt = $conn->prepare("INSERT INTO feedback (patient_id, feedback_timestamp, `message`) VALUES (?,?,?);");
            $stmt->bind_param("iss", $patient_id, $timestamp, $message);
            if ($stmt->execute()) {
                echo "<h3>Thank You For Your Feedback!</h3>";
            } else {
                echo "<h3 id='error_message'>Internal Server Error Occured</h3>";
                echo "<h3 id='error_message'>" . $stmt->error . "</h3>";
            }
            $stmt->close();
            $conn->close();
        }
        else {
            header("Location: contact.php");
        }
        ?>
    </div>

    <?php
    include ('footer.php');
    ?>
</body>

</html>

<!-- 
    Pending:
    Prevent Resubmitting of feedback on page refresh
 -->