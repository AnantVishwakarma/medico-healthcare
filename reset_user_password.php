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
        }
    } else {
        $statement->close();
        return "Server Error";
    }
    $statement->close();
    return "Other Error";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="./css/reset_user_password.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Password Reset</title>
</head>

<body>
    <?php
    include('header.php');
    ?>

    <div class="container">

        <?php

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $token = $_POST['token'];
            $password = $_POST['password'];
            $validity = checkValidity($conn, $email, $token);
            if ($validity === "Valid") {
                $hashed_password = hash('sha256', $password);
                $stmt = $conn->prepare("UPDATE patients SET `password`=? WHERE `email`=?;");
                $stmt->bind_param("ss", $hashed_password, $email);
                if ($stmt->execute()) {
                    echo "<h3>Password Changed Successfully</h3>";                    
                    //Delete Token
                    $stmt = $conn->prepare("DELETE FROM forgot_password WHERE `email`=?;");
                    $stmt->bind_param("s", $email);
                    if (!$stmt->execute()) {
                        echo "<h3>Unable To Delete Token</h3>";
                    }
                } else {
                    //echo $stmt->error;
                    echo "<h3>Internal Server Error Occured</h3>";
                }
                $stmt->close();
            } else {
                echo "<h3>" . $validity . "</h3>";
            }
            echo "<a class='link' href='login.php'>Go To Login Page</a>";
        } else {
            header("Location: login.php");
        }

        $conn->close();

        ?>
    </div>

    <?php
    include('footer.php');
    ?>

</body>

</html>