<?php

session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
}

include('connect.php');

$error_string = "";

if (isset($_POST['email']) && isset($_POST['password'])) {
    $admin_email = $_POST['email'];
    $admin_password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM `admin` WHERE email=?;");
    $stmt->bind_param("s", $admin_email);
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            //Admin doesn't exist
            $error_string = "Account doesn't exist";
        } else {
            while ($row = $result->fetch_assoc()) {
                $db_admin_id = $row['admin_id'];
                $db_admin_hashedpassword = $row['password'];
            }

            if (hash('sha256', $admin_password) != $db_admin_hashedpassword) {
                //Incorrect Username or Password
                $error_string = "Incorrect Username or Password";
            } else {
                //Successful Login
                $_SESSION['admin_id'] = $db_admin_id;
                header("Location: admin_dashboard.php");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fonts/fontawesome-pro-5.13.0-web/css/all.css">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            color: #444444;
            font: 1.3rem 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #brand {
            font-size: 2rem;
            /* background-color: #F0FFD2; */
        }

        #brand>i {
            font-size: 2rem;
            color: #478100;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 0;
            text-align: center;
            width: fit-content;
            margin: 5% auto;
            background-color: #f0ffd2;
            width: 18rem;
        }

        .form-container>* {
            padding: 1rem 1.5rem;
        }

        #login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0;
            margin: 0 1.5rem;
        }

        #login-form>input {
            margin: 0.5rem;
            padding: 0.5rem;
            outline: none;
            border: 1px solid gray;
            transition: border 200ms;
        }

        #login-form>input:focus {
            border: 2px solid #69be00;
        }

        .btn {
            padding: 0.5rem 2rem;
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

        .display-error {
            font-size: medium;
            padding: 0 1.5rem 0.5rem 1.5rem;
            color: red;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <span id="brand"><i class="fal fa-heartbeat"></i> Medico</span>
        <span><i class="fas fa-user"></i> Admin Login</span>
        <span class="display-error"><?php echo $error_string; ?></span>
        <form action="#" method="POST" id="login-form" onsubmit="return validateForm(this)">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <button class="btn" type="submit">Log In</button>
        </form>
    </div>
    <script>
        const displayError = document.querySelector(".display-error");

        function validateForm(form) {
            let email = form.email;
            let password = form.password;
            if (validateEmail(email) && validatePassword(password)) {
                return true;
            }

            return false;
        }

        function validateEmail(email) {
            const emailPattern = /[a-z0-9.]+@[a-z0-9.-]+\.[a-z]{2,}$/;
            if (email.value.match(emailPattern)) return true;
            else {
                displayError.innerHTML = "Not a valid email";
                email.focus();
                return false;
            }
        }

        function validatePassword(password) {
            if (password.value == "") {
                displayError.innerHTML = "Please type the password";
                password.focus();
                return false;
            } else {
                return true;
            }
        }
    </script>
</body>

</html>