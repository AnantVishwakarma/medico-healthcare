<?php

$hostname = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "medico";

$conn = new mysqli($hostname,$dbusername,$dbpassword,$dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  
}

//echo "Connected Successfully";

?>