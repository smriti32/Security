<?php session_start();
error_reporting(0);
include("include/config.php");
if(isset($_POST['submit']))
{
	$puname = mysqli_real_escape_string($con, $_POST['username']);
    $ppwd = $_POST['password'];

	$stmt = $con->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $puname);
    $stmt->execute();
    $stmt->store_result();
	if ($stmt->num_rows > 0)
	{
	
		$stmt->bind_result($id, $hashed_password);
		$stmt->fetch();
		if(password_verify($ppwd,$hashed_password))
		{
            // Successful login
            $_SESSION['login'] = $puname;
            $_SESSION['id'] = $id;
            $pid = $id;
            $host = $_SERVER['HTTP_HOST'];
            $uip = $_SERVER['REMOTE_ADDR'];
            $status = 1;

            // Log successful login
            $log_stmt = $con->prepare("INSERT INTO userlog (uid, username, userip, status) VALUES (?, ?, ?, ?)");
            $log_stmt->bind_param("isis", $pid, $puname, $uip, $status);
            $log_stmt->execute();

            header("Location: dashboard.php");
            exit();
		}
		else {
            // Log unsuccessful login attempt
            $_SESSION['login'] = $puname;
            $uip = $_SERVER['REMOTE_ADDR'];
            $status = 0;

            $log_stmt = $con->prepare("INSERT INTO userlog (username, userip, status) VALUES (?, ?, ?)");
            $log_stmt->bind_param("ssi", $puname, $uip, $status);
            $log_stmt->execute();

            echo "<script>alert('Invalid username or password');</script>";
            echo "<script>window.location.href='user-login.php'</script>";
        }
	}

	else {
        // Log unsuccessful login attempt
        $_SESSION['login'] = $puname;
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;

        $log_stmt = $con->prepare("INSERT INTO userlog (username, userip, status) VALUES (?, ?, ?)");
        $log_stmt->bind_param("ssi", $puname, $uip, $status);
        $log_stmt->execute();

        echo "<script>alert('Invalid username or password');</script>";
        echo "<script>window.location.href='user-login.php'</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<title>User-Login</title>
		
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body class="login">
		<div class="row">
			<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div class="logo margin-top-30">
				<a href="../index.php"><h2> HMS | Patient Login</h2></a>
				</div>

				<div class="box-login">
					<form class="form-login" method="post">
						<fieldset>
							<legend>
								Sign in to your account
							</legend>
							<p>
								Please enter your name and password to log in.<br />
								<span style="color:red;"><?php echo $_SESSION['errmsg']; ?><?php echo $_SESSION['errmsg']="";?></span>
							</p>
							<div class="form-group">
								<span class="input-icon">
									<input type="email" class="form-control" name="username" placeholder="Email" required>
									<i class="fa fa-user"></i> </span>
							</div>
							<div class="form-group form-actions">
								<span class="input-icon">
									<input type="password" class="form-control" name="password" placeholder="Password" required>
									<i class="fa fa-lock"></i>
									 </span><a href="forgot-password.php">
									Forgot Password ?
								</a>
							</div>
							<div class="form-actions">
								
								<button type="submit" class="btn btn-primary pull-right" name="submit">
									Login <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
							<div class="new-account">
								Don't have an account yet?
								<a href="registration.php">
									Create an account
								</a>
							</div>
						</fieldset>
					</form>

					<div class="copyright">
						</span><span class="text-bold text-uppercase"> Hospital Management System</span>.
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
		<!-- <script src="vendor/jquery-validation/jquery.validate.min.js"></script> -->
	
		<script src="assets/js/main.js"></script>

		<script src="assets/js/login.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Login.init();
			});
		</script>
	
	</body>
	<!-- end: BODY -->
</html>