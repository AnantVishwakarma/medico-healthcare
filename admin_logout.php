<?php

session_start();

if (isset($_SESSION['admin_id'])) {
    session_destroy();
}

header("Location: admin_login.php");

?>
