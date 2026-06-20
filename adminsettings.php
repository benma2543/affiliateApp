<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_cache_limiter('nocache');
session_start();
clearstatcache();
if (function_exists('opcache_invalidate')) {
    opcache_invalidate('config.php', true);
}

require('config.php');
require("menucolor.php");

if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}
require("add1.php");
if (file_exists("add2.php")){require("add2.php");}else{$add2=0;}
$thisurl=getURL();
$thishost=getHost();

function getURL()
{
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = rtrim(dirname($requestUri), '/') . '/';
	
    return $protocol . '://' . $host . $path;
}

function getHost()
{
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = rtrim(dirname($requestUri), '/') . '/';
	
    return $host;
}


$savemessage="0";
if (isset($_GET["s"])){$savemessage=$_GET["s"];}

$uploadto=0;
if ($thishost=="yoursassapp.com" || $thishost=="exclusivesoftwarelab.online" || $thishost="exclusivesoftwarelab.store" || $thishost="premiumsaasapp.com" || $thishost="exclusivesaaslab.com" || $thishost="exclusivesaaslabs.com"){
	$uploadto=1;
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
						<h1 class="h3 mb-2 text-gray-800">Settings for "<?php echo $sitename ?>" </h1>
						<p class="mb-4">You can change your settings here.</p>
						<input id="savemessage" value="<?php echo $savemessage ?>" style="display:none;">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Settings</h6>
							</div>
							<input id="add2" style="display:none;" value="<?php echo $add2; ?>">
							<div class="card-body">
								<div class="row topgap">
									<div class="col-12">
										<div class="alert alert-danger"><b>WARNING: </b>If you change your admin username and password you will automatically be logged out.</div>
									</div>
								</div>
								<form id="settingsForm">
									<div class="form-row topgap">
										<div class="col-3">
											<label>Terms Link : </label>
											<input type="text" class="form-control" placeholder="Terms Link (if required)" name="termslink" value="<?php echo $termslink ?>">
										</div>
										<div class="col-3">
											<label>Support Link : </label>
											<input type="text" class="form-control" placeholder="Support Desk Link (if required)" name="supportlink" value="<?php echo $supportlink ?>">
										</div>
										<div class="col-3">
											<label>Support Email : </label>
											<input type="email" class="form-control" placeholder="Support Email (if required)" name="supportemail" value="<?php echo $supportemail ?>">
										</div>
										<div class="col-3">
											<label>Logout Redirect : </label>
											<input type="text" class="form-control" placeholder="Logout redirect URL (usually login.php)" name="logoutredirect" value="<?php echo $logoutredirect ?>">
										</div>
									</div>
									<div class="form-row topgap">
										<div class="col-3">
											<label>Admin Username : </label>
											<input type="text" class="form-control" placeholder="" required="required" name="adminuser" value="<?php echo $adminuser ?>">
										</div>
										<div class="col-3">
											<label>Admin Password : </label>
											<input type="password" class="form-control" placeholder="" required="required" name="adminpassword" value="<?php echo $adminpassword ?>">
										</div>
										<div class="col-3" style="<?php if($add1==0 && $add2==0){echo 'display:none;';} ?>">
											<label>W+ Security Key : </label>
											<input type="text" class="form-control" placeholder="Enter your key" name="wpluskey" value="<?php echo $wpluskey; ?>">
										</div>
										<div class="col-3" style="<?php if($add1==0 && $add2==0){echo 'display:none;';} ?>">
											<label>JVZoo JVZIPN Secret Key : </label>
											<input type="text" class="form-control" placeholder="Enter your key" name="jvzkey" value="<?php echo $jvzkey; ?>">
										</div>	
									</div>
									<div class="form-row topgap">
										<div class="col-1">
											<span id="mkmode1" style="<?php if ($add2==1){echo 'display:none;';} ?>">
												<label>API Key Mode : </label>
												<select class="form-control" id="masterkeymode" name="masterkeymode" >
													<option <?php if ($masterkeymode==false){echo "selected";} ?> value="false">Users Key </option>
													<option <?php if ($masterkeymode==true){echo "selected";} ?> value="true">My Key </option>
												</select>
											</span>
											<span id="mkmode2" style="<?php if ($add2==0){echo 'display:none;';} ?>">
												<label>API Key Mode : </label>
												<select class="form-control" id="" name="" disabled="disabled">
													<option value="false">CREDITS MODE</option>
												</select>
											</span>
										</div>
										<div class="col-1">
											<label>Master API Key : </label>
											<input type="text" class="form-control" placeholder="Enter your master API key" name="masterapikey" value="<?php echo $masterapikey ?>">
										</div>
										<div class="col-1" style="<?php if($add2==0){echo 'display:none;';} ?>">
											<label>Initial Credits : </label>
											<input type="number" class="form-control numonly" placeholder="1000" id="initial_credits" name="initial_credits" value="<?php echo $initial_credits; ?>">
										</div>
										<div class="col-1">
											<label>Credits System : </label>
											<select class="form-control" id="toggleCredits" name="toggleCredits" >
												<option <?php if ($add2==0){echo "selected";} ?> value="0">Off</option>
												<option <?php if ($add2==1){echo "selected";} ?> value="1">On</option>
											</select>
										</div>
										<div class="col-1">
											<label>Show Register : </label>
											<select class="form-control" id="showreg" name="showreg" >
												<option <?php if ($showreg==0){echo "selected";} ?> value="0">No</option>
												<option <?php if ($showreg==1){echo "selected";} ?> value="1">Yes</option>
											</select>
										</div>
										<div class="col-1">
											<label>Color Scheme : </label>
											<select class="form-control" id="menucolor" name="menucolor" >
												<option <?php if ($menucolorselect==0){echo "selected";} ?> class="bg-gradient-primary" style="color:#FFF;" value="0">Marine Blue</option>
												<option <?php if ($menucolorselect==3){echo "selected";} ?> class="bg-gradient-info" style="color:#FFF;" value="3">Aqua Blue</option>
												<option <?php if ($menucolorselect==2){echo "selected";} ?> class="bg-gradient-success" style="color:#FFF;" value="2">Leafy Green</option>
												<option <?php if ($menucolorselect==4){echo "selected";} ?> class="bg-gradient-warning" style="color:#FFF;" value="4">Sunshine Orange</option>
												<option <?php if ($menucolorselect==5){echo "selected";} ?> class="bg-gradient-danger" style="color:#FFF;" value="5">Bold Red</option>
												<option <?php if ($menucolorselect==6){echo "selected";} ?> class="bg-gradient-dark" style="color:#FFF;" value="6">Inky Dark</option>
												<option <?php if ($menucolorselect==1){echo "selected";} ?> class="bg-gradient-secondary" style="color:#FFF;" value="1">Slate Gray</option>
												<option <?php if ($menucolorselect==7){echo "selected";} ?> class="bg-gradient-light" style="color:#222;" value="7">Frosty White</option>
												
											</select>
										</div>
										
										<div class="col-1">
											<label>AI Model : </label>
											<input type="text" class="form-control" placeholder="Enter Model" name="ainame" id="ainame" value="<?php if (isset($ainame) && $ainame!=""){echo $ainame;} else {echo 'gpt-3.5-turbo';} ?>">											
										</div>
										
										
										<div class="col-1">
											<label>Preset AI : </label>
											<select class="form-control" id="presetai" name="presetai" >
												<option selected="selected" value="0">gpt-3.5-turbo</option>
												<option value="1">gpt-4o</option>
											</select>
										</div>
										
										<div class="col-1" style="<?php if ($uploadto==1){echo 'display:none;';} ?>">
											<label>HTML Location : </label>
											<select class="form-control" id="htmlLocation" name="htmlLocation" >
												<option <?php if ($uploadto==1){echo 'selected="selected"';} ?> value="0">Here</option>
												<option value="1">Root</option>
											</select>
										</div>
										<div class="col-1">
											<label>Upload HTML :</label>
											<div style="display: flex; align-items: center;">
												<input type="file" id="HTMLUploadButton" name="HTMLUploadButton" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" style="width:100%;" onclick="document.getElementById('HTMLUploadButton').click();" id="test"><i class="fas fa-file-upload"></i> Upload</button>
											</div>											
										</div>
										<div class="col-1">
											<label>Logo :</label>
											<div style="display: flex; align-items: center;">
												<input type="file" id="logoUploadButton" name="logoUploadButton" class="fileUploader" style="display: none;" />
												<button type="button" class="btn btn-success" style="width:100%;" onclick="document.getElementById('logoUploadButton').click();" id="test"><i class="fas fa-file-image"></i> Upload</button>
											</div>
										</div>
										<div class="col-1">
											<label>&nbsp;</label>
											<br>
											<button type="button" class="btn btn-primary" style="float:right;" id="saveSettings">
												<i class="fas fa-save"></i> Save All</button>
										</div>										
									</div>
									<div class="form-row topgap">
										<div class="col-3" style="display:none;">
											<label>AI Engine : </label>
											<select class="form-control" name="aiengine">
												<option <?php if ($aiengine=="0"){echo "selected";} ?> value="0">ChatGPT 3.5 (Recommended)</option>
												<option <?php if ($aiengine=="1"){echo "selected";} ?> value="1">ChatGPT 4 </option>
											</select>
										</div>										
									</div>

									<div class="form-row topgap2" style="<?php if($add1==0 && $add2==0){echo 'display:none;';} ?>">
										<div class="col-12">
											<div class="alert alert-secondary">
												<b>Platform Integration: </b>
												<br><br>
												<?php
													if ($add1==1 && $add2==0) {echo "You have the WarriorPlus / JVZoo upgrade installed.";}
													if ($add1==0 && $add2==1) {echo "You have the Credit System upgrade installed.";}
													if ($add1==1 && $add2==1) {echo "You have the WarriorPlus / JVZoo upgrade AND the Credit System upgrade installed.";}
												?>
 												You should enter the Security/Secret key of the platform you wish to sell on above - Use both if you want (W+ only for Credit Packs).
												<br><br>
												<span style="<?php if($add1==0){echo 'display:none;';} ?>">
												<b>Instructions for W+/JVZoo Upgrade:</b><br>
												Then in your product setup on the platform you must set the IPN to point to the following :
												<br>
												Warrior+ :  <strong><?php echo($thisurl."ipn_wplus.php"); ?></strong>
												<br>
												JVZoo : <strong><?php echo($thisurl."ipn_jvzoo.php"); ?></strong>
												<br>
												The "Thank You" page you should direct your buyers to is : <strong><?php echo($thisurl."thankyou.php"); ?></strong>
												</span>
												<span style="<?php if($add2==0){echo 'display:none;';} ?>">
												<br><br><b>Instructions for Credits System Upgrade:</b><br>
												You should configure your platform keys above and create your "packs" in the "Pack Settings" menu.
												</span>												
											</div>										
										</div>
									</div>
									
									<div class="form-row topgap2" style="display:none;">
										<div class="col-12">
											<div class="alert alert-secondary">
												<b>VERY IMPORTANT NOTE: </b>ChatGPT 3.5 is recommened for the best balance of speed, cost and availability. You may, if you wish, change to GPT4 using the options above <i>HOWEVER</i> please note the following before you do so:
												<br><br>
												GPT-4 is still in BETA and is NOT available to all users.  If you are allowing your users to supply their own API key and you have selected GPT-4, please be aware that if their API access does not include access to GPT-4 then the system will not work for them.  The combination of allowing users to supply their API key and selecting GPT-4 is not recommended at the moment until OpenAI take GPT-4 out of Beta.
												<br><br>
												If YOU are supplying the API key in "Master API Key Mode" and you are sure that YOU have GPT-4 Beta access via API then also be aware that the cost of using GPT-4 is significantly more than GPT-3 and you should bear this in mind.
												<br><br>
												Other considerations are ones of speed - GPT-4 is often slower then 3 and you may need to manually increase your Apache and PHP timeout limits on your server - Your server support will be able to help with this (on shared or low tier hosting this may not be possible).
												<br><br>
												Also please bear in mind that due to GPT-4 being in Beta their may be more glitches and errors than earlier models.  
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
			
			$('#presetai').change(function(){
				var presetai=$(this).val();
				if (presetai==0){
					$('#ainame').val('gpt-3.5-turbo');
				} else {
					$('#ainame').val('gpt-4o');
				}
					
			});

			$(document).ready(function() {
				if($('#savemessage').val()=='1'){
					$('#toastHeader').text('SAVED');
					$('#toastBody').text('Your settings have been saved!');
					$('#toast').toast('show');	
				}
				document.body.addEventListener('change', function(event) {
					if (event.target.id === 'logoUploadButton') {
						uploadLogo(event.target.id);
					}
				});
				document.body.addEventListener('change', function(event) {
					if (event.target.id === 'HTMLUploadButton') {
						uploadHTML(event.target.id);
					}
				});
			});
			
			
			function uploadLogo(id) {
				let fileInput = document.getElementById(id);
				let file = fileInput.files[0];
				let formData = new FormData();
				formData.append('file', file);
				formData.append('fileId', id);  
				fetch('AJAX_admin_uploadlogo.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					if (data.status === 'success') {
						alert('Upload successful: ' + data.message);
						var img = $('img[src$="logo.png"]');
						var newSrc = img.attr('src') + '?' + new Date().getTime();
						img.attr('src', newSrc);
					} else {
						alert('Upload failed: ' + data.message);
					}
				})
				.catch(error => {
					alert('An error occurred: ' + error);
				});
			}

			function uploadHTML(id) {
				let fileInput = document.getElementById(id);
				let file = fileInput.files[0];
				let formData = new FormData();
				formData.append('file', file);
				formData.append('fileId', id);

				// Get the value of the select box with id "htmlLocation"
				let htmlLocation = document.getElementById('htmlLocation').value;
				formData.append('htmlLocation', htmlLocation);

				fetch('AJAX_admin_uploadhtml.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					if (data.status === 'success') {
						alert('Upload successful: ' + data.message);
						var img = $('img[src$="logo.png"]');
						var newSrc = img.attr('src') + '?' + new Date().getTime();
						img.attr('src', newSrc);
					} else {
						alert('Upload failed: ' + data.message);
					}
				})
				.catch(error => {
					alert('An error occurred: ' + error);
				});
			}

			
			$('#toggleCredits').change(function(){
				var toggleCredits=$(this).val();
				if (toggleCredits==1){
					$('#mkmode1').hide();
					$('#mkmode2').show();
				} else {
					$('#mkmode1').show();
					$('#mkmode2').hide();
				}
			});
			$('#adminLogout').click(function() {
				$('#logoutModal').modal('show');
			});
			
			$('#saveSettings').click(function(){
				if ($('#add2').val()==1){$('#masterkeymode').prop('disabled', false);}
				$.ajax({
					url: "AJAX_admin_updatesettings.php",
					dataType: 'text',
					data: $("form#settingsForm").serialize(),
					type: 'POST',
					success: function (response) {
						if (response=='OK'){
							window.location.href='adminsettings.php?s=1';
						}
						if (response=='ERROR_ADMINCREDS'){
							if ($('#add2').val()==1){$('#masterkeymode').prop('disabled', true);}
							$('#toastHeader').text('ERROR');
							$('#toastBody').text('Settings NOT saved - You must have both an admin username and password!');
							$('#toast').toast('show');				
						}
						if (response=="ADMINCHANGE") {
							if ($('#add2').val()==1){$('#masterkeymode').prop('disabled', true);}
							window.location.assign("adminlogout.php");
						}
					}

				});
			});
		</script>
	</body>
</html>