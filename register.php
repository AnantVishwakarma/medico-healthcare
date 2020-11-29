<?php
session_start();

include ('connect.php');


if (isset($_SESSION['id'])) {
    header("Location: patient_dashboard.php");
}

$error_string = "";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css">
    <link rel="stylesheet" href="./css/register.css">
    <title>Register</title>
</head>

<body>
    <?php
    
    if (
        isset($_POST['name']) && isset($_POST['dob']) && isset($_POST['sex']) && isset($_POST['phno'])
        && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])
    ) {
        $user_email = $_POST['email'];
        $stmt = $conn->prepare("SELECT * FROM patients WHERE email=?;");
        $stmt->bind_param("s", $user_email);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                //Email already registered
                $error_string = "Email already registered";
            } else {
                //Register the patient
                $user_name = $_POST['name'];
                $user_dob = $_POST['dob'];
                $user_sex = $_POST['sex'];
                $user_phno = $_POST['phno'];
                $user_address = $_POST['address'];
                $user_hashed_password = hash('sha256', $_POST['password']);

                $stmt = $conn->prepare("INSERT INTO patients(`patient_name`,`date_of_birth`,`sex`,`phone_number`,`address`,`email`,`password`) VALUES(?,?,?,?,?,?,?);");
                $stmt->bind_param("sssssss", $user_name, $user_dob, $user_sex, $user_phno, $user_address, $user_email, $user_hashed_password);
                if ($stmt->execute()) {
                    //Successfully Registered
    ?>
                    <div class="modal" id="myModal">
                        <div class="modal-content">
                            <span><i class="fal fa-check-circle"></i></span>
                            <p>Successfully Registered</p>
                            <a href="login.php">Go To Login Page</a>
                        </div>
                    </div>
    <?php
                } else {
                    //echo $stmt->error;
                    $error_string = "Internal Server Error Occured. Please Try Again.";
                }
            }
        } else {
            //echo $stmt->error;
            $error_string = "Internal Server Error Occured. Please Try Again.";
        }
        $stmt->close();
    }

    $conn->close();

    ?>

    <div class="container">
        <img src="./assets/images/registration_background.jpg">
        <div class="form-container">
            <span id="brand"><i class="fal fa-heartbeat"></i> Medico</span>
            <span><i class="fas fa-user-plus"></i> Patient Registration</span>
            <span class="display-error"><?php echo $error_string; ?></span>
            <form action="#" method="POST" id="registration-form" onsubmit="return validateForm(this)">
                <input type="text" name="name" placeholder="Name">
                <input type="text" name="dob" placeholder="Date Of Birth">
                <select name="sex">
                    <option value="" disabled selected>Sex</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <input type="tel" name="phno" placeholder="Phone Number">
                <textarea type="text" rows="5" cols="40" name="address" placeholder="Address"></textarea>
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="confirm_password" placeholder="Confirm Password">
                <button class="btn" type="submit">Register</button>
            </form>
            <span class="link"><small>Already have an account? <a href="./login.php">Log In</a></small></span>
        </div>
    </div>
    <script src="./js/register.js"></script>
</body>

</html>