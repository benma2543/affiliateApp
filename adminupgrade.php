<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require("config.php");
if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}
require("addons.php");

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
				margin-top: 25px;
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
						<h1 class="h3 mb-2 text-gray-800">Upgrade Feature</h1>
						<p class="mb-4">You can upgrade the software and upload addons here.</p>

						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Upgrade / Addon</h6>
							</div>
							<div class="card-body">
								<div class="row topgap"><div class="col-12"><div class="alert alert-warning"><b>NOTE:</b> As soon as you upload an upgrade or addon it will start to install.</div></div></div>
									<div class="form-row topgap">
										<div class="col-3">
										
											<form name="uploadform" id="uploadform" method="post"><input type="file" id="upload-file" class="filestyle" /></form>
										</div>
									</div>
									<div class="row topgap2">
										<div class="col-6">
											<div class="card shadow mb4">
												<div class="card-header">
													<h6 class="m-0 font-weight-bold text-primary; color:#777">Already installed Addons / Upgrades</h6>
												</div>
												<div class="card-body">
													<?php echo str_replace("|","<br>",$addons); ?>
												</div>
											</div>
										</div>
									</div>
								
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js" integrity="sha512-HfRdzrvve5p31VKjxBhIaDhBqreRXt4SX3i3Iv7bhuoeJY47gJtFTRWKUpjk8RUkLtKZUhf87ONcKONAROhvIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>		
		<!-- Page level plugins -->
		<script>
			
			$("#upload-file").filestyle({htmlIcon: '<i class="fas fa-rocket"></i>',text: " Upload",input:false,btnClass: "btn-primary"});

			$('#upload-file').change(function(){
			   $('#uploadform').submit();
			});
			$('#uploadform').submit(function(event){
				event.preventDefault(); 
				
				var file_data = $('#upload-file').prop('files')[0];   
				var form_data = new FormData();                  
				form_data.append('file', file_data);

				$.ajax({
					url: 'AJAX_admin_uploadupgrade.php', 
					dataType: 'text',  
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					success: function(response){
						if (response=="OK"){
							var filename=file_data.name;
							var filename = filename.replace(/\.[^\.]+$/, '.php');
							window.location.href = filename;
						} else {
							alert(response);
						}
					}
				 });
			});


	
			$(document).ready(function() {
			});
			$('#adminLogout').click(function() {
				$('#logoutModal').modal('show');
			});
			
			$('#saveSettings').click(function(){
				$.ajax({
					url: "AJAX_admin_updatesettings.php",
					dataType: 'text',
					data: $("form#settingsForm").serialize(),
					type: 'POST',
					success: function (response) {
						if (response=='OK'){
							$('#toastHeader').text('SAVED');
							$('#toastBody').text('Your settings have been saved!');
							$('#toast').toast('show');				
						}
						if (response=='ERROR_ADMINCREDS'){
							$('#toastHeader').text('ERROR');
							$('#toastBody').text('Settings NOT saved - You must have both an admin username and password!');
							$('#toast').toast('show');				
						}
						if (response=="ADMINCHANGE") {
							window.location.assign("adminlogout.php");
						}
					}

				});
			});
		</script>
	</body>
</html>