<?php
require_once("config.php");
?>
<!doctype HTML>
<html>
<head>
<title></title>
<style>
* {
  font-family: Arial, Helvetica, sans-serif;
  box-sizing: border-box;
  color: #6f6f6f;
  
}


#notify {
  background: #ffd9e3;
  padding: 10px;
  margin-bottom: 10px;
}


.upgrade_style {
  font-size:16px;
  max-width: 900px;
  border: 1px solid #ddd;
  background: #f2f2f2;
  margin: 0 auto;
  padding: 20px;
}
.upgrade_style h2 {
  color: #6f6f6f;
  padding: 0;
  margin: 0 0 10px 0;
}
.upgrade_style label, .upgrade_style input, .upgrade_style textarea, .upgrade_style select {
  width: 100%;
  margin: 10px 0;
}
.upgrade_style input, .upgrade_style textarea, .upgrade_style select {
  padding: 10px;
}
.upgrade_style input[type=submit] {
  background: #4f69db;
  color: #fff;
  border: 0;
}
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 100%;
}	
</style>
</head>
<body>
	
    <div  class="upgrade_style">
		<img class="center" style="max-width:100%; height:auto;" src="img/logo.png">
		<div style="text-align:center; margin-top:10px;"><h2 style="font-size:18px" >Thank you for your purchase of <?php echo($sitename); ?></h2></div>
		<br>
		<strong>IMPORTANT :</strong>
		<br><br>
		<i>Your login details have been emailed to you.</i>
		<br><br>
		<i><strong>NOTE: </strong>PLEASE make sure you check your SPAM folder.</i>
		<br><br>
		<i>If you have any problems then please get in touch.</i>
		
		<?php echo($footer); ?>
    </div>	
<script>
</script>
</body>
</html>