<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();

date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
}

include('connect.php');

if (isset($_POST['submit'])) {
    $message = "";
    $patient_id = (int)$_POST['patient_id'];    
    //$patient_type = (int)$_POST['patient_type'];
    $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id=?");
    $stmt->bind_param("i", $patient_id);
    echo $stmt->error;
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $message .= "Patient doesn't exist<br>";
        } else {
            $file = $_FILES['health_report'];
            $error = $file['error'];
            if ($error > 0) {
                $message .= "Error uploading file<br>";
            } else {
                $tmp_name = $file['tmp_name'];
                $generated_at = time();
                $patient_type = $_POST['patient_type'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $report_url = "./documents/reports/" . $patient_type . "_" . $patient_id . "_" . $generated_at . "." . $ext;
                if (move_uploaded_file($tmp_name, $report_url)) {
                    $message .= "File uploaded and moved successfully<br>";
                    $stmt = $conn->prepare("INSERT INTO health_reports (report_timestamp, report_url, patient_type, patient_id) VALUES (?,?,?,?);");
                    $generated_at_ISO = date("c", $generated_at);
                    $stmt->bind_param("ssii", $generated_at_ISO, $report_url, $patient_type, $patient_id);
                    if ($stmt->execute()) {
                        $message .= "File information added to database successfully<br>";
                    } else {
                        $message .= "Error adding file information to database<br>";
                    }
                } else {
                    $message .= "Error moving file to directory<br>";
                }
            }
        }
    } else {
        $message .= "Unable check if patient exist<br>";
    }
    $stmt->close();
    $conn->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            color: #444444;
            font: 1.3rem 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            text-decoration: none;
            color: #69be00;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 20rem;
            padding-bottom: 2rem;
            margin: 5% auto;
            box-shadow: 1px 1px 10px gray;
        }

        #upload-form {
            display: flex;
            flex-direction: column;
        }

        #upload-form>input,
        #upload-form>select {
            padding: 0.5rem;
            margin: 0.5rem;
        }

        .btn {
            padding: 1rem 2rem;
            margin: 0.5rem;
            font-size: medium;
            color: white;
            background: none;
            background-color: #69be00;
            border-radius: 5px;
            border: none;
            outline: none;
            cursor: pointer;
            transition: background-color 200ms;
        }

        .btn:hover {
            background-color: #58a000;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Admin</h2>
            <a href="admin_logout.php">Logout</a>
        </div>

        <p class="message">
            <?php
            if (isset($message)) echo $message;
            ?>
        </p>


        <h3>Upload Health Report</h3>
        <form action="#" method="POST" enctype="multipart/form-data" id="upload-form">
            <input type="number" name="patient_id" placeholder="Patient ID" required>
            <select name="patient_type">
                <option selected value="1">Online</option>
                <option value="0">Offline</option>
            </select>
            <input type="file" name="health_report" required>
            <button class="btn" type="submit" name="submit">Upload</button>
        </form>
    </div>

</body>

</html>

<!-- 
    Pending:
    Prevent user To read report of other patient
    Upload document for offline patients
 -->