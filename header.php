<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('connect.php');

?>

<nav class="navbar">
    <div class="brand-container">
        <span id="brand"><i class="fal fa-heartbeat"></i> Medico</span>
    </div>
    <div class="navbar-navlinks">
        <a href="./index.php">Home</a>
        <a href="./about.php">About</a>
        <a href="./contact.php">Contact</a>
    </div>
    <?php
    if (isset($_SESSION['id'])) {
        $stmt = $conn->prepare("SELECT patient_name FROM patients WHERE patient_id=?;");
        $stmt->bind_param("i", $_SESSION['id']);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $patient_name = $row['patient_name'];
            }
        }
        $stmt->close();
        //$conn->close();
        
    ?>
        <div class="dropdown">
            <button id="btn-profile"><i class="fas fa-user"></i> <?php echo $patient_name; ?></button>
            <div id="profile-dropdown" class="dropdown-content">
                <a href="patient_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
        <script src="./js/header.js"></script>
    <?php
    } else {
    ?>
        <div class="user">
            <a href="./login.php"><i class="fas fa-user"></i> Log In</a>
            <a href="./register.php"><i class="fas fa-user-plus"></i> Register</a>
        </div>
    <?php
    }
    ?>
</nav>
