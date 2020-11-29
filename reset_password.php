<?php

date_default_timezone_set('Asia/Kolkata');

session_start();

include('connect.php');

if (isset($_SESSION['id'])) {
    header("Location: patient_dashboard.php");
}

function checkValidity($connection, $email, $token)
{
    if (empty($email) || empty($token)) {
        return "Invalid Token";
    }
    $statement = $connection->prepare("SELECT * FROM forgot_password WHERE email=?;");
    $statement->bind_param("s", $email);
    if ($statement->execute()) {
        $result = $statement->get_result();
        if ($result->num_rows == 0) {
            $statement->close();
            return "Invalid Token";
        }
        while ($row = $result->fetch_assoc()) {
            $db_token =  $row['token'];
            $expires = $row['expires'];
        }
        if (strtotime($expires) < time()) {
            $statement->close();
            return "Token Expired";
        }
        if ($db_token === hash('sha256', hex2bin($token))) {
            $statement->close();
            return "Valid";
        } else {
            $statement->close();
            return "Invalid Token";
        }
    } else {
        $statement->close();
        return "Server Error";
    }
    $statement->close();
    return "Other Error";
}


if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];
    $validity = checkValidity($conn, $email, $token);
} else {
    $validity = "Invalid Token";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/reset_password.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Reset Password</title>
</head>

<body>
    <?php
    include('header.php');
    ?>

    <div class="container">
        <?php
        if ($validity === "Valid") {
        ?>
            <h2>Reset Password</h2>
            <p id="error_message"></p>
            <form action="reset_user_password.php" method="POST" id="reset-form" onsubmit="return validateForm(this)">
                <input type="password" name="password" placeholder="New Password">
                <input type="password" name="confirm_password" placeholder="Confirm Password">
                <input type="hidden" name="email" value=<?php echo $email; ?>>
                <input type="hidden" name="token" value=<?php echo $token; ?>>
                <button type="submit" name="submit">Reset</button>
            </form>
            <script src="./js/reset_password.js"></script>
        <?php
        } else {
            echo "<h3>" . $validity . "</h3>";
        }
        ?>
    </div>

    <?php
    include('footer.php');
    ?>

</body>

</html>

<!-- 
    Pending Form Validation
 -->