<?php
require_once("config.php");
session_start();

if (isset($_POST["username"]) && isset($_POST["password"])) {
	if ($_POST["username"]===$adminuser && $_POST["password"]===$adminpassword){
		$_SESSION["adminuser".$l1] = [];
	}
}


if (isset($_SESSION["adminuser".$l1])) {
	header("location:admin.php");
	exit();
}

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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		<link href="libs/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
	</head>
	<body class="bg-gradient-dark">
		<div class="container">

			<div class="row justify-content-center">

				<div class="col-xl-10 col-lg-12 col-md-9">

					<div class="card o-hidden border-0 shadow-lg my-5">
						<div class="card-body p-0">
							<div class="row bg-gray-100">
							<?php if (isset($_POST["username"])) { echo "<div class='alert alert-danger' style='width:100%; text-align:center; margin-bottom:45px;'><b>Invalid user/password</b></div>"; } ?>	
								<div class="col-lg-6 d-none d-lg-block" style="text-align:center;"><img style="padding:20px; max-height: 100%; max-width: 100%; width: auto; height: auto; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;" src="img/logo.png"></div>
								<div class="col-lg-6">
									<div class="p-5">
										<div class="text-center">
											<h1 class="h4 text-gray-900 mb-4">ADMIN Login</h1>
										</div>
										<form class="user" id="login" method="post">
											<div class="form-group">
												<input type="text" class="form-control form-control-user" name="username" required placeholder="Enter Username..." autofocus>
											</div>
											<div class="form-group">
												<input type="password" class="form-control form-control-user" name="password" required placeholder="Password">
											</div>
											<div class="form-group"></div>
											<button type="submit" class="btn btn-primary btn-user btn-block"> Login </button>
										</form>
										<hr>
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
