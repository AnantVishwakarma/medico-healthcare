<?php

include('connect.php');

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

$patient_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT appointment_id, app_date, date_format(app_time, '%H:%i') as app_time, `status` FROM appointments WHERE patient_id=? ORDER BY app_date DESC, app_time DESC, `status` DESC;");
$stmt->bind_param("i", $patient_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $app_details = array();
    while ($row = $result->fetch_assoc()) {
        $app_details[$row['appointment_id']]['app_date'] = $row['app_date'];
        $app_details[$row['appointment_id']]['app_time'] = $row['app_time'];
        $app_details[$row['appointment_id']]['status'] = $row['status'];
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
    <link rel="stylesheet" href="./css/appointment_history.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Appointment History</title>
</head>

<body>
    <?php
    include('header.php');
    ?>

    <div class="container">
        <h2>Appointment History</h2>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($app_details as $key => $value) {
                    echo "<tr>";
                    echo "<td>" . $key . "</td><td>" . date("D, d M Y", strtotime($value['app_date'])) . "</td><td>" . date("h:i a", strtotime($value['app_time'])) . "</td>";
                    switch($value['status'])
                    {
                        case 0:
                            echo "<td class='app-cancelled'>Cancelled</td>";
                        break;
                        case 1:
                            echo "<td class='app-booked'>Booked</td>";
                        break;
                        case 2:
                            echo "<td class='app-attended'>Attended</td>";
                        break;
                    }                    
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    include('footer.php');
    ?>
</body>

</html>