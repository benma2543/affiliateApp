<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("config.php");

if (file_exists("add2.php")){require("add2.php");}else{$add2=0;}

if ($add2==1){require("configpacks.php"); $showcredits="";}else{$showcredits="display:none;";}

require_once("user/protect.php");
require_once("user/user-lib.php");

function getNextAdImage($adnumber) {
    $maxFiles = 5;
    $dir = 'image_ads/';
    $countChecked = 0;

    while ($countChecked < $maxFiles) {
        $filename = $dir . $adnumber . '.png';

        if (file_exists($filename)) {
            return $adnumber;
        }

        $adnumber = ($adnumber % $maxFiles) + 1;
        $countChecked++;
    }

    return 0;
}

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

if (file_exists("add4.php")){require("add4.php");}else{$add4=0;}
if ($add4==1){
	require("configimgads.php");
	$sql = "SELECT adnumber FROM users WHERE user_id = :userid";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $adnumber = $result['adnumber'];
	$nextad = getNextAdImage($adnumber);
}

$st = $db->prepare("SELECT id,project_name,date_created,last_edited,category FROM projects WHERE user_id=:userid");
$st->bindParam(':userid', $userid);
$st->execute();
$results = $st->fetchAll();
$tableout="";

foreach ($results as $key => $value) {
	$category="No Category";
	$catid=$value['category'];
	if($catid!=0){
		$st = $db->prepare("SELECT cat_name FROM categories WHERE cat_userid=:userid AND cat_id=:catid");
		$st->bindParam(':userid', $userid);
		$st->bindParam(':catid', $catid);
		$st->execute();
		$catresults = $st->fetch();
		if ($catresults){
			$category=$catresults["cat_name"];
		}

	}
	
	
    $id=$value["id"];
	$action=<<<EOT
<div class="dropdown">
  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton$id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-bars"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton$id">
    <span class="dropdown-item" style="cursor:pointer;" onclick="editproject($id);"><i class="fas fa-edit"></i> Edit</span>
    <span class="dropdown-item" style="cursor:pointer;" onclick="delproject($id);"><i class="fas fa-trash-alt"></i> Delete</span>
  </div>
</div>	
EOT;

	$rowbackground="";
	
	$tableout.="<tr style='".$rowbackground."' id='rowid".$id."'><td style='cursor:pointer;' onclick='editproject(".$id.")'>".$value["project_name"]."</td><td>".$category."</td><td>".$value["date_created"]."</td><td>".$value["last_edited"]."</td><td>".$action."</td></tr>";
	
}

$stmt = $db->prepare("SELECT cat_name FROM categories WHERE cat_userid = :userid");
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$catSelect="";
while ($row = $stmt->fetch()) {
    $catName = $row['cat_name'];
    $catSelect.='<option>'.$catName.'</option>';
}

try {
    $query = "SELECT user_credits FROM users WHERE user_id = :userid";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $usercredits = $result['user_credits'];
    } else {
        echo "No user found with the given user_id.";
    }

} catch(PDOException $e) {
    echo "Error getting credits left";
}
$packoutput="";
if($add2==1){
	for($i = 1; $i <= 10; $i++) {
		$description = 'wplus_pack_description_' . $i;
		$credits = 'wplus_pack_credits_' . $i;
		$cart= 'wplus_pack_cart_url_' . $i;
		$productid= 'wplus_pack_product_id_' . $i;
		$$productid = preg_replace('/^wso_/', '', $$productid);
		$carturl=$$cart."?s=".$userid."&co=".$$productid;
		if ($$description != "") {
			$packoutput.="<option value='".$carturl."'>".$$description."</option>";
			
		}
	}
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

	<style>
		.no-select {
			user-select: none;
		}
		.fake-input {
			user-select: none; /* This prevents the content from being selected */
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding-right: 10px;
		}
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
    <div id="wrapper">

       <?php require_once("menu_sidenav.php"); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                

                <!-- Begin Page Content -->
                <div class="container-fluid" style="margin-top:50px;"	>

                    <!-- Page Heading -->
					<div class="row" style="">
						<div class="col-3">
							<h1 class="h3 mb-2 text-gray-800">Projects</h1>
							<p class="mb-4">You can manage your projects here.</p>
						</div>
						<div class="col-6" style="text-align:center; <?php if ($nextad==0){echo "display:none;";} ?>">
							<a id="imgtestlink" href="<?php if ($nextad!=0){$nextImgLink = ${'imglink' . $nextad}; echo $nextImgLink;} ?>" style="<?php if ($nextImgLink==""){echo 'pointer-events:none;';} ?>" target="_BLANK"><img style="max-height:80px; max-width:928px;" src="<?php echo "image_ads/".$nextad.".png"; ?>" id="imgtest"></a>
						</div>
					</div>		
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <input id="userid" style="display:none;" value="<?php echo($userid); ?>">
								<div class="row">
									<div class="col-12">
										<a href="newproject.php?id=0" class="btn btn-primary btn-icon-split float-right"><span class="icon text-white-50"><i class="fas fa-rocket"></i></span><span class="text">New Project</span></a>
										<span class="float-right form-inline">
											Category&nbsp;&nbsp;<select class="form-control" style="width:auto; margin-right:10px;" id="catSelect" >
											<option>All Categories</option>
											<option>No Category</option>
											<?php echo $catSelect; ?>
											</select>
										</span>
										<span class="float-left form-inline" style="<?php echo $showcredits; ?>">Credits Left:&nbsp;&nbsp;<div class="form-control fake-input" id="wordsleft" readonly ><?php echo $usercredits; ?> <i onclick="refreshCredits();" class="fas fa-sync-alt" style="margin-left:10px; margin-right:10px; cursor:pointer;"></i> </div></span>
										<span class="float-left form-inline"  style="<?php echo $showcredits; ?>">
											&nbsp;&nbsp;Buy Packs&nbsp;&nbsp;<select class="form-control" style="width:auto; margin-right:10px;" id="packSelect" >
												<?php echo $packoutput; ?>
											</select>
										</span>
										<span class="float-left form-inline"  style="<?php echo $showcredits; ?>">
											<button id="buyPackButton" class="btn btn-success btn-icon-split float-right"><span class="icon text-white-50"><i class="fas fa-rocket"></i></span><span class="text">Buy Pack</span></button>
										</span>
									</div>
								</div>
								<div class="row topgap" style="<?php if ($usercredits>1000 || $add2==0){echo "display:none;";}?>">
									<div class="col-12">
										<div class="alert alert-<?php if ($usercredits<200){echo "danger";} else {echo "info";}?>"><?php if ($usercredits<200){echo "Not enough credits remaining to create a new project - Please purchase more.";}else{echo "You are running low on credits - Please purchase more.";} ?></div>
									</div>
								</div>
							
                        </div>
                        <div class="card-body">
						<input id="delid" value="" style="display:none;"></input>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
											<th>Category</th>
                                            <th>Created</th>
                                            <th>Last Edit</th>
                                            <th style="max-width:70px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Created</th>
                                            <th>Last Edit</th>
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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="delLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delLabel">Delete Project?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you SURE you want to delete this project?<br><i>(This is permanent and cannot be undone!)</i></div>
                <div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-icon-split" data-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-window-close"></i></span><span class="text">Cancel</span></button>
					<button type="button" class="btn btn-danger btn-icon-split" onclick="reallyDelete();" data-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-trash-alt"></i></span><span class="text">DELETE</span></button>
                </div>
            </div>
        </div>
    </div>



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

		<!-- Page level plugins -->
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js" integrity="sha512-OQlawZneA7zzfI6B1n1tjUuo3C5mtYuAWpQdg+iI9mkDoo7iFzTqnQHf+K5ThOWNJ9AbXL4+ZDwH7ykySPQc+A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
	$('#dataTable').DataTable({
	  "columnDefs": [
		{ "orderable": false, "targets": [ -1]} 
	  ]
	});
    $('#buyPackButton').click(function() {
		var packurl=$('#packSelect').val();
        window.open(packurl, '_blank');
    });	
});

function generalToast(header,body){
			$('#generalToastHeader').text(header);
			$('#generalToastBody').text(body);
			$('#generalToast').toast('show');
}

$('#catSelect').on('change', function() {
	var catname = this.value;
	var table = $('#dataTable').DataTable();
	if (catname == 'All Categories') {
		table.column(1).search('').draw();
	} else {
		table.column(1).search(catname).draw();
	}
});
function editproject(id){
	window.location.assign("newproject.php?id="+id);
}

function delproject(id){
	$('#delid').val(id);
	$('#deleteModal').modal('show');
}

function reallyDelete(){
	var id=$('#delid').val();
	$('#delid').val('');
	$.ajax({
		url: "AJAX_delproject.php",
		data: {id:parseInt(id)},
		type: 'POST',
		success: function (response) {
			if (response!="ERROR"){
				var id=response;
				var userTable = $('#dataTable').DataTable();
				var rowId = userTable.row('[id=rowid'+id+']').index();
				var row=userTable.row(rowId).remove().draw();
				generalToast('Project Deleted','This project has been deleted');
			} else {
				generalToast('ERROR!','Project NOT deleted');
			}
		}
	});	
}


function refreshCredits(){
	var userid=$('#userid').val();
	$.ajax({
		url: "AJAX_getcredit.php",
		data: {id:parseInt(userid)},
		type: 'POST',
		success: function (response) {
			if (response!="ERROR"){
				$('#wordsleft').html(response+' <i onclick="refreshCredits();" class="fas fa-sync-alt" style="margin-left:10px; margin-right:10px; cursor:pointer;"></i> ');
			} else {
				generalToast('ERROR!','Wordcount Error');
			}
		}
	});	
}
</script>

</body>

</html>