<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require("config.php");
require("configpacks.php");
if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}
require("add1.php");
require("add2.php");

$thisurl=getURL();
function getURL()
{
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = rtrim(dirname($requestUri), '/') . '/';

    return $protocol . '://' . $host . $path;
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title> <?php echo $sitename; ?> </title>
		<!-- Custom fonts for this template -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="libs/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
		<style>
			.topgap {
				margin-top: 15px;
			}
			.topgap2 {
				margin-top: 20px;
			}
			.row-number {
				margin-right: 10px; /* Adjust as necessary */
				font-weight: bold;
			}			
		</style>
	</head>
	<body id="page-top">
		<!-- Page Wrapper -->
		<div id="wrapper"> <?php require_once("admin_menu_sidenav.php"); ?>
			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
				<!-- Main Content -->
				<div id="content">
					<!-- Begin Page Content -->
					<div class="container-fluid" style="margin-top:50px;">
						<!-- Page Heading -->
						<h1 class="h3 mb-2 text-gray-800">Pack Settings for "<?php echo $sitename ?>" </h1>
						<p class="mb-4">You can change your pack settings here.</p>
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Settings</h6>
							</div>
							<div class="card-body">
								<div class="row topgap">
									<div class="col-12">
										<div class="alert alert-secondary"><b>Settings For Warrior Plus</b></div>
									</div>
								</div>
								<form id="settingsForm">
									<div class="form-row topgap">
									
										<div class="col-4">
											<label>Description : </label>
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_1" value="<?php echo $wplus_pack_description_1; ?>">
										</div>
										<div class="col-2">
											<label>Credits : </label>
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_1" value="<?php echo $wplus_pack_credits_1; ?>">
										</div>
										<div class="col-3">
											<label>Vendor URL : </label>
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_1" value="<?php echo $wplus_pack_cart_url_1; ?>">
										</div>
										<div class="col-3">
											<label>Product ID : </label>
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_1" value="<?php echo $wplus_pack_product_id_1; ?>">
										</div>
									</div>

									<div class="form-row topgap">
									
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_2" value="<?php echo $wplus_pack_description_2; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_2" value="<?php echo $wplus_pack_credits_2; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_2" value="<?php echo $wplus_pack_cart_url_2; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_2" value="<?php echo $wplus_pack_product_id_2; ?>">
										</div>
									</div>
									
									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_3" value="<?php echo $wplus_pack_description_3; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_3" value="<?php echo $wplus_pack_credits_3; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_3" value="<?php echo $wplus_pack_cart_url_3; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_3" value="<?php echo $wplus_pack_product_id_3; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_4" value="<?php echo $wplus_pack_description_4; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_4" value="<?php echo $wplus_pack_credits_4; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_4" value="<?php echo $wplus_pack_cart_url_4; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_4" value="<?php echo $wplus_pack_product_id_4; ?>">
										</div>
									</div>									
									
									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_5" value="<?php echo $wplus_pack_description_5; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_5" value="<?php echo $wplus_pack_credits_5; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_5" value="<?php echo $wplus_pack_cart_url_5; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_5" value="<?php echo $wplus_pack_product_id_5; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_6" value="<?php echo $wplus_pack_description_6; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_6" value="<?php echo $wplus_pack_credits_6; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_6" value="<?php echo $wplus_pack_cart_url_6; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_6" value="<?php echo $wplus_pack_product_id_6; ?>">
										</div>
									</div>
									
									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_7" value="<?php echo $wplus_pack_description_7; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_7" value="<?php echo $wplus_pack_credits_7; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_7" value="<?php echo $wplus_pack_cart_url_7; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_7" value="<?php echo $wplus_pack_product_id_7; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_8" value="<?php echo $wplus_pack_description_8; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_8" value="<?php echo $wplus_pack_credits_8; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_8" value="<?php echo $wplus_pack_cart_url_8; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_8" value="<?php echo $wplus_pack_product_id_8; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_9" value="<?php echo $wplus_pack_description_9; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_9" value="<?php echo $wplus_pack_credits_9; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_9" value="<?php echo $wplus_pack_cart_url_9; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_9" value="<?php echo $wplus_pack_product_id_9; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="EG: 10k words for $20" maxlength="200" name="wplus_pack_description_10" value="<?php echo $wplus_pack_description_10; ?>">
										</div>
										<div class="col-2">
											<input type="number" class="form-control numonly" placeholder="0" name="wplus_pack_credits_10" value="<?php echo $wplus_pack_credits_10; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Vendor Tracking URL" name="wplus_pack_cart_url_10" value="<?php echo $wplus_pack_cart_url_10; ?>">
										</div>
										<div class="col-3">
											<input type="text" class="form-control" placeholder="Warrior Plus Product ID" name="wplus_pack_product_id_10" value="<?php echo $wplus_pack_product_id_10; ?>">
										</div>
									</div>									
									
									<div class="form-row topgap">
										<div class="col-9">
											
										</div>
										
										<div class="col-3">
											<button type="button" class="btn btn-primary" id="saveSettings" style="float:right;">
												<i class="fas fa-save"></i> Save Settings </button>
										</div>										
									</div>

									<div class="form-row topgap2">
										<div class="col-12">
											<div class="alert alert-secondary">
												<b>Platform Integration: </b>
												<br><br>
 												You should enter the WarriorPlus Security key using the "Settings" screen.
												<br><br>
												
												<b>Pack Settings:</b><br><br>
												You must first create a separate product/offer in Warrior Plus for each pack. 
												<br>
												<br>
												Set your IPN settings in Warrior Plus to point to: <br><br>
												Warrior+ :  <strong><?php echo($thisurl."ipn_credits_wplus.php"); ?></strong>
												<br>
												<br>
												The "Thank You" page you should direct your buyers to is : <strong><?php echo($thisurl."thankyoupack.php"); ?></strong>
												<br>
												<br>
												Then create up to 5 packs above (you don't have to use them all).
											</div>										
										</div>
									</div>
					

								</form>
							</div>
						</div>
					<!-- /.container-fluid -->
					</div>
				</div>
			<!-- End of Main Content -->
			<!-- Footer -->
			<footer class="sticky-footer bg-white">
				<div class="container my-auto">
					<div class="copyright text-center my-auto"><?php echo $footer; ?></div>
				</div>
			</footer>
			<!-- End of Footer -->
		</div>
		<!-- End of Content Wrapper -->
		</div>
		<!-- End of Page Wrapper -->
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		<!-- Logout Modal-->
		<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<a class="btn btn-primary" href="adminlogout.php">Logout</a>
					</div>
				</div>
			</div>
		</div>
		<div class="toast bg-gray-200" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; top: 1rem; right: 1rem;" id="toast">
			<div class="toast-header bg-gray-400">
				<strong class="mr-auto">
					<span id="toastHeader"></span>
				</strong>
				<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="toast-body">
				<span id="toastBody"></span>
			</div>
		</div>
		<!-- Bootstrap core JavaScript-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!-- Core plugin JavaScript-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js" integrity="sha512-0QbL0ph8Tc8g5bLhfVzSqxe9GERORsKhIn1IrpxDAgUsbBGz/V7iSav2zzW325XGd1OMLdL4UiqRJj702IeqnQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!-- Custom scripts for all pages-->
		<script src="libs/sbadmin2/js/sb-admin-2.min.js"></script>
		<!-- Page level plugins -->
		<script>
			$(document).ready(function() {
				var inputs = document.querySelectorAll('.numonly');
				inputs.forEach(function(input) {
					input.addEventListener('keydown', function(e) {
						if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
							(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
							(e.keyCode === 67 && (e.ctrlKey === true || e.metaKey === true)) || 
							(e.keyCode === 86 && (e.ctrlKey === true || e.metaKey === true)) || 
							(e.keyCode === 88 && (e.ctrlKey === true || e.metaKey === true)) || 
							(e.keyCode >= 35 && e.keyCode <= 39)) {
							return;
						}
						if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
							e.preventDefault();
						}
					});
				});
				$('input:not(#step4 input), #projectnameEdit').on('input', function() {
				  var inputValue = $(this).val();
				  var sanitizedValue = inputValue.replace(/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_\-=\,\.\/:$£€]/g, '');
				  $(this).val(sanitizedValue);
				});

				
			});
			$('#adminLogout').click(function() {
				$('#logoutModal').modal('show');
			});
			
			$('#saveSettings').click(function(){
				$.ajax({
					url: "AJAX_admin_updatepacksettings.php",
					dataType: 'text',
					data: $("form#settingsForm").serialize(),
					type: 'POST',
					success: function (response) {
						if (response=='OK'){
							$('#toastHeader').text('SAVED');
							$('#toastBody').text('Your settings have been saved!');
							$('#toast').toast('show');				
						}

					}

				});
			});
		</script>
	</body>
</html>