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


$st = $db->prepare("SELECT cat_id,cat_name,cat_created FROM categories WHERE cat_userid=:userid");
$st->bindParam(':userid', $userid);
$st->execute();
$user=[];
$results = $st->fetchAll();
$tableout="";
if ($results){
	$showtable="";
	foreach ($results as $key => $value) {
		$id=$value["cat_id"];
		
    
	$action=<<<EOT
<div class="dropdown">
  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton$id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-bars"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton$id">
    <span class="dropdown-item" style="cursor:pointer;" onclick="editCat('$id');"><i class="fas fa-edit"></i> Edit</span>
    <span class="dropdown-item" style="cursor:pointer;" onclick="delCat('$id');"><i class="fas fa-trash-alt"></i> Delete</span>
  </div>
</div>	
EOT;
	
		$tableout.="<tr id='rowid".$id."'><td onclick=\"editCat('".$id."','".$value["cat_name"]."');\" style='cursor:pointer;' >".$value["cat_name"]."</td><td>".$value["cat_created"]."</td><td >".$action."</td></tr>";
	}
} else {
	$showtable="display:none;";
	
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

       <?php require_once("menu_sidenav.php"); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid" style="margin-top:50px;"	>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Categories</h1>
                    <p class="mb-4">You can manage your Categories here.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
								<div class="input-group mb-3 float-right" style="max-width:50%;">
									<input id="newCatName" maxlength="40" type="text" class="form-control" placeholder="Enter New Category">
									<div class="input-group-append">
										<button id="newCatButton" type="button" class="btn btn-primary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-folder-plus"></i></span><span class="text">Add Category</span></button>
									</div>
								</div>
	                        </div>
                        <div class="card-body">
						<input id="delid" value="" style="display:none;"></input>
                            <div class="table-responsive" id="tablediv" style="<?php echo $showtable; ?>">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Created</th>
                                            <th style="max-width:70px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Created</th>
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
                    <h5 class="modal-title" id="delLabel">Delete Category?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you SURE you want to delete this Category?<br><i>(This is permanent and cannot be undone!)</i></div>
                <div class="modal-footer">
                    
					<button type="button" class="btn btn-secondary btn-icon-split" data-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-window-close"></i></span><span class="text">Cancel</span></button>
					<button type="button" class="btn btn-danger btn-icon-split" onclick="reallyDelete();" data-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-trash-alt"></i></span><span class="text">DELETE</span></button>					
                </div>
            </div>
        </div>
    </div>

    <!-- edit Modal -->
    <div class="modal fade" id="editCatModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delLabel">Edit Category?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
				<input id="catIdStore" value="" style="display:none;">
				
                <div class="modal-body">
					<div class="row" style="">
						<div class="col-12">
							<label>Category Name (edit and click Save)</label>
							<input type="text" class="form-control validate" placeholder="Category Name" maxlength="50" name="catEditName" id="catEditName" value="">
						</div>
					</div>
				</div>
                <div class="modal-footer">
                    
					<button type="button" class="btn btn-secondary btn-icon-split" data-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-window-close"></i></span><span class="text">Cancel</span></button>
					<button type="button" class="btn btn-primary btn-icon-split" onclick="saveEditCat();" data-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-save"></i></span><span class="text">Save</span></button>					
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
function editCat(id,catName){
	$('#catIdStore').val(id);
	$('#catEditName').val(catName);
	$('#editCatModal').modal('show');
}

function saveEditCat(){
	var id=$('#catIdStore').val();
	var catName=$('#catEditName').val();
	$.ajax({
		url: "AJAX_editcat.php",
		data: {catId:id,catName:catName},
		type: 'POST',
		success: function (response) {
			if (response!="ERROR"){
				var cell = $('#rowid' + id).children('td:first');
				cell.text(catName);
				cell.attr('onclick', 'editCat(\''+id+'\',\''+catName+'\');')			
				generalToast('Category Changed','This Category has been changed');
			} else {
				generalToast('ERROR!','This Category has NOT been changed!');
			}
		}
	});		
}

$(document).ready(function() {
	$('#dataTable').DataTable({
	  "columnDefs": [
		{ "orderable": false, "targets": 2 }
	  ]
	});
  $('#newCatName,#catEditName').on('input', function() {
    var inputValue = $(this).val();
    var sanitizedValue = inputValue.replace(/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_\-=]/g, '');
    $(this).val(sanitizedValue);
  });

});
function generalToast(header,body){
			$('#generalToastHeader').text(header);
			$('#generalToastBody').text(body);
			$('#generalToast').toast('show');
}

$('#newCatButton').click(function(){
	var newCatName=$('#newCatName').val();
	$('#newCatName').val("");
	$.ajax({
		url: "AJAX_addnewcat.php",
		data: {newcatname:newCatName},
		type: 'POST',
		success: function (response) {
			if (response!="ERROR"){
				$('#tablediv').show();
				var data = JSON.parse(response);
				var catId = data.cat_id;
				var catName = data.cat_name;
				var catCreated = data.cat_created;

				var catTable = $('#dataTable').DataTable();
				var actionbutton='<div class="dropdown"><button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton'+catId+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bars"></i></button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton'+catId+'"><span class="dropdown-item" style="cursor:pointer;" onclick="editCat(\''+catId+'\');"><i class="fas fa-edit"></i> Edit</span><span class="dropdown-item" style="cursor:pointer;" onclick="delCat(\''+catId+'\');"><i class="fas fa-trash-alt"></i> Delete</span></div></div>';
				
				var rowNode = catTable
					.row.add( [ catName, catCreated, actionbutton ] )
					.draw()
					.node();
				$(rowNode).attr('id','rowid'+catId);
				$('td', rowNode).eq(0).attr('onclick', 'editCat(\''+catId+'\',\''+catName+'\');').css('cursor', 'pointer');
				
				generalToast('Category Added','This Category has been added');
			} else {
				generalToast('ERROR!','This Category has NOT been added');
			}
		}
	});	
});



function delCat(id){
	$('#delid').val(id);
	$('#deleteModal').modal('show');
}

function reallyDelete(){
	var id=$('#delid').val();
	$('#delid').val('');
	$.ajax({
		url: "AJAX_delcat.php",
		data: {id:parseInt(id)},
		type: 'POST',
		success: function (response) {
			if (response!="ERROR"){
				var userTable = $('#dataTable').DataTable();
				var rowId = userTable.row('[id=rowid'+id+']').index();
				var row=userTable.row(rowId).remove().draw();
				generalToast('Category Deleted','This Category has been deleted');
			} else {
				generalToast('ERROR!','This Category has NOT been deleted');
			}
		}
	});	
}


</script>

</body>

</html>