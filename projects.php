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
        $st2 = $db->prepare("SELECT cat_name FROM categories WHERE cat_userid=:userid AND cat_id=:catid");
        $st2->bindParam(':userid', $userid);
        $st2->bindParam(':catid', $catid);
        $st2->execute();
        $catresults = $st2->fetch();
        if ($catresults){
            $category=$catresults["cat_name"];
        }
    }

    $id=$value["id"];
    $action=<<<EOT
<div class="dropdown">
  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton$id" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-bars"></i>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton$id">
    <li><span class="dropdown-item" style="cursor:pointer;" onclick="editproject($id);"><i class="fas fa-edit"></i> Edit</span></li>
    <li><span class="dropdown-item" style="cursor:pointer;" onclick="delproject($id);"><i class="fas fa-trash-alt"></i> Delete</span></li>
  </ul>
</div>
EOT;

    $tableout.="<tr id='rowid".$id."'><td style='cursor:pointer;' onclick='editproject(".$id.")'>".$value["project_name"]."</td><td>".$category."</td><td>".$value["date_created"]."</td><td>".$value["last_edited"]."</td><td>".$action."</td></tr>";
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
        $usercredits = 0;
    }
} catch(PDOException $e) {
    $usercredits = 0;
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title><?php echo $sitename; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/tabler-compat.css">
    <style>
        .no-select { user-select: none; }
        .fake-input {
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-right: 10px;
        }
    </style>
</head>
<body class="antialiased">
<div class="wrapper">
    <?php require_once("menu_sidenav.php"); ?>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl py-4">

                <!-- Ad banner -->
                <?php if (isset($nextad) && $nextad != 0): ?>
                <div class="text-center mb-3">
                    <a id="imgtestlink" href="<?php $nextImgLink = ${'imglink' . $nextad}; echo $nextImgLink; ?>"
                       style="<?php if (empty($nextImgLink)){echo 'pointer-events:none;';} ?>" target="_blank">
                        <img style="max-height:80px; max-width:928px;" src="image_ads/<?php echo $nextad; ?>.png" id="imgtest">
                    </a>
                </div>
                <?php endif; ?>

                <div class="d-flex align-items-center mb-3">
                    <div>
                        <h2 class="page-title">Projects</h2>
                        <div class="text-muted">Manage your projects here.</div>
                    </div>
                    <div class="ms-auto">
                        <a href="newproject.php?id=0" class="btn btn-primary">
                            <i class="fas fa-rocket me-1"></i> New Project
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex align-items-center flex-wrap gap-2 w-100">
                            <input id="userid" style="display:none;" value="<?php echo($userid); ?>">

                            <div style="<?php echo $showcredits; ?>" class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="text-muted">Credits:</span>
                                <div class="form-control fake-input" id="wordsleft" style="width:auto; min-width:120px;" readonly>
                                    <?php echo $usercredits; ?>
                                    <i onclick="refreshCredits();" class="fas fa-sync-alt ms-2" style="cursor:pointer;"></i>
                                </div>
                                <select class="form-select" style="width:auto;" id="packSelect">
                                    <?php echo $packoutput; ?>
                                </select>
                                <button id="buyPackButton" class="btn btn-success">
                                    <i class="fas fa-rocket me-1"></i> Buy Pack
                                </button>
                            </div>

                            <div class="ms-auto d-flex align-items-center gap-2">
                                <span class="text-muted">Category:</span>
                                <select class="form-select" style="width:auto;" id="catSelect">
                                    <option>All Categories</option>
                                    <option>No Category</option>
                                    <?php echo $catSelect; ?>
                                </select>
                            </div>
                        </div>

                        <?php if ($add2==1 && $usercredits <= 1000): ?>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="alert alert-<?php echo ($usercredits<200) ? 'danger' : 'warning'; ?> mb-0">
                                    <?php echo ($usercredits<200) ? 'Not enough credits remaining to create a new project - Please purchase more.' : 'You are running low on credits - Please purchase more.'; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <input id="delid" value="" style="display:none;">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-bordered" id="dataTable" width="100%" cellspacing="0">
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
        </div>

        <footer class="footer sticky-footer bg-white">
            <div class="container text-center py-2">
                <?php echo $footer; ?>
            </div>
        </footer>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="delLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delLabel">Delete Project?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you SURE you want to delete this project?<br><i>(This is permanent and cannot be undone!)</i></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-window-close me-1"></i> Cancel</button>
                <button type="button" class="btn btn-danger" onclick="reallyDelete();" data-bs-dismiss="modal"><i class="fas fa-trash-alt me-1"></i> DELETE</button>
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
$(document).ready(function() {
    $('#dataTable').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [-1] }
        ]
    });
    $('#buyPackButton').click(function() {
        var packurl = $('#packSelect').val();
        window.open(packurl, '_blank');
    });
});

function generalToast(header, body) {
    $('#generalToastHeader').text(header);
    $('#generalToastBody').text(body);
    var toastEl = document.getElementById('generalToast');
    var toast = new bootstrap.Toast(toastEl, {delay: 5000});
    toast.show();
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

function editproject(id) {
    window.location.assign("newproject.php?id=" + id);
}

function delproject(id) {
    $('#delid').val(id);
    var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function reallyDelete() {
    var id = $('#delid').val();
    $('#delid').val('');
    $.ajax({
        url: "AJAX_delproject.php",
        data: {id: parseInt(id)},
        type: 'POST',
        success: function(response) {
            if (response != "ERROR") {
                var id = response;
                var userTable = $('#dataTable').DataTable();
                var rowId = userTable.row('[id=rowid' + id + ']').index();
                userTable.row(rowId).remove().draw();
                generalToast('Project Deleted', 'This project has been deleted');
            } else {
                generalToast('ERROR!', 'Project NOT deleted');
            }
        }
    });
}

function refreshCredits() {
    var userid = $('#userid').val();
    $.ajax({
        url: "AJAX_getcredit.php",
        data: {id: parseInt(userid)},
        type: 'POST',
        success: function(response) {
            if (response != "ERROR") {
                $('#wordsleft').html(response + ' <i onclick="refreshCredits();" class="fas fa-sync-alt ms-2" style="cursor:pointer;"></i> ');
            } else {
                generalToast('ERROR!', 'Wordcount Error');
            }
        }
    });
}
</script>
</body>
</html>
