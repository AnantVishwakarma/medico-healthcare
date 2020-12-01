<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./css/about.css">    
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>About Medico Healthcare</title>
</head>
<body>
    <?php
    include('header.php');
    ?>
    <div id="about-container">
        <h2>About Us</h2>
        <p>
            Started in 2005, Medico Healthcare is dedicated to provide highest qulality healthcare 
            services to the patients. The centre always keep updated with latest medical equipment and 
            have pathology laboratories inside the clinic to conduct tests without any hassle.
        </p>
        <p>
            Providing an online platform to the patients, they can now book an appointment at their home and 
            get the healthcare services without waiting in queues.
        </p>
        <p>
            We have excellent medical staffs on board, who are best at conducting generic health check-ups. 
            We also direct patients with cases that require further specialized medical attention to the 
            designated specialized doctors all around.
        </p>        
    </div>
    <div id="map">
        <h2>Reach Us</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d942.0118310031977!2d73.08454182914308!3d19.193135265749547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTnCsDExJzM1LjMiTiA3M8KwMDUnMDYuMyJF!5e0!3m2!1sen!2sin!4v1602501164840!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
    <?php
    include('footer.php');
    ?>
</body>
</html>