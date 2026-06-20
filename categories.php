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
$results = $st->fetchAll();
$tableout="";
if ($results){
    $showtable="";
    foreach ($results as $key => $value) {
        $id=$value["cat_id"];
        $action=<<<EOT
<div class="dropdown">
  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton$id" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-bars"></i>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton$id">
    <li><span class="dropdown-item" style="cursor:pointer;" onclick="editCat('$id');"><i class="fas fa-edit"></i> Edit</span></li>
    <li><span class="dropdown-item" style="cursor:pointer;" onclick="delCat('$id');"><i class="fas fa-trash-alt"></i> Delete</span></li>
  </ul>
</div>
EOT;
        $tableout.="<tr id='rowid".$id."'><td onclick=\"editCat('".$id."','".$value["cat_name"]."');\" style='cursor:pointer;' >".$value["cat_name"]."</td><td>".$value["cat_created"]."</td><td>".$action."</td></tr>";
    }
} else {
    $showtable="display:none;";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title><?php echo $sitename; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/tabler-compat.css">
</head>
<body class="antialiased">
<div id="wrapper" class="d-flex">
    <?php require_once("menu_sidenav.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column flex-grow-1">
        <div id="content" class="flex-grow-1">
            <div class="container-xl py-4">

                <div class="d-flex align-items-center mb-3">
                    <div>
                        <h2 class="page-title">Categories</h2>
                        <div class="text-muted">Manage your categories here.</div>
                    </div>
                    <div class="ms-auto d-flex gap-2">
                        <input id="newCatName" maxlength="40" type="text" class="form-control" placeholder="New category name" style="width:220px;">
                        <button id="newCatButton" type="button" class="btn btn-primary">
                            <i class="fas fa-folder-plus me-1"></i> Add Category
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <input id="delid" value="" style="display:none;">
                        <div class="table-responsive" id="tablediv" style="<?php echo $showtable; ?>">
                            <table class="table table-vcenter table-bordered" id="dataTable" width="100%" cellspacing="0">
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
        </div>
        <footer class="footer sticky-footer bg-white">
            <div class="container text-center py-2"><?php echo $footer; ?></div>
        </footer>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="delLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delLabel">Delete Category?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you SURE you want to delete this category?<br><i>(This is permanent and cannot be undone!)</i></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-window-close me-1"></i> Cancel</button>
                <button type="button" class="btn btn-danger" onclick="reallyDelete();" data-bs-dismiss="modal"><i class="fas fa-trash-alt me-1"></i> DELETE</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input id="catIdStore" value="" style="display:none;">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" class="form-control" placeholder="Category Name" maxlength="50" id="catEditName" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-window-close me-1"></i> Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEditCat();" data-bs-dismiss="modal"><i class="fas fa-save me-1"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1090;">
    <div id="generalToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="generalToastHeader"></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="generalToastBody"></div>
    </div>
</div>

<a class="scroll-to-top" href="#"><i class="fas fa-angle-up"></i></a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
function generalToast(header, body) {
    $('#generalToastHeader').text(header);
    $('#generalToastBody').text(body);
    var toastEl = document.getElementById('generalToast');
    var toast = new bootstrap.Toast(toastEl, {delay: 5000});
    toast.show();
}

function editCat(id, catName) {
    $('#catIdStore').val(id);
    $('#catEditName').val(catName);
    var modal = new bootstrap.Modal(document.getElementById('editCatModal'));
    modal.show();
}

function saveEditCat() {
    var id = $('#catIdStore').val();
    var catName = $('#catEditName').val();
    $.ajax({
        url: "AJAX_editcat.php",
        data: {catId: id, catName: catName},
        type: 'POST',
        success: function(response) {
            if (response != "ERROR") {
                var cell = $('#rowid' + id).children('td:first');
                cell.text(catName);
                cell.attr('onclick', 'editCat(\'' + id + '\',\'' + catName + '\');');
                generalToast('Category Changed', 'This category has been updated');
            } else {
                generalToast('ERROR!', 'This category could not be updated');
            }
        }
    });
}

$(document).ready(function() {
    $('#dataTable').DataTable({
        "columnDefs": [
            {"orderable": false, "targets": 2}
        ]
    });
    $('#newCatName,#catEditName').on('input', function() {
        var inputValue = $(this).val();
        var sanitizedValue = inputValue.replace(/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_\-=]/g, '');
        $(this).val(sanitizedValue);
    });
});

$('#newCatButton').click(function() {
    var newCatName = $('#newCatName').val();
    $('#newCatName').val("");
    $.ajax({
        url: "AJAX_addnewcat.php",
        data: {newcatname: newCatName},
        type: 'POST',
        success: function(response) {
            if (response != "ERROR") {
                $('#tablediv').show();
                var data = JSON.parse(response);
                var catId = data.cat_id;
                var catName = data.cat_name;
                var catCreated = data.cat_created;
                var catTable = $('#dataTable').DataTable();
                var actionbutton = '<div class="dropdown"><button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + catId + '" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars"></i></button><ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + catId + '"><li><span class="dropdown-item" style="cursor:pointer;" onclick="editCat(\'' + catId + '\');"><i class="fas fa-edit"></i> Edit</span></li><li><span class="dropdown-item" style="cursor:pointer;" onclick="delCat(\'' + catId + '\');"><i class="fas fa-trash-alt"></i> Delete</span></li></ul></div>';
                var rowNode = catTable.row.add([catName, catCreated, actionbutton]).draw().node();
                $(rowNode).attr('id', 'rowid' + catId);
                $('td', rowNode).eq(0).attr('onclick', 'editCat(\'' + catId + '\',\'' + catName + '\');').css('cursor', 'pointer');
                generalToast('Category Added', 'This category has been added');
            } else {
                generalToast('ERROR!', 'This category could not be added');
            }
        }
    });
});

function delCat(id) {
    $('#delid').val(id);
    var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function reallyDelete() {
    var id = $('#delid').val();
    $('#delid').val('');
    $.ajax({
        url: "AJAX_delcat.php",
        data: {id: parseInt(id)},
        type: 'POST',
        success: function(response) {
            if (response != "ERROR") {
                var userTable = $('#dataTable').DataTable();
                var rowId = userTable.row('[id=rowid' + id + ']').index();
                userTable.row(rowId).remove().draw();
                generalToast('Category Deleted', 'This category has been deleted');
            } else {
                generalToast('ERROR!', 'This category could not be deleted');
            }
        }
    });
}
</script>
</body>
</html>
