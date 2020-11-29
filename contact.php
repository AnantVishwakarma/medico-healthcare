<?php


session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css">
    <link rel="stylesheet" href="./css/contact.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Contact</title>
</head>

<body>
    <?php
    include('header.php');
    ?>

    <div class="container">
        <h2>Contact Us</h2>
        <p><i class="fal fa-envelope"></i> medico.healthcare@gmail.com</p>
        <p><i class="fal fa-phone-alt"></i> +91 9876543210</p>
        <br>
        <br>

        <h3>We value your feedback</h3>
        <?php
        if (isset($_SESSION['id'])) {
        ?>

            <form action="feedback.php" method="POST" id="feedback-form">
                <textarea name="message" placeholder="Message" cols="50" rows="10" required></textarea>
                <button type="submit">Submit</button>
            </form>

        <?php
        } else {
            echo "<a href='login.php'>Login To Give Feedback</a>";
        }

        ?>

    </div>

    <?php
    include('footer.php');
    ?>
</body>

</html>

<!-- 
    Pending:
    Add feedback to database
 -->