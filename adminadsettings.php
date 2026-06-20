<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require("config.php");
if (file_exists("configpacks.php")){require("configpacks.php");}
if (file_exists("configimgads.php")){require("configimgads.php");}
if (file_exists("configmenuads.php")){require("configmenuads.php");}
if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}

if (file_exists("add1.php")){require("add1.php");}
if (file_exists("add2.php")){require("add2.php");}


if (!isset($imglink1)){$imglink1="";}
if (!isset($imglink2)){$imglink2="";}
if (!isset($imglink3)){$imglink3="";}
if (!isset($imglink4)){$imglink4="";}
if (!isset($imglink5)){$imglink5="";}

if (!isset($menulink1)){$menulink1="";}
if (!isset($menulink2)){$menulink2="";}
if (!isset($menulink3)){$menulink3="";}
if (!isset($menulink4)){$menulink4="";}
if (!isset($menulink5)){$menulink5="";}

if (!isset($menutext1)){$menutext1="";}
if (!isset($menutext2)){$menutext2="";}
if (!isset($menutext3)){$menutext3="";}
if (!isset($menutext4)){$menutext4="";}
if (!isset($menutext5)){$menutext5="";}


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
						<div class="form-row">
							<div class="col-3">
								<h1 class="h3 mb-2 text-gray-800">Ad Settings for "<?php echo $sitename ?>" </h1>
								<p class="mb-4">You can change your Ad settings here.</p>
							</div>
							<div class="col-6" style="text-align:center">
								<a id="imgtestlink" href="" target="_BLANK"><img style="max-height:80px; max-width:928px;" src="" style="display:none;" id="imgtest"></a>
							</div>
						</div>
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Settings</h6>
							</div>
							<div class="card-body">
								<div class="row topgap">
									<div class="col-12">
										<div class="alert alert-secondary"><b>Banner Ad Settings (ideal size : 928px x 60px)</b></div>
									</div>
								</div>
								<form id="settingsForm">
									<div class="form-row topgap">
									
										<div class="col-4">
											<label>Ad Link : </label>
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="200" id="imglink1" name="imglink1" value="<?php echo $imglink1; ?>">
										</div>
										<div class="col-3">
										<label>Actions</label>
											<div style="display: flex; align-items: center;">
												<input type="file" id="fileUpload1" name="fileUpload1" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" onclick="document.getElementById('fileUpload1').click();" id="test"><i class="fas fa-file-upload"></i> Upload</button>
												<button type="button" class="btn btn-info" style="margin-left:5px;" onclick="imgTest(1);"><i class="fas fa-check-square"></i> Test</button>
												<button type="button" class="btn btn-danger" style="margin-left:5px;" onclick="adClear(1);"><i class="fas fa-trash-alt"></i> Clear</button>
											</div>
											
										</div>
										
									</div>

									<div class="form-row topgap">
									
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="200" id="imglink2" name="imglink2" value="<?php echo $imglink2; ?>">
										</div>
										<div class="col-3">
											<div style="display: flex; align-items: center;">
												<input type="file" id="fileUpload2" name="fileUpload2" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" onclick="document.getElementById('fileUpload2').click();" id="test"><i class="fas fa-file-upload"></i> Upload</button>
												<button type="button" class="btn btn-info" style="margin-left:5px;" onclick="imgTest(2);"><i class="fas fa-check-square"></i> Test</button>
												<button type="button" class="btn btn-danger" style="margin-left:5px;" onclick="adClear(2);"><i class="fas fa-trash-alt"></i> Clear</button>
											</div>
										</div>

									</div>
									
									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="200" id="imglink3" name="imglink3" value="<?php echo $imglink3; ?>">
										</div>
										<div class="col-3">
											<div style="display: flex; align-items: center;">
												<input type="file" id="fileUpload3" name="fileUpload3" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" onclick="document.getElementById('fileUpload3').click();" id="test"><i class="fas fa-file-upload"></i> Upload</button>
												<button type="button" class="btn btn-info" style="margin-left:5px;" onclick="imgTest(3);"><i class="fas fa-check-square"></i> Test</button>
												<button type="button" class="btn btn-danger" style="margin-left:5px;" onclick="adClear(3);"><i class="fas fa-trash-alt"></i> Clear</button>
											</div>
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="200" id="imglink4" name="imglink4" value="<?php echo $imglink4; ?>">
										</div>
										<div class="col-3">
											<div style="display: flex; align-items: center;">
												<input type="file" id="fileUpload4" name="fileUpload4" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" onclick="document.getElementById('fileUpload4').click();" id="test"><i class="fas fa-file-upload"></i> Upload</button>
												<button type="button" class="btn btn-info" style="margin-left:5px;" onclick="imgTest(4);"><i class="fas fa-check-square"></i> Test</button>
												<button type="button" class="btn btn-danger" style="margin-left:5px;" onclick="adClear(4);"><i class="fas fa-trash-alt"></i> Clear</button>
											</div>
										</div>
										
									</div>									
									
									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="200" id="imglink5" name="imglink5" value="<?php echo $imglink5; ?>">
										</div>
										<div class="col-3">
											<div style="display: flex; align-items: center;">
												<input type="file" id="fileUpload5" name="fileUpload5" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" onclick="document.getElementById('fileUpload5').click();" id="test"><i class="fas fa-file-upload"></i> Upload</button>
												<button type="button" class="btn btn-info" style="margin-left:5px;" onclick="imgTest(5);"><i class="fas fa-check-square"></i> Test</button>
												<button type="button" class="btn btn-danger" style="margin-left:5px;" onclick="adClear(5);"><i class="fas fa-trash-alt"></i> Clear</button>
											</div>
										</div>

										
									</div>
									<div class="row" style="margin-top:30px;">
										<div class="col-12">
											<div class="alert alert-secondary"><b>Menu Text Settings</b></div>
										</div>
									</div>									

									<div class="form-row topgap">
										<div class="col-4">
											<label>Menu Text : </label>
											<input type="text" class="form-control" placeholder="Menu Text" maxlength="30" id="menutext1" name="menutext1" value="<?php echo $menutext1; ?>">
										</div>
										<div class="col-4">
											<label>Menu Link : </label>
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="500" id="menulink1" name="menulink1" value="<?php echo $menulink1; ?>">
										</div>
									</div>							

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="Menu Text" maxlength="30" id="menutext2" name="menutext2" value="<?php echo $menutext2; ?>">
										</div>
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="500" id="menulink2" name="menulink2" value="<?php echo $menulink2; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="Menu Text" maxlength="30" id="menutext3" name="menutext3" value="<?php echo $menutext3; ?>">
										</div>
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="500" id="menulink3" name="menulink3" value="<?php echo $menulink3; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="Menu Text" maxlength="30" id="menutext4" name="menutext4" value="<?php echo $menutext4; ?>">
										</div>
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="500" id="menulink4" name="menulink4" value="<?php echo $menulink4; ?>">
										</div>
									</div>

									<div class="form-row topgap">
										<div class="col-4">
											<input type="text" class="form-control" placeholder="Menu Text" maxlength="30" id="menutext5" name="menutext5" value="<?php echo $menutext5; ?>">
										</div>
										<div class="col-4">
											<input type="text" class="form-control" placeholder="https://myaffiliatelink.com" maxlength="500" id="menulink5" name="menulink5" value="<?php echo $menulink5; ?>">
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
		
			document.addEventListener("DOMContentLoaded", function() {
				document.body.addEventListener('change', function(event) {
					if (event.target.matches('.fileUploader')) {
						uploadFile(event.target.id);
					}
				});
			});

			function uploadFile(id) {
				let fileInput = document.getElementById(id);
				let file = fileInput.files[0];
				let formData = new FormData();
				formData.append('file', file);
				formData.append('fileId', id);  // Sending the id of the file input to the server
				fetch('AJAX_admin_uploadadimage.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					if (data.status === 'success') {
						alert('Upload successful: ' + data.message);
//						let preview = document.getElementById('preview'); // Note: You might want to change this to dynamically select the correct preview image based on the id.
//						preview.src = URL.createObjectURL(file);
//						preview.hidden = false;                
					} else {
						alert('Upload failed: ' + data.message);
					}
				})
				.catch(error => {
					alert('An error occurred: ' + error);
				});
			}
			
			$(document).ready(function() {
				$('input:not(.linkurl input)').on('input', function() {
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
					url: "AJAX_admin_updateads.php",
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
			

			function imgTest(imagenum){

				$.ajax({
					url: 'image_ads/'+imagenum+'.png',
					type: 'HEAD',
					error: function() {
						alert("There is no image file for this ad!");
					},
					success: function() {
						let imglink=$('#imglink'+imagenum).val().trim();
						if (imglink==''){
							$("#imgtestlink").removeAttr("href").css({
								"pointer-events": "none"
							});
						} else {
							$("#imgtestlink").attr("href", imglink).css({  
								"pointer-events": "auto"
							});						
						}						
						$('#imgtest').attr('src','image_ads/'+imagenum+'.png');
						$('#imgtest').show();
					}
				});
			}			


			function adClear(adnum) {
				$('#imgtest').attr('src','');
				$('#imgtest').hide();
				$('#imglink'+adnum).val("");
				$.ajax({
					url: 'AJAX_admin_clearad.php',
					type: 'POST',
					data: { adnum: adnum },
					success: function(response) {
						alert ("Ad cleared - don't forget to save");
					}
				}); 
			}

		</script>
	</body>
</html> 