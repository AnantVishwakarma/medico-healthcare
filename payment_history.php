<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

include('connect.php');

$patient_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT payment_id, amount, payment_timestamp, payment_type FROM payment_history WHERE patient_type=1 AND patient_id=? ORDER BY payment_timestamp DESC;");
$stmt->bind_param("i", $patient_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $payment_details = array();
    while ($row = $result->fetch_assoc()) {
        $payment_details[$row['payment_id']]['amount'] = $row['amount'];
        $payment_details[$row['payment_id']]['payment_timestamp'] = $row['payment_timestamp'];
        $payment_details[$row['payment_id']]['payment_type'] = $row['payment_type'];
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
    <title>Payment History</title>
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

    </style>
</head>

<body>
    <?php
    include('header.php');
    ?>

    <div class="container">
        <h2>Payment History</h2>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>For</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($payment_details as $key => $value) {
                    echo "<tr>";
                    echo "<td>" . $key . "</td>";
                    echo "<td>" . date("D, d M Y", strtotime($value['payment_timestamp'])) . "</td>";
                    echo "<td>" . date("h:i a", strtotime($value['payment_timestamp'])) . "</td>";
                    switch ($value['payment_type']) {
                        case 1:
                            echo "<td>Consultancy</td>";
                            break;
                        case 2:
                            echo "<td>Lab Test</td>";
                            break;
                        default:
                            echo "<td>Other</td>";
                    }
                    echo "<td>" . $value['amount'] . " &#8377;</td>";
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