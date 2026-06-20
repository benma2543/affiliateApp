<?php
session_start();
require_once("config.php");
if (isset($_POST["email"])) {
  require "user/user-lib.php";
  $USR->lostpassword($_POST["email"],$sitename);
}

if (isset($_SESSION["user".$l1])) {
	header("Location: projects.php");
	exit();
}
require("menucolor.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Login</title>
		<!-- Custom fonts for this template-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		<!-- Custom styles for this template-->
		<link href="libs/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
	</head>
	<body class="<?php echo $menucolor; ?>">
		<div class="container">
			<!-- Outer Row -->
			<div class="row justify-content-center">
				<div class="col-xl-10 col-lg-12 col-md-9">
					<div class="card o-hidden border-0 shadow-lg my-5">
						<div class="card-body p-0">
							<!-- Nested Row within Card Body -->
							<div class="row bg-gray-100">
							<?php if (isset($_POST["email"])) { echo "<div class='alert alert-info' style='width:100%; text-align:center; margin-bottom:45px;'>If your email was found then a new password has been emailed to you. Don't forget to check your SPAM folder.</div>"; } ?>	
								<div class="col-lg-6 d-none d-lg-block" style="text-align:center;"><img style="padding:20px; max-height: 100%; max-width: 100%; width: auto; height: auto; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;" src="img/logo.png?<?php echo time(); ?>"></div>
								<div class="col-lg-6">
									<div class="p-5">
										<div class="text-center">
											<h1 class="h4 text-gray-900 mb-4">Forgot Password</h1>
										</div>
										<form class="user" id="forgotpassword" method="post">
											<div class="form-group">
												<input type="email" class="form-control form-control-user" name="email" placeholder="Enter Email Address...">
											</div>
											<div class="form-group"></div>
											<button type="submit" class="btn btn-primary btn-user btn-block"> New Password </button>
										</form>
										<hr>
										<div class="text-center">
											<a class="small" href="login.php">I already have an account - Login</a>
										</div>										
									</div>
								</div>
							</div>
						</div>
						<!-- Footer -->
						<footer class="sticky-footer bg-white">
							<div class="container my-auto">
								<div class="copyright text-center my-auto">
									<?php echo $footer; ?>
								</div>
							</div>
						</footer>
						<!-- End of Footer -->
					</div>
				</div>
			</div>
		</div>
		<!-- Bootstrap core JavaScript-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!-- Core plugin JavaScript-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js" integrity="sha512-0QbL0ph8Tc8g5bLhfVzSqxe9GERORsKhIn1IrpxDAgUsbBGz/V7iSav2zzW325XGd1OMLdL4UiqRJj702IeqnQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!-- Custom scripts for all pages-->
		<script src="libs/sbadmin2/js/sb-admin-2.min.js"></script>
	</body>
</html>
