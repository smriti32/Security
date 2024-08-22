<?php
include_once('include/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require 'vendor/autoload.php';

#this function is for sending email for the user
#for future purpose
function sendVerificationEmail($email, $verificationCode)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'live.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'api';
        $mail->Password = '7fb6eefde00691a3534a25d478aec211';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('youremail@example.com', 'Your Website');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'Email Verification';
        $mail->Body = "Your verification code is: $verificationCode";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

#this function check if the password character count is less than min count or not
function ValidatePasswordLength($rawPass)
{
    $minLength = 8;
    $specialCharPattern = '/[!@#$%^&*(),.?":{}|<>]/';
    if (strlen($rawPass) < $minLength) {
        echo "<script>alert('Too Weak Password');</script>";
        return false;
    } else if (!preg_match($specialCharPattern, $rawPass)) {
        echo "<script>alert('Password must contain at least one special character.');</script>";
        return false;
    }
    return true;

}

if (isset($_POST['submit'])) {
    $fname = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $gender = $_POST['gender'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (ValidatePasswordLength($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query = mysqli_query($con, "insert into users(fullname,address,city,gender,email,password) values('$fname','$address','$city','$gender','$email','$password')");
        if ($query) {
            echo "<script>alert('Successfully Registered. You can login now');</script>";
            //header('location:user-login.php');
        }
    }

// if(sendVerificationEmail($email,"key"))
// 	{
    //validate email and register ...
// 	}
// else
// 	{
    //send message that the email was not verified ...
// 	}

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Registration</title>

    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic"
          rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color"/>

    <script type="text/javascript">
        function valid() {
            if (document.registration.password.value != document.registration.password_again.value) {
                alert("Password and Confirm Password Field do not match  !!");
                document.registration.password_again.focus();
                return false;
            }
            return true;
        }
    </script>


</head>

<body class="login">
<!-- start: REGISTRATION -->
<div class="row">
    <div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="logo margin-top-30">
            <a href="../index.php"><h2>HMS | Patient Registration</h2></a>
        </div>
        <!-- start: REGISTER BOX -->
        <div class="box-register">
            <form name="registration" id="registration" method="post" onSubmit="return valid();">
                <fieldset>
                    <legend>
                        Sign Up
                    </legend>
                    <p>
                        Enter your personal details below:
                    </p>
                    <div class="form-group">
                        <input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="address" placeholder="Address" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="city" placeholder="City" required>
                    </div>
                    <div class="form-group">
                        <label class="block">
                            Gender
                        </label>
                        <div class="clip-radio radio-primary">
                            <input type="radio" id="rg-female" name="gender" value="female">
                            <label for="rg-female">
                                Female
                            </label>
                            <input type="radio" id="rg-male" name="gender" value="male">
                            <label for="rg-male">
                                Male
                            </label>
                        </div>
                    </div>
                    <p>
                        Enter your account details below:
                    </p>
                    <div class="form-group">
								<span class="input-icon">
									<input type="email" class="form-control" name="email" id="email"
                                           onBlur="userAvailability()" placeholder="Email" required>
									<i class="fa fa-envelope"></i> </span>
                        <span id="user-availability-status1" style="font-size:12px;"></span>
                    </div>
                    <div class="form-group">
								<span class="input-icon">
									<input type="password" class="form-control" id="password" name="password"
                                           placeholder="Password" required>
									<i class="fa fa-lock"></i> </span>
                    </div>
                    <div class="form-group">
								<span class="input-icon">
									<input type="password" class="form-control" id="password_again"
                                           name="password_again" placeholder="Password Again" required>
									<i class="fa fa-lock"></i> </span>
                    </div>
                    <div class="form-group">
                        <div class="checkbox clip-check check-primary">
                            <input type="checkbox" id="agree" value="agree" checked="true" readonly=" true">
                            <label for="agree">
                                I agree
                            </label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <p>
                            Already have an account?
                            <a href="user-login.php">
                                Log-in
                            </a>
                        </p>
                        <button type="submit" class="btn btn-primary pull-right" id="submit" name="submit">
                            Submit <i class="fa fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </fieldset>
            </form>

            <div class="copyright">
                &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> HMS</span>. <span>All rights reserved</span>
            </div>

        </div>

    </div>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/login.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
        Login.init();
    });
</script>

<script>
    function userAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function (data) {
                $("#user-availability-status1").html(data);
                $("#loaderIcon").hide();
            },
            error: function () {
            }
        });
    }
</script>

</body>
<!-- end: BODY -->
</html>