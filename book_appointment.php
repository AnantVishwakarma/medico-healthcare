<?php
date_default_timezone_set('Asia/Kolkata');

session_start();
include('connect.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

$patient_id = $_SESSION['id'];


//Check if patient has already booked an appointment
$query =
    "SELECT app_date, date_format(app_time,'%H:%i') as app_time
FROM appointments 
WHERE patient_id = $patient_id 
AND `status` = 1
AND ((app_date = CURRENT_DATE() AND app_time > CURRENT_TIME()) OR app_date > CURRENT_DATE());";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    //appointment already booked    
    while ($row = $result->fetch_assoc()) {
        $app_date = $row['app_date'];
        $app_time = $row['app_time'];
    }
    $conn->close();
    header("Location: booked_appointment.php?date=" . $app_date . "&time=" . $app_time);
}



//Check if appointment is available
function isAvailable($app, $date, $time)
{
    if (strtotime($date . "T" . $time) < time()) {
        return false;
    }
    foreach ($app as $key => $value) {
        if ($value['date'] == $date && $value['time'] == $time) {
            return false;
        }
    }
    return true;
}


//Get all available appointments from current time
function getAppointments($conn)
{
    $currentdate = date("Y-m-d", time());
    $statement = $conn->prepare("SELECT appointment_id, app_date, date_format(app_time,'%H:%i') as app_time FROM appointments WHERE app_date >= ? AND `status` = 1;");
    $statement->bind_param("s", $currentdate);
    $appts = array();
    if ($statement->execute()) {
        $result = $statement->get_result();
        while ($row = $result->fetch_assoc()) {
            $appts[$row['appointment_id']] = array('date' => $row['app_date'], 'time' => $row['app_time']);
        }
    } else {
        echo $statement->error;
        //$error_string = "Internal Server Error Occured. Please Try Again.";
    }
    $statement->close();
    return $appts;
}

$appointments = getAppointments($conn);

if (isset($_POST['app_date']) && isset($_POST['app_time'])) {
    $app_date = $_POST['app_date'];
    $app_time = $_POST['app_time'];
    if (isAvailable($appointments, $app_date, $app_time)) {
        $status = 1; //status=> 0 - cancelled, 1 - booked, 2 - attended
        $patient_type = 1; //patient_type=> 1 - online, 0 - offline

        $stmt = $conn->prepare("INSERT INTO appointments (`app_date`, `app_time`, `status`, `patient_type`, `patient_id`) VALUES (?,?,?,?,?);");

        $stmt->bind_param("ssiii", $app_date, $app_time, $status, $patient_type, $patient_id);
        if ($stmt->execute()) {
            //Successfully Booked
            $stmt->close();
            $conn->close();
            //send mail notification
            header("Location: booked_appointment.php?date=" . $app_date . "&time=" . $app_time);
        } else {
            echo $stmt->error;
            $error_string = "Internal Server Error Occured. Please Try Again.";
        }
        $stmt->close();
    } else {
        $error_string = "Appointment slot not available";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css"> -->
    <link rel="stylesheet" href="./css/book_appointment.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Book Appointment</title>
</head>

<body>
    <?php
    include('header.php');
    ?>

    <div class="container">
        <h2>Schedule Appointment</h2>
        <table>
            <thead>
                <?php
                $timearray = [];
                $currenttime = strtotime("10:00");
                echo "<tr><th>Choose Slot</th>";
                while ($currenttime < strtotime("13:00")) {
                    echo "<th>" . date("H:i", $currenttime) . "</th>";
                    array_push($timearray, date("H:i", $currenttime));
                    $currenttime = strtotime("+20 minutes", $currenttime);
                }
                $currenttime = strtotime("14:00");
                while ($currenttime < strtotime("18:00")) {
                    echo "<th>" . date("H:i", $currenttime) . "</th>";
                    array_push($timearray, date("H:i", $currenttime));
                    $currenttime = strtotime("+20 minutes", $currenttime);
                }
                echo "</tr>";
                ?>
            </thead>
            <tbody>
                <?php
                $currentdate = time();
                $i = 0;
                while ($i < 7) {
                    if (date("D", $currentdate) != "Sun") {
                        echo "<tr><td>" . date("D, d M Y", $currentdate) . "</td>";
                        foreach ($timearray as $time) {
                            if (isAvailable($appointments, date("Y-m-d", $currentdate), $time)) {
                                echo "<td class='app_dt_available' data-date='" . date("Y-m-d", $currentdate) . "' data-time='" . $time . "'></td>";
                            } else {
                                echo "<td class='app_dt_unavailable' data-date='" . date("Y-m-d", $currentdate) . "' data-time='" . $time . "'></td>";
                            }
                        }
                        echo "</tr>";
                        $i++;
                    }
                    $currentdate = strtotime("+1 day", $currentdate);
                }
                ?>
            </tbody>
        </table>

        <h3 id="app_dt_view"></h3>
        <h3 id="error_message"><?php if (isset($error_string)) echo $error_string; ?></h3>

        <form action="#" method="POST" id="appointment_form">
            <input name="app_date" type="hidden" readonly>
            <input name="app_time" type="hidden" readonly>
            <button type="submit" name='submit' disabled>Confirm Booking</button>
        </form>
    </div>

    <script src="./js/book_appointment.js"></script>

    <?php
    include('footer.php');
    ?>
</body>

</html>

<!-- 
    Pending:    
 -->