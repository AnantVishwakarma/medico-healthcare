<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css"> -->
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Medico Healthcare</title>
</head>
<body>
    <?php
    include ('header.php');
    ?>
    <div id="bg-img">
        <img src="./assets/images/homepage_background.jpg">
        <div>
            <h2>We Care For You</h2>
            <p>Medico Healthcare is dedicated to provide highest qulality healthcare services to you. We firmly believe that your good health is our first priority.</p>
            <hr>
            <a class="btn" href="register.php">Get Started</a>
        </div>
    </div>
    <div id="appointment">
        <div id="appointment-info">
            <h2>Timings</h2>
            <table id="timings">
                <tr>
                    <td>Monday - Saturday</td>
                    <td>10 am - 6 pm</td>
                </tr>
            </table>
        </div>
        <div id="appointment-btn">
            <a class="btn" href="book_appointment.php">Book an Appointment</a>
            <span>Or Call Us +91 9897656342</span>
        </div>
    </div>
    <div>
        <h2 class="heading">Our Facilities</h2>
        <div id="facilities">
            <div class="facility-card">
                <i class="fal fa-heart"></i>
                <h3>Intensive Care</h3>
                <p>Our medical staff look after our patients and provide best intensive care</p>
            </div>
            <div class="facility-card">
                <i class="fal fa-flask"></i>
                <h3>Lab Tests</h3>
                <p>We have laboratories to perform tests on demand</p>
            </div>
            <div class="facility-card">
                <i class="fal fa-check-circle"></i>
                <h3>Routine Check-ups</h3>
                <p>We provide routine check-ups so that you are always in good health</p>
            </div>
        </div>        
    </div>

    <?php
    include ('footer.php');
    ?>
    
</body>
</html>

