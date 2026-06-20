<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("config.php");

require_once("user/protect.php");
require_once("user/user-lib.php");


$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
$stmt = $db->prepare("SELECT user_apikey FROM users WHERE user_id = :userid");
$stmt->bindParam(':userid', $userid);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
extract($result);



?>
<!DOCTYPE html>
<html lang="en">

<head>
	<style>
		.topgap {
			margin-top: 15px;
		}
		.topgap2 {
			margin-top: 20px;
		}

	</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
    <title><?php echo $sitename; ?></title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="libs/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

       <?php require_once("menu_sidenav.php"); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid" style="margin-top:50px;"	>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Settings</h1>
                    <p class="mb-4">You can view and change your settings here.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
							<button type="button" class="btn btn-primary btn-icon-split" style="float:right" id="saveSettings"><span class="icon text-white-50"><i class="fas fa-save"></i></span><span class="text">Save Settings</span></button>
														
                        </div>
                        <div class="card-body">
							<div class="row topgap">
								<div class="col-12">
									<div class="alert alert-danger"><b>WARNING: </b>If you change your email address you will automatically be logged out.</div>
								</div>
							</div>
							<div id="step1">
								<div class="row">
									<div class="col-2">
										<label>Your Name</label>
										<input type="text" class="form-control validate" disabled placeholder="" name="username" id="username" value="<?php echo $_SESSION["user".$l1]["user_name"]; ?>">
									</div>
									<div class="col-2">
										<label>Your Email</label>
										<input type="text" class="form-control validate" placeholder="" name="useremail" id="useremail" value="<?php echo $_SESSION["user".$l1]["user_email"]; ?>">
									</div>
									<div class="col-2">
										<label>Account Created</label>
										<input type="text" class="form-control validate" disabled placeholder=""  value="<?php echo $_SESSION["user".$l1]["user_creation"]; ?>">
									</div>
									<div class="col-2">
										<label>IP Address</label>
										<input type="text" class="form-control validate" disabled placeholder=""  value="<?php $ip="None"; if (!empty($_SERVER['HTTP_CLIENT_IP'])) {$ip = $_SERVER['HTTP_CLIENT_IP'];} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];} else {$ip = $_SERVER['REMOTE_ADDR'];} echo $ip ?>">
									</div>
									<div class="col-2 " style="<?php if ($masterkeymode==true){echo "display:none;";} ?>">
										<label>OpenAI API Key</label>
										<input type="text" class="form-control validate" placeholder="" name="userapi" id="userapi" value="<?php echo $result["user_apikey"]; ?>">
									</div>

								</div>
								

							</div>
		
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>



	<div class="toast bg-gray-400" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; top: 1rem; right: 1rem;" id="generalToast">
	  <div class="toast-header bg-gray-700">
		<strong class="mr-auto"><span id="generalToastHeader" style="color:#FFF;"></span></strong>
		<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
		  <span aria-hidden="true" style="color:#FFF;">&times;</span>
		</button>
	  </div>
	  <div class="toast-body">
		<span id="generalToastBody" style="color:#000"></span>
	 </div>
	</div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js" integrity="sha512-0QbL0ph8Tc8g5bLhfVzSqxe9GERORsKhIn1IrpxDAgUsbBGz/V7iSav2zzW325XGd1OMLdL4UiqRJj702IeqnQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Custom scripts for all pages-->
    <script src="libs/sbadmin2/js/sb-admin-2.min.js"></script>


<script>
$(document).ready(function() {
	
	let params = new URLSearchParams(window.location.search);
	if (params.get('s') === '1') {
		$('#generalToastHeader').text('SAVED');
		$('#generalToastBody').text('Your settings have been saved!');
		$('#generalToast').toast('show');
	}
	
  $('input').on('input', function() {
    var inputValue = $(this).val();
    var sanitizedValue = inputValue.replace(/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_@\-=\,\.]/g, '');
    $(this).val(sanitizedValue);
  });	
});

$('#saveSettings').click(function(){
	let data = {
		email: $('#useremail').val(),
		api: $('#userapi').val(),
	};

	$.ajax({
		url: "AJAX_user_updatesettings.php",
		dataType: 'text',
		type: 'POST',
		data: data, // pass the data object here
		success: function (response) {
			if (response == 'OK') {
				window.location.assign('usersettings.php?s=1');
			}
			if (response == "EMAILCHANGE") {
				window.location.assign("logout.php");
			}
		}
	});

});



</script>

</body>

</html>