<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

include('connect.php');

$patient_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT report_id, report_timestamp FROM health_reports WHERE patient_type=1 AND patient_id=? ORDER BY report_timestamp DESC;");
$stmt->bind_param("i", $patient_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $report_details = array();
    while ($row = $result->fetch_assoc()) {
        $report_details[$row['report_id']]['date'] = date("D, d M Y", strtotime($row['report_timestamp']));
        $report_details[$row['report_id']]['time'] = date("h:i a", strtotime($row['report_timestamp']));
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Health Reports</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font: 1.3rem 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 5rem auto;
        }

        table,
        th,
        td {
            border: 1px solid gray;
            border-collapse: collapse;
        }

        th {
            background-color: #f0ffd2;
        }

        th,
        td {
            text-align: center;
            padding: 0.5rem;
        }

        .report_link {
            text-decoration: none;
            color: #69be00;
        }
    </style>
</head>

<body>
    <?php
    include('header.php');
    ?>

    <div class="container">
        <h2>Health Reports</h2>
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Download Link</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($report_details as $key => $value) {
                    echo "<tr>";
                    echo "<td>" . $key . "</td><td>" . $value['date'] . "</td><td>" . $value['time'] . "</td>";
                    echo "<td><a class='report_link' href='download_report.php?report_id=" . $key . "'>Download</a></td>";
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