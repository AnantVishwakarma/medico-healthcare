<?php

include('connect.php');

session_start();

if (isset($_SESSION['id'])) {
    header("Location: patient_dashboard.php");
}

$error_string = "";

if (isset($_POST['email']) && isset($_POST['password'])) {
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM patients WHERE email=?;");
    $stmt->bind_param("s", $user_email);
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            //Patient doesn't exist
            $error_string = "Account doesn't exist";
        } else {
            //$db_user_id = $db_user_email = $db_user_hashedpassword = "";

            while ($row = $result->fetch_assoc()) {
                $db_user_id = $row['patient_id'];
                $db_user_email = $row['email'];
                $db_user_hashedpassword = $row['password'];
            }

            if (hash('sha256', $user_password) != $db_user_hashedpassword) {
                //Incorrect Username or Password
                $error_string = "Incorrect Username or Password";
            } else {
                //Successful Login
                $_SESSION['id'] = $db_user_id;
                header("Location: patient_dashboard.php");
            }
        }
    }
    else
    {
        //echo $stmt->error;
        $error_string = "Internal Server Error Occured. Please Try Again.";
    }
    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css">
    <link rel="stylesheet" href="./css/login.css">
    <title>Patient Login</title>
</head>

<body>
    <div class="container">
        <img src="./assets/images/login_background.jpg">
        <div class="form-container">
            <span id="brand"><i class="fal fa-heartbeat"></i> Medico</span>
            <span><i class="fas fa-user"></i> Patient Login</span>
            <span class="display-error"><?php echo $error_string; ?></span>
            <form action="#" method="POST" id="login-form" onsubmit="return validateForm(this)">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button class="btn" type="submit">Log In</button>
            </form>
            <div>
                <span class="link"><small>Don't have an account? <a href="./register.php">Register</a></small></span><br>
                <span class="link"><small><a href="forgot_password.php">Forgot Password?</a></small></span>
            </div>
        </div>
    </div>
    <script src="./js/login.js"></script>
</body>

</html>
