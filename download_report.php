<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

if (!isset($_GET['report_id'])) {
    header("Location: health_reports.php");
}

$patient_id = $_SESSION['id'];
$report_id = $_GET['report_id'];

include('connect.php');

$stmt = $conn->prepare("SELECT report_url FROM health_reports WHERE report_id=? AND patient_type=1 AND patient_id=?");
$stmt->bind_param("ii", $report_id, $patient_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "File does not exist";
    } else {
        while ($row = $result->fetch_assoc()) {
            $file_url = $row['report_url'];
        }
        if (file_exists($file_url)) {
            header("Content-disposition: attachment; filename=" . basename($file_url));
            header("Content-type: application/pdf");
            readfile($file_url);
            /*$handler = fopen($file_url, 'r');
            if ($handler) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file_url));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                //header('Content-Length: ' . filesize($file)); //Remove

                //Send the content in chunks
                while (false !== ($chunk = fread($handler, 4096))) {
                    echo $chunk;
                }             
            }
            fclose($handler);*/
        } else {
            echo "File does not exist";
        }
    }
} else {
    echo "Error fetching report";
}
$stmt->close();
$conn->close();
