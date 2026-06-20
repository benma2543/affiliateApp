<?php
require_once("config.php");
session_start();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    if ($_POST["username"]===$adminuser && $_POST["password"]===$adminpassword){
        $_SESSION["adminuser".$l1] = [];
    }
}

if (isset($_SESSION["adminuser".$l1])) {
    header("location:admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
</head>
<body class="d-flex flex-column antialiased">
<div class="page page-center">
    <div class="container-tight py-4">
        <div class="card card-md shadow-lg">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="img/logo.png" alt="Logo" style="max-height:80px; max-width:200px; width:auto; height:auto;">
                </div>
                <h2 class="h2 text-center mb-4">Admin Login</h2>
                <?php if (isset($_POST["username"])) { echo '<div class="alert alert-danger"><b>Invalid username or password.</b></div>'; } ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Admin username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            <?php echo $footer; ?>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</body>
</html>
