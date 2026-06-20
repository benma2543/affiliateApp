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
$results = $st->fetchAll();
$tableout="";
$multicredits="0";
if (isset($_GET["m"])){$multicredits=$_GET["m"];}

foreach ($results as $key => $value) {
    $id=$results[$key]['user_id'];
    $credits=$results[$key]['user_credits'];
    if ($results[$key]['user_status']=="1") {
        $status="<span class='badge bg-success'>OK</span>";
        $actionmenu='<li><span class="dropdown-item" style="cursor:pointer;" onclick="ban('.$id.',0,'.$credits.');"><i class="fas fa-user-times me-1"></i> Ban</span></li>';
    } else {
        $status="<span class='badge bg-danger'>Banned</span>";
        $actionmenu='<li><span class="dropdown-item" style="cursor:pointer;" onclick="ban('.$id.',1,'.$credits.');"><i class="fas fa-user-check me-1"></i> Unban</span></li>';
    }

    $action=<<<EOT
<div class="dropdown">
  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton$id" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-bars"></i>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton$id">
    $actionmenu
    <li><span class="dropdown-item" style="cursor:pointer;" onclick="del($id);"><i class="fas fa-user-slash me-1"></i> Delete</span></li>
    <li><span class="dropdown-item" style="cursor:pointer;" onclick="newpass($id);"><i class="fas fa-key me-1"></i> New Password</span></li>
    <li><span class="dropdown-item" style="cursor:pointer; $showcredits" onclick="credits($id,$credits);"><i class="fas fa-comment-dollar me-1"></i> Credits</span></li>
  </ul>
</div>
EOT;

    $tableout.="<tr id='rowid".$results[$key]['user_id']."'><td>".$value["user_id"]."</td><td>".$value["user_name"]."</td><td>".$value["user_email"]."</td><td>".$value["user_creation"]."</td><td>".$value["user_logins"]."</td><td>".$value["project_count"]."</td><td style='".$showcredits."'>".$value["user_credits"]."</td><td>".$status."</td><td>".$action."</td></tr>";
}

$url="https://eslcore.com/l/ver.php?ver=".base64_encode($version)."&k=".$l1;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$ret = curl_exec($ch);
curl_close($ch);
$ret=base64_decode($ret);
$versionmessage="";
if ($ret!="OK"){
    $versionmessage='<div class="alert alert-warning mb-3">'.$ret.'</div>';
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
    <?php require_once("admin_menu_sidenav.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column flex-grow-1">
        <div id="content" class="flex-grow-1">
            <div class="container-xl py-4">

                <div class="d-flex align-items-center mb-3">
                    <div>
                        <h2 class="page-title">Users</h2>
                        <div class="text-muted">Manage your users here.</div>
                    </div>
                    <div class="ms-auto d-flex gap-2 align-items-center" style="<?php echo $showcredits; ?>">
                        <input id="multiCredits" maxlength="40" type="number" class="form-control numonly" style="width:200px;" placeholder="Credits to add to all users">
                        <button onclick="multiCredits();" type="button" class="btn btn-primary">
                            <i class="fas fa-comment-dollar me-1"></i> Add Credits
                        </button>
                    </div>
                </div>

                <input id="multicredits_storage" value="<?php echo($multicredits); ?>" style="display:none;">

                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php echo $versionmessage; ?>
                        <div class="table-responsive">
                            <table class="table table-vcenter table-bordered" id="dataTable" width="100%" cellspacing="0">
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
        </div>
        <footer class="footer sticky-footer bg-white">
            <div class="container text-center py-2"><?php echo $footer; ?></div>
        </footer>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Click "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="adminlogout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="delModal" tabindex="-1" aria-labelledby="delLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delLabel">WARNING: DELETE USER!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Click "DELETE" below if you really want to delete this user.<br><br><b>IMPORTANT:</b> This is NOT reversible. Consider banning the user instead.</div>
            <input style="display:none;" id="deluserid" value="">
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" onclick="doDeluser();" data-bs-dismiss="modal">DELETE</button>
            </div>
        </div>
    </div>
</div>

<!-- New Password Modal -->
<div class="modal fade" id="newpassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input id="newpassId" value="" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input class="form-control" id="newpassPassword">
                </div>
                <p class="text-muted"><i>Enter new password above and click Set or Cancel.</i></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"><i class="fas fa-times-circle me-1"></i> Cancel</button>
                <button class="btn btn-primary" onclick="donewpass()" data-bs-dismiss="modal"><i class="fas fa-rocket me-1"></i> Set</button>
            </div>
        </div>
    </div>
</div>

<!-- Credits Modal -->
<div class="modal fade" id="creditsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Credits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input id="modalId" value="" style="display:none;">
                <p>Managing Credits for: <strong id="modalName"></strong> (<span id="modalEmail"></span>)</p>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label class="form-label">Credits</label>
                    </div>
                    <div class="col-auto">
                        <input class="form-control numonly" type="number" style="width:200px;" id="modalCredits">
                    </div>
                </div>
                <p class="text-muted mt-2"><i>Change the credits above and click Modify or Cancel.</i></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"><i class="fas fa-times-circle me-1"></i> Cancel</button>
                <button class="btn btn-primary" onclick="modCredits()"><i class="fas fa-rocket me-1"></i> Modify</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1090;">
    <div id="banToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="banToastHeader"></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="banToastBody"></div>
    </div>
</div>

<a class="scroll-to-top" href="#"><i class="fas fa-angle-up"></i></a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
function showToast(header, body) {
    $('#banToastHeader').text(header);
    $('#banToastBody').text(body);
    var toast = new bootstrap.Toast(document.getElementById('banToast'), {delay: 5000});
    toast.show();
}

$(document).ready(function() {
    $('#dataTable').DataTable({
        "columnDefs": [{"orderable": false, "targets": 8}]
    });

    var inputs = document.querySelectorAll('.numonly');
    inputs.forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                (e.keyCode === 65 && (e.ctrlKey || e.metaKey)) ||
                (e.keyCode === 67 && (e.ctrlKey || e.metaKey)) ||
                (e.keyCode === 86 && (e.ctrlKey || e.metaKey)) ||
                (e.keyCode === 88 && (e.ctrlKey || e.metaKey)) ||
                (e.keyCode >= 35 && e.keyCode <= 39)) { return; }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });

    var multicredits_storage = $('#multicredits_storage').val();
    if (multicredits_storage > 0) {
        showToast('Credits Added', multicredits_storage + ' credits have been added to ALL user accounts!');
    }
});

$('#adminLogout').click(function() {
    var modal = new bootstrap.Modal(document.getElementById('logoutModal'));
    modal.show();
});

function multiCredits() {
    var credits = $('#multiCredits').val();
    if (credits > 0) {
        $.ajax({
            url: "AJAX_admin_multicredits.php",
            data: {credits: parseInt(credits)},
            type: 'POST',
            success: function(response) {
                window.location.assign('adminusers.php?m=' + response);
            }
        });
    }
}

function credits(id, credits) {
    var userTable = $('#dataTable').DataTable();
    var rowData = userTable.row('#rowid' + id).data();
    var name = rowData[1];
    var email = rowData[2];
    $('#modalId').val(id);
    $('#modalName').text(name);
    $('#modalEmail').text(email);
    $('#modalCredits').val(credits);
    var modal = new bootstrap.Modal(document.getElementById('creditsModal'));
    modal.show();
}

function modCredits() {
    var id = $('#modalId').val();
    var credits = $('#modalCredits').val();
    var userTable = $('#dataTable').DataTable();
    var origCredits = userTable.cell('#rowid' + id, 6).data();
    if (origCredits != credits) {
        $.ajax({
            url: "AJAX_admin_modcredits.php",
            data: {id: parseInt(id), credits: parseInt(credits)},
            type: 'POST',
            success: function(response) {
                userTable.cell('#rowid' + id, 6).data(credits).draw();
                showToast('Credits Modified', 'Credit modification completed.');
            }
        });
    }
    bootstrap.Modal.getInstance(document.getElementById('creditsModal')).hide();
}

function del(id) {
    $('#deluserid').val(id);
    var modal = new bootstrap.Modal(document.getElementById('delModal'));
    modal.show();
}

function doDeluser() {
    var id = $('#deluserid').val();
    var userTable = $('#dataTable').DataTable();
    var rowId = userTable.row('[id=rowid' + id + ']').index();
    $.ajax({
        url: "AJAX_admin_del.php",
        data: {id: parseInt(id)},
        type: 'POST',
        success: function(response) {
            userTable.row(rowId).remove().draw();
            showToast('Delete User', 'This user has been deleted!');
        }
    });
}

function ban(id, banmode, credits) {
    var userTable = $('#dataTable').DataTable();
    var rowId = userTable.row('[id=rowid' + id + ']').index();
    if (banmode == 0) {
        userTable.cell({row: rowId, column: 7}).data("<span class='badge bg-danger'>Banned</span>").draw(false);
        var actionmenu = '<li><span class="dropdown-item" style="cursor:pointer;" onclick="ban(' + id + ',1);"><i class="fas fa-user-check me-1"></i> Unban</span></li>';
        userTable.cell({row: rowId, column: 8}).data('<div class="dropdown"><button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + id + '" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars"></i></button><ul class="dropdown-menu">' + actionmenu + '<li><span class="dropdown-item" style="cursor:pointer;" onclick="credits(' + id + ',' + credits + ');"><i class="fas fa-comment-dollar me-1"></i> Credits</span></li></ul></div>').draw(false);
        showToast('Ban User', 'This user has been banned!');
    } else {
        userTable.cell({row: rowId, column: 7}).data("<span class='badge bg-success'>OK</span>").draw(false);
        var actionmenu = '<li><span class="dropdown-item" style="cursor:pointer;" onclick="ban(' + id + ',0);"><i class="fas fa-user-times me-1"></i> Ban</span></li>';
        userTable.cell({row: rowId, column: 8}).data('<div class="dropdown"><button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + id + '" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars"></i></button><ul class="dropdown-menu">' + actionmenu + '<li><span class="dropdown-item" style="cursor:pointer;" onclick="credits(' + id + ',' + credits + ');"><i class="fas fa-comment-dollar me-1"></i> Credits</span></li></ul></div>').draw(false);
        showToast('Unban User', 'This user has been un-banned!');
    }
    $.ajax({
        url: "AJAX_admin_ban.php",
        data: {id: parseInt(id), banmode: parseInt(banmode)},
        type: 'POST'
    });
}

function newpass(id) {
    $('#newpassId').val(id);
    $('#newpassPassword').val('');
    var modal = new bootstrap.Modal(document.getElementById('newpassModal'));
    modal.show();
}

function donewpass() {
    var id = $('#newpassId').val();
    var newpass = $('#newpassPassword').val();
    if (newpass != "") {
        $.ajax({
            url: "AJAX_admin_newpass.php",
            data: {id: parseInt(id), newpass: newpass},
            type: 'POST',
            success: function(response) {
                showToast('Password Changed', 'Password changed for this user!');
            }
        });
    }
}
</script>
</body>
</html>
