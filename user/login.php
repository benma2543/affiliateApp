<?php

session_start();
if (isset($_POST["email"])) {
  require "user-lib.php";
  $USR->login($_POST["email"], $_POST["password"]);
}


if (isset($_SESSION["user".$l1])) {
	header("Location: index.php");
	exit();
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
  </head>
  <body>
    
    <?php
    if (isset($_POST["email"])) { echo "<div id='notify'>Invalid user/password</div>"; }
    ?>

    
    <form id="login" method="post">
      <h2>MEMBER LOGIN</h2>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" value="Sign In">
    </form>
  </body>
</html>