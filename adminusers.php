<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");
if (file_exists("add2.php")){require("add2.php");}else{$add2=0;}
if ($add2==0){$showcredits='display:none;';}else{$showcredits='';}

if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

$st = $db->prepare("
    SELECT 
        u.user_id, 
        u.user_name,
        u.user_email,
        u.user_creation,
        u.user_logins,
        u.user_status,
        COUNT(e.id) AS project_count
    FROM 
        users u
    LEFT JOIN 
        projects e ON u.user_id = e.user_id
    GROUP BY 
        u.user_id,
        u.user_name,
        u.user_email,
        u.user_creation,
        u.user_logins,
        u.user_status
");
$st = $db->prepare("
    SELECT 
        users.user_id,
        users.user_name,
        users.user_email,
        users.user_creation,
        users.user_logins,
        users.user_status,
		users.user_credits,
        COUNT(projects.user_id) as project_count
    FROM users
    LEFT JOIN projects ON users.user_id = projects.user_id
    GROUP BY users.user_id
");

$st->execute();
$user=[];
$results = $st->fetchAll();
$tableout="";
$multicredits="0";
if (isset($_GET["m"])){$multicredits=$_GET["m"];}
foreach ($results as $key => $value) {

    $human_readable_date = $value['user_creation'];
	$id=$results[$key]['user_id'];
	$credits=$results[$key]['user_credits'];
	if ($results[$key]['user_status']=="1") {
		$status="OK";
		$actionmenu='<span class="dropdown-item" style="cursor:pointer;" onclick="ban('.$id.',0,'.$credits.');"><i class="fas fa-user-times"></i> Ban</span>';
	} else {
		$status="Banned";
		$actionmenu='<span class="dropdown-item" style="cursor:pointer;" onclick="ban('.$id.',1,'.$credits.');"><i class="fas fa-user-check"></i> Unban</span>';
	}
	

    
	$action=<<<EOT
<div class="dropdown">
  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton$id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-bars"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton$id">
    $actionmenu
	<span class="dropdown-item" style="cursor:pointer;" onclick="del($id);"><i class="fas fa-user-slash"></i> Delete</span>
	<span class="dropdown-item" style="cursor:pointer;" onclick="newpass($id);"><i class="fas fa-key"></i> New Password</span>
    <span class="dropdown-item" style="cursor:pointer; $showcredits" onclick="credits($id,$credits);"><i class="fas fa-comment-dollar"></i> Credits</span>
  </div>
</div>	
EOT;

	
	$tableout.="<tr id='rowid".$results[$key]['user_id']."'><td>".$value["user_id"]."</td><td>".$value["user_name"]."</td><td>".$value["user_email"]."</td><td>".$human_readable_date."</td><td>".$value["user_logins"]."</td><td>".$value["project_count"]."</td><td style='".$showcredits."'>".$value["user_credits"]."</td><td>".$status."</td><td >".$action."</td></tr>";
	
}

$url="https://eslcore.com/l/ver.php?ver=".base64_encode($version)."&k=".$l1;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$ret = curl_exec($ch);
curl_close($ch);
$ret=base64_decode($ret);
$versionmessage="";
if ($ret!="OK"){
	$versionmessage='<div class="row topgap"><div class="col-12"><div class="alert alert-warning">'.$ret.'</div></div></div>';
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
	<link rel="icon" type="image/x-icon" href="favicon.ico">
    <title><?php echo $sitename; ?></title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="libs/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css" integrity="sha512-PT0RvABaDhDQugEbpNMwgYBCnGCiTZMh9yOzUsJHDgl/dMhD9yjHAwoumnUk3JydV3QTcIkNDuN40CJxik5+WQ==" crossorigin=	"anonymous" referrerpolicy="no-referrer" />

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

       <?php require_once("admin_menu_sidenav.php"); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                

                <!-- Begin Page Content -->
                <div class="container-fluid" style="margin-top:50px;"	>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Users</h1>
                    <p class="mb-4">You can manage your users here.</p>
					<input id="multicredits_storage" value="<?php echo($multicredits); ?>" style="display:none;">

                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
							<div class="input-group mb-3 float-right" style="max-width:35%; <?php echo $showcredits; ?>">
								<input id="multiCredits" maxlength="40" type="number" class="form-control numonly" style="" placeholder="Credits to add to all users!">
								<div class="input-group-append">
									<button onclick="multiCredits();" type="button" class="btn btn-primary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-comment-dollar"></i></span><span class="text">Add Credits</span></button>
								</div>
							</div>
                        </div>
                        <div class="card-body">
						<?php echo $versionmessage; ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created</th>
                                            <th># Logins</th>
                                            <th># Projects</th>
                                            <th style="<?php echo $showcredits; ?>">Credits</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created</th>
                                            <th># Logins</th>
                                            <th># Projects</th>
                                            <th style="<?php echo $showcredits; ?>">Credits</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                          
										
										<?php echo $tableout; ?>
									                                        
                                            
                                        
                                    </tbody>
                                </table>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Click "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="adminlogout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete User Modal-->
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutLabel">WARNING: DELETE USER!</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Click "DELETE" below if you are really want to delete this user.<br><br><b>IMPORTANT:</b> This is NOT reversible. Consider banning the user instead.</div>
				<input style="display:none;" id="deluserid" value="">
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" onclick="doDeluser();"; data-dismiss="modal">DELETE</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newpassModal" tabindex="-1" role="dialog" aria-labelledby="newpassLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">New Password</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
					<input id="newpassId" value="" style="display:none;">
					
					<div class="row">
						
						<div class="col-12">
							<label for="newpassPassword" class="">New Password</label>
							<input class="form-control" style="" id="newpassPassword">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-12">
						<i>Enter new password above and click the Set or Cancel buttons</i>
						</div>
					</div>
				</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button class="btn btn-primary" onclick="donewpass()" data-dismiss="modal"><i class="fas fa-rocket"></i> Set</button>
                </div>
            </div>
        </div>
    </div>

	<!-- Credits Modal-->
    <div class="modal fade" id="creditsModal" tabindex="-1" role="dialog" aria-labelledby="creditsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="creditsLabel">Manage Credits</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
					<input id="modalId" value="" style="display:none;">
					Managing Credits for : <span id="modalName"></span> (<span id="modalEmail"></span>).
					<br><br>
					<div class="row">
						<label for="modalCredits" class="col-sm-1 col-form-label">Credits</label>
						<div class="col-sm-6">
							<input class="form-control numonly" type="number" style="width:200px;" id="modalCredits">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-12">
						<i>Change the credits above and click the Modify or Cancel buttons</i>
						</div>
					</div>
				</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button class="btn btn-primary" onclick="modCredits()"><i class="fas fa-rocket"></i> Modify</button>
                </div>
            </div>
        </div>
    </div>

	<div class="toast bg-gray-200" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; top: 1rem; right: 1rem;" id="banToast">
	  <div class="toast-header bg-gray-400">
		<strong class="mr-auto"><span id="banToastHeader"></span></strong>

		<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="toast-body">
		<span id="banToastBody"></span>
	 </div>
	</div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js" integrity="sha512-0QbL0ph8Tc8g5bLhfVzSqxe9GERORsKhIn1IrpxDAgUsbBGz/V7iSav2zzW325XGd1OMLdL4UiqRJj702IeqnQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Custom scripts for all pages-->
    <script src="libs/sbadmin2/js/sb-admin-2.min.js"></script>

		<!-- Page level plugins -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js" integrity="sha512-OQlawZneA7zzfI6B1n1tjUuo3C5mtYuAWpQdg+iI9mkDoo7iFzTqnQHf+K5ThOWNJ9AbXL4+ZDwH7ykySPQc+A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
	$('#dataTable').DataTable({
	  "columnDefs": [
		{ "orderable": false, "targets": 8 }
	  ]
	});
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
	var multicredits_storage=$('#multicredits_storage').val();
	if (multicredits_storage>0) {
		$('#banToastHeader').text('Credits Added');
		$('#banToastBody').text(multicredits_storage+' credits have been added to ALL user accounts!');
		$('#banToast').toast('show');			
	}


});

$('#adminLogout').click(function(){
	$('#logoutModal').modal('show');

});

function multiCredits(){
	var credits=$('#multiCredits').val();
	if (credits>0){
		$.ajax({
			url: "AJAX_admin_multicredits.php",
			data: {credits:parseInt(credits)},
			type: 'POST',
			success: function (response) {
				window.location.assign('adminusers.php?m='+response);
			}
		});			
	}
	
}

function credits(id,credits) {
	var userTable = $('#dataTable').DataTable();
	var rowData = userTable.row('#rowid' + id).data();
	var name=rowData[1];
	var email=rowData[2];
	$('#modalId').val(id);
	$('#modalName').text(name);
	$('#modalEmail').text(email);
	$('#modalCredits').val(credits);
	$('#creditsModal').modal('show');

}

function modCredits(){
	
	var id = $('#modalId').val();
	var credits = $('#modalCredits').val();
	var userTable = $('#dataTable').DataTable();
	var origCredits = userTable.cell('#rowid' + id, 6).data();
	if (origCredits!=credits){
		$.ajax({
			url: "AJAX_admin_modcredits.php",
			data: {id:parseInt(id),credits:parseInt(credits)},
			type: 'POST',
			success: function (response) {
				userTable.cell('#rowid' + id, 6).data(credits).draw();
				$('#banToastHeader').text('Credits Modified');
				$('#banToastBody').text('You credit modification has been completed.');
				$('#banToast').toast('show');				
			}
		});			

	}
	$('#creditsModal').modal('hide');
}	

function del(id) {
	$('#deluserid').val(id);
	$('#delModal').modal('show');
}

function doDeluser() {
	var id=$('#deluserid').val();
	var userTable = $('#dataTable').DataTable();
	var rowId = userTable.row('[id=rowid'+id+']').index();
	$.ajax({
		url: "AJAX_admin_del.php",
		data: {id:parseInt(id)},
		type: 'POST',
		success: function (response) {
			userTable.row(rowId).remove().draw();
			$('#banToastHeader').text('Delete User');
			$('#banToastBody').text('This User Has Been Deleted!');
			$('#banToast').toast('show');			
		}
	});	

	
	
	/*
	if (banmode==0) { 
		userTable.cell({row:rowId, column: 7}).data("Banned").draw(false);
			var actionmenu='<span class="dropdown-item" style="cursor:pointer;" onclick="ban('+id+',1);"><i class="fas fa-user-check"></i> Unban</span>';
		
		userTable.cell({row:rowId, column: 8}).data('<div class="dropdown"><button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton'+id+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   <i class="fas fa-bars"></i></button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton'+id+'">'+actionmenu+'<span class="dropdown-item" style="cursor:pointer;" onclick="credits('+id+','+credits+');"><i class="fas fa-comment-dollar"></i> Credits</span></div></div>').draw(false);
		
		
		$('#banToastHeader').text('Ban User');
		$('#banToastBody').text('This User Has Been Banned!');
		$('#banToast').toast('show');
	} else {
		userTable.cell({row:rowId, column: 7}).data("OK").draw(false);
		var actionmenu='<span class="dropdown-item" style="cursor:pointer;" onclick="ban('+id+',0);"><i class="fas fa-user-times"></i> Ban</span>';
		userTable.cell({row:rowId, column: 8}).data('<div class="dropdown"><button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton'+id+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   <i class="fas fa-bars"></i></button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton'+id+'">'+actionmenu+'<span class="dropdown-item" style="cursor:pointer;" onclick="credits('+id+','+credits+');"><i class="fas fa-comment-dollar"></i> Credits</span></div></div>').draw(false);
		
		
		$('#banToastHeader').text('Unban User');
		$('#banToastBody').text('This User Has Been Un-Banned!');
		$('#banToast').toast('show');
	}
	$.ajax({
		url: "AJAX_admin_ban.php",
		data: {id:parseInt(id),banmode:parseInt(banmode)},
		type: 'POST',
		success: function (response) {
			
		}
	});	
	*/
}	


function ban(id,banmode,credits) {
	var userTable = $('#dataTable').DataTable();
	var rowId = userTable.row('[id=rowid'+id+']').index();
	if (banmode==0) { 
		userTable.cell({row:rowId, column: 7}).data("Banned").draw(false);
			var actionmenu='<span class="dropdown-item" style="cursor:pointer;" onclick="ban('+id+',1);"><i class="fas fa-user-check"></i> Unban</span>';
		
		userTable.cell({row:rowId, column: 8}).data('<div class="dropdown"><button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton'+id+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   <i class="fas fa-bars"></i></button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton'+id+'">'+actionmenu+'<span class="dropdown-item" style="cursor:pointer;" onclick="credits('+id+','+credits+');"><i class="fas fa-comment-dollar"></i> Credits</span></div></div>').draw(false);
		
		
		$('#banToastHeader').text('Ban User');
		$('#banToastBody').text('This User Has Been Banned!');
		$('#banToast').toast('show');
	} else {
		userTable.cell({row:rowId, column: 7}).data("OK").draw(false);
		var actionmenu='<span class="dropdown-item" style="cursor:pointer;" onclick="ban('+id+',0);"><i class="fas fa-user-times"></i> Ban</span>';
		userTable.cell({row:rowId, column: 8}).data('<div class="dropdown"><button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton'+id+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   <i class="fas fa-bars"></i></button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton'+id+'">'+actionmenu+'<span class="dropdown-item" style="cursor:pointer;" onclick="credits('+id+','+credits+');"><i class="fas fa-comment-dollar"></i> Credits</span></div></div>').draw(false);
		
		
		$('#banToastHeader').text('Unban User');
		$('#banToastBody').text('This User Has Been Un-Banned!');
		$('#banToast').toast('show');
	}
	$.ajax({
		url: "AJAX_admin_ban.php",
		data: {id:parseInt(id),banmode:parseInt(banmode)},
		type: 'POST',
		success: function (response) {
			
		}
	});	
}

function newpass(id){
	$('#newpassId').val(id);
	$('#newpassPassword').val('');
	$('#newpassModal').modal('show');
}
	
function donewpass(){
	var id=$('#newpassId').val();
	var newpass=$('#newpassPassword').val();
	if (newpass!=""){
		$.ajax({
			url: "AJAX_admin_newpass.php",
			data: {id:parseInt(id),newpass:newpass},
			type: 'POST',
			success: function (response) {
				$('#banToastHeader').text('Password Changed');
				$('#banToastBody').text('Password changed for this user!');
				$('#banToast').toast('show');
			}
		});
	}
}
</script>

</body>

</html>