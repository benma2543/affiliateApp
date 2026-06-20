<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require("config.php");

if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}

if (file_exists("add1.php")){require("add1.php");}
if (file_exists("add2.php")){require("add2.php");}


require_once("configpage.php");
$productdetails=base64_decode($productdetails);
$cartinfo=base64_decode($cartinfo);
$buttoncode=base64_decode($buttoncode);
$footerhtml=base64_decode($footerhtml);




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
		    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400;500;600&display=swap" rel="stylesheet">
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
							<div class="col-12">
								<h1 class="h3 mb-2 text-gray-800">Salespage Settings for "<?php echo $sitename ?>" </h1>
								<p class="mb-4">You can change your Salespage settings here.</p>
							</div>
						</div>
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold">Your page is located at this URL (click to open) : <a href="<?php echo $thisurl; ?>home.php" target="_BLANK"><?php echo $thisurl; ?>home.php</a>
									<button type="button" class="btn btn-primary" id="saveSettings" style="float:right">
									<i class="fas fa-save"></i> Save Page </button>
								</h6>
								<textarea id="productdetails_store" style="display:none;"><?php echo $productdetails; ?></textarea> 
								<textarea id="cartinfo_store" style="display:none;"><?php echo $cartinfo; ?></textarea>
										
										
										
							</div>
							<div class="card-body">
								<form id="settingsForm">
									<div class="form-row topgap">
										<div class="col-2">
											<label>Page Title</label>
											<input type="text" class="form-control" placeholder="My Page Title" maxlength="200" id="pagetitle" name="pagetitle" value="<?php echo $pagetitle; ?>">
										</div>
										<div class="col-2">
											<label>Product Name</label>
											<input type="text" class="form-control" placeholder="My Product Name" maxlength="200" id="productname" name="productname" value="<?php echo $productname; ?>">
										</div>
										<div class="col-2">
											<label>Footer HTML</label>
											<textarea type="text" class="form-control" style="height:38px;" placeholder="<footer HTML>"  id="footerhtml" name="footerhtml" ><?php echo $footerhtml; ?></textarea>
										</div>
										<div class="col-2">
											<label>Button Code</label>
											<textarea type="text" class="form-control" style="height:38px;" placeholder="<buttoncode>" id="buttoncode" name="buttoncode" ><?php echo $buttoncode; ?></textarea>
										</div>
										<div class="col-2">
										<label>Hero Image</label>
											<div style="display: flex; align-items: center;">
												<input type="file" id="fileUpload1" name="fileUpload1" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" onclick="document.getElementById('fileUpload1').click();" id="test"><i class="fas fa-file-upload"></i> Upload</button>
											</div>
											
										</div>
									</div>
									
									<div class="form-row topgap">
									
									</div>

									<div class="row topgap">
										<div class="col-8">
											<label>Product Information</label>
											<div id="productdetails_editorarea"></div>
										</div>
										<div class="col-4">
											<label>Cart Information</label>
											<div id="cartinfo_editorarea" style="height:100px;"></div>
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js" integrity="sha512-sWydClczl0KPyMWlARx1JaxJo2upoMYb9oh5IHwudGfICJ/8qaCyqhNTP5aa9Xx0aCRBwh71eZchgz0a4unoyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
				formData.append('fileId', id);  
				fetch('AJAX_admin_uploadimage.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					if (data.status === 'success') {
						alert('Upload successful: ' + data.message);
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
				
				tinymce.init({
					selector: '#productdetails_editorarea',
					menubar: false,
					promotion: false,
					branding: false,
					inline_styles : true,
					content_style: "p { color: black; font-size: 12pt; font-family: Verdana	; margin: 1rem auto; max-width: 1400px; }",	
					toolbar: "aligncenter alignjustify alignleft alignnone alignright| anchor | bold italic underline strikethrough | numlist bullist | emoticons | copy | cut | hr indent | language | lineheight | newdocument | outdent | paste pastetext | redo | selectall | searchreplace | undo | fullscreen",
					plugins: "wordcount, fullscreen, searchreplace, emoticons, lists",	  
					setup: function (productdetailseditor) {
					  productdetailseditor.on('init', function (e) {
						productdetailseditor.setContent($('#productdetails_store').val());
					  });
					}
				});
				tinymce.init({
					selector: '#cartinfo_editorarea',
					menubar: false,
					promotion: false,
					branding: false,
					inline_styles : true,
					content_style: "p { color: black; font-size: 12pt; font-family: Verdana	; margin: 1rem auto; max-width: 1400px; }",	
					toolbar: "aligncenter alignjustify alignleft alignnone alignright| anchor | bold italic underline strikethrough | numlist bullist |  emoticons | copy | cut | hr indent | language | lineheight | newdocument | outdent | paste pastetext | redo | selectall | searchreplace | undo | fullscreen",
					plugins: "wordcount, fullscreen, searchreplace, emoticons, lists",	  
					setup: function (cartinfoeditor) {
					  cartinfoeditor.on('init', function (e) {
						cartinfoeditor.setContent($('#cartinfo_store').val());
					  });
					}
				});
			});
			
			
			$('#adminLogout').click(function() {
				$('#logoutModal').modal('show');
			});
			
			$('#saveSettings').click(function(){
				var pagetitle=$('#pagetitle').val();
				var productname=$('#productname').val();
				var productdetails=encodeURIComponent(tinymce.get("productdetails_editorarea").getContent());
				var cartinfo=encodeURIComponent(tinymce.get("cartinfo_editorarea").getContent());
				var buttoncode=encodeURIComponent($('#buttoncode').val());
				var footerhtml=encodeURIComponent($('#footerhtml').val());
				var data = {
				  pagetitle: pagetitle,
				  productname: productname,
				  productdetails: productdetails,
				  cartinfo: cartinfo,
				  buttoncode: buttoncode,
				  footerhtml: footerhtml
				};					
				$.ajax({
					url: "AJAX_admin_salespagesettings.php",
					data: data,
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