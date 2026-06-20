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
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> <?php echo $sitename; ?> </title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
		<link rel="stylesheet" href="css/tabler-compat.css">
		<style>
			.topgap { margin-top: 15px; }
			.topgap2 { margin-top: 25px; }
		</style>
	</head>
	<body class="antialiased">
		<div class="wrapper"> <?php require_once("admin_menu_sidenav.php"); ?>
			<div class="page-wrapper">
				<div class="page-body">
					<div class="container-xl py-4">
						<h2 class="page-title">Upgrade Feature</h2>
						<div class="text-muted mb-3">Upgrade the software and upload addons here.</div>

						<div class="card shadow-sm">
							<div class="card-header">
								<h5 class="card-title mb-0">Upgrade / Addon</h5>
							</div>
							<div class="card-body">
								<div class="row topgap"><div class="col-12"><div class="alert alert-warning"><b>NOTE:</b> As soon as you upload an upgrade or addon it will start to install.</div></div></div>
								<div class="row g-3 topgap">
									<div class="col-3">
										<form name="uploadform" id="uploadform" method="post"><input type="file" id="upload-file" class="form-control" /></form>
									</div>
								</div>
								<div class="row topgap2">
									<div class="col-6">
										<div class="card shadow-sm">
											<div class="card-header">
												<h5 class="card-title mb-0">Already installed Addons / Upgrades</h5>
											</div>
											<div class="card-body">
												<?php echo str_replace("|","<br>",$addons); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<footer class="footer sticky-footer bg-white">
					<div class="container text-center py-2"><?php echo $footer; ?></div>
				</footer>
			</div>
		</div>
		<a class="scroll-to-top" href="#">
			<i class="fas fa-angle-up"></i>
		</a>
		<!-- Logout Modal -->
		<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
						<a class="btn btn-primary" href="adminlogout.php">Logout</a>
					</div>
				</div>
			</div>
		</div>
		<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1090;">
			<div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-header">
					<strong class="me-auto" id="toastHeader"></strong>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
				<div class="toast-body" id="toastBody"></div>
			</div>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
		<script>
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
							var filename = file_data.name.replace(/\.[^\.]+$/, '.php');
							window.location.href = filename;
						} else {
							alert(response);
						}
					}
				});
			});

			$('#adminLogout').click(function() {
				new bootstrap.Modal(document.getElementById('logoutModal')).show();
			});
		</script>
	</body>
</html>
