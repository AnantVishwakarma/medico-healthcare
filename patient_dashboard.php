<?php
include('connect.php');

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

$patient_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id=?;");
$stmt->bind_param("i", $patient_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();

    //$db_user_name = $db_user_dob = $db_user_sex = $db_user_phno = $db_user_email = $db_user_address = "";

    while ($row = $result->fetch_assoc()) {
        $db_user_name = $row['patient_name'];
        $db_user_dob = $row['date_of_birth'];
        $db_user_sex = $row['sex'];
        $db_user_phno = $row['phone_number'];
        $db_user_email = $row['email'];
        $db_user_address = $row['address'];
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css"> -->
    <link rel="stylesheet" href="./css/patient_dashboard.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Dashboard</title>
</head>

<body>
    <?php
    include('header.php');
    ?>
    <div id="bg-img">
        <img src="./assets/images/dashboard_background.jpg">

        <div id="welcome">
            <span>Welcome</span>
            <p><?php echo $db_user_name; ?></p>
            <small>Hope you are well!</small>
        </div>
    </div>

    <div id="account-information">
        <h2 class="heading">Account Information</h2>

        <div id="patient-details">
            <img src="./assets/illustrations/avatar.png" width="300px" height="400px">
            <div>
                <table id="patient-data">
                    <tr>
                        <th>Name</th>
                        <td><?php echo $db_user_name; ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td><?php echo date("d M Y", strtotime($db_user_dob)); ?></td>
                    </tr>
                    <tr>
                        <th>Sex</th>
                        <td><?php echo $db_user_sex; ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo $db_user_phno; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo $db_user_email; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $db_user_address; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="get-started">
        <h2 class="heading">Get Started</h2>
        <div id="appointment">
            <div id="appointment-info">
                <h2>Timings</h2>
                <table id="timings">
                    <tr>
                        <td>Monday - Saturday</td>
                        <td>10 am - 1 pm</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>2 pm - 6 pm</td>
                    </tr>
                </table>
            </div>
            <a href="book_appointment.php">Book an Appointment</a>
        </div>
    </div>

    <div id="other-links">
        <a href="health_reports.php">
            <i class="fas fa-files-medical"></i>
            <span>View Health Reports</span>
        </a>
        <a href="appointment_history.php">
            <i class="far fa-history"></i>
            <span>Appointment History</span>
        </a>
        <a href="payment_history.php">
            <i class="far fa-rupee-sign"></i>
            <span>Payment History</span>
        </a>
    </div>

    <script src="./js/patient_dashboard.js"></script>
    <?php
    include('footer.php');
    ?>
</body>

</html>
