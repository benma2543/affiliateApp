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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
		<link rel="stylesheet" href="css/tabler-compat.css">
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
	<body class="antialiased">
		<div class="wrapper"> <?php require_once("admin_menu_sidenav.php"); ?>
			<div class="page-wrapper">
				<div class="page-body">
					<div class="container-xl py-4">
						<div class="form-row">
							<div class="col-12">
								<h2 class="page-title">Salespage Settings for "<?php echo $sitename ?>" </h2>
								<div class="text-muted mb-3">You can change your Salespage settings here.</div>
							</div>
						</div>
						<div class="card shadow-sm">
							<div class="card-header">
								<h5 class="card-title mb-0">Your page is located at this URL (click to open) : <a href="<?php echo $thisurl; ?>home.php" target="_BLANK"><?php echo $thisurl; ?>home.php</a>
									<button type="button" class="btn btn-primary" id="saveSettings" style="float:right">
									<i class="fas fa-save"></i> Save Page </button>
								</h5>
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
		<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
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
		<div class="toast bg-gray-200" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; top: 1rem; right: 1rem;" id="toast">
			<div class="toast-header bg-gray-400">
				<strong class="me-auto">
					<span id="toastHeader"></span>
				</strong>
				<button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body">
				<span id="toastBody"></span>
			</div>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
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
				new bootstrap.Modal(document.getElementById('logoutModal')).show();
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
							new bootstrap.Toast(document.getElementById('toast'), {delay:5000}).show();
						}

					}

				});
			});
			



		</script>
	</body>
</html> 