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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title><?php echo $sitename; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
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
                        <h2 class="page-title">Settings</h2>
                        <div class="text-muted">View and change your account settings.</div>
                    </div>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-primary" id="saveSettings">
                            <i class="fas fa-save me-1"></i> Save Settings
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="alert alert-danger mb-4">
                            <b>WARNING:</b> If you change your email address you will automatically be logged out.
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Your Name</label>
                                <input type="text" class="form-control" disabled name="username" id="username" value="<?php echo $_SESSION["user".$l1]["user_name"]; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Your Email</label>
                                <input type="text" class="form-control" name="useremail" id="useremail" value="<?php echo $_SESSION["user".$l1]["user_email"]; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Account Created</label>
                                <input type="text" class="form-control" disabled value="<?php echo $_SESSION["user".$l1]["user_creation"]; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">IP Address</label>
                                <input type="text" class="form-control" disabled value="<?php
                                    $ip = "None";
                                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {$ip = $_SERVER['HTTP_CLIENT_IP'];}
                                    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];}
                                    else {$ip = $_SERVER['REMOTE_ADDR'];}
                                    echo $ip;
                                ?>">
                            </div>
                            <?php if ($masterkeymode != true): ?>
                            <div class="col-md-3">
                                <label class="form-label">OpenAI API Key</label>
                                <input type="text" class="form-control" name="userapi" id="userapi" value="<?php echo $result["user_apikey"]; ?>">
                            </div>
                            <?php endif; ?>
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

<script>
$(document).ready(function() {
    let params = new URLSearchParams(window.location.search);
    if (params.get('s') === '1') {
        $('#generalToastHeader').text('SAVED');
        $('#generalToastBody').text('Your settings have been saved!');
        var toast = new bootstrap.Toast(document.getElementById('generalToast'), {delay: 5000});
        toast.show();
    }

    $('input').on('input', function() {
        var inputValue = $(this).val();
        var sanitizedValue = inputValue.replace(/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_@\-=\,\.]/g, '');
        $(this).val(sanitizedValue);
    });
});

$('#saveSettings').click(function() {
    let data = {
        email: $('#useremail').val(),
        api: $('#userapi').val(),
    };
    $.ajax({
        url: "AJAX_user_updatesettings.php",
        dataType: 'text',
        type: 'POST',
        data: data,
        success: function(response) {
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
