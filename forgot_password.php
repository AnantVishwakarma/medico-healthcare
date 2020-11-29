<?php

date_default_timezone_set('Asia/Kolkata');

session_start();

if (isset($_SESSION['id'])) {
    header("Location: patient_dashboard.php");
}

if (isset($_POST['submit'])) {
    include('connect.php');
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM patients WHERE email=?;");
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            //Account Doesn't Exist
            $error_string = "Account doesn't exist";
        } else {
            //Create a token
            $token = random_bytes(32);

            //Create url
            $url = "http://localhost/medico/reset_password.php?email=" . $email . "&token=" . bin2hex($token);
            $expires = date("c", time() + 600);

            //Clear previous token if any            
            $stmt = $conn->prepare("DELETE FROM forgot_password WHERE email=?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                //Add token to database
                $hash_token = hash('sha256', $token);
                $stmt = $conn->prepare("INSERT INTO forgot_password (email, token, expires) VALUES(?,?,?);");
                $stmt->bind_param("sss", $email, $hash_token, $expires);
                if ($stmt->execute()) {
                    //mail the link to user
                    $to = $email;
                    $subject = "Reset Password";
                    $message = "<p>We received a password reset request from you. Here is your link to reset password:</p><p><a href='" . $url . "'>$url</a></p>";
                    $headers = "Content-type: text/html\r\n";
                    if (mail($to, $subject, $message, $headers)) {
                        $mail_sent = true;
                    } else {
                        //Error occured in sending mail
                        $error_string = "Error occured in sending mail";
                    }
                } else {
                    //Error occured in inserting new token
                    $error_string = "Error occured in inserting new token: " . $stmt->error;
                }
            } else {
                //Error occured in deleting previous token
                $error_string = "Error occured in deleting previous token";
            }
        }
    } else {
        //Internal Server Error
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
    <link rel="stylesheet" href="./css/forgot_password.css">
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
        if (isset($mail_sent) && $mail_sent == true) {
            echo "<p>Mail sent to <strong>" . $email . "</strong><p>";
        } else {
        ?>
            <h2>Reset Your Password</h2>
            <p class="error_message">
                <?php
                if (isset($error_string))
                    echo $error_string;
                ?>
            </p>
            <form action="#" method="POST" id="reset-form">
                <input type="email" placeholder="Email" name="email" required>
                <button type="submit" name="submit">Reset Password</button>
            </form>
        <?php
        }
        ?>
    </div>

    <?php
    include('footer.php');
    ?>
</body>

</html>