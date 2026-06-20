<?php
session_start();
require_once("config.php");
if ($showreg==0){header("location:login.php");}
if (isset($_POST["email"]) && trim($_POST["password"])!="" && trim($_POST["name"]!="")) {
    require_once "user/user-lib.php";
    $isusr=$USR->get($_POST["email"]);
    if (!is_array($isusr)) {
        $USR->save(trim($_POST["name"]), trim($_POST["email"]),trim($_POST["password"]));
        header("Location: login.php?new=1");
    }
}

if (isset($_SESSION["user".$l1])) {
    header("Location: projects.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
</head>
<body class="d-flex flex-column antialiased">
<div class="page page-center">
    <div class="container-tight py-4">
        <div class="card card-md shadow-lg">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="img/logo.png?<?php echo time(); ?>" alt="Logo" style="max-height:80px; max-width:200px; width:auto; height:auto;">
                </div>
                <h2 class="h2 text-center mb-4">Create a new account</h2>
                <?php if (isset($_POST["email"])) { echo '<div class="alert alert-danger">Email already exists, invalid password, or name not entered. Please correct and try again.</div>'; } ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Choose a password" required>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Create Account</button>
                    </div>
                </form>
            </div>
            <div class="hr-text">or</div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <a href="login.php" class="btn w-100">I already have an account</a>
                    </div>
                </div>
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
