<!-- Sidebar -->
<?php
$adnumber=0;
for ($i = 1; $i <= 5; $i++) {
    $varName = "menutext" . $i;
    $$varName = "";
	$varName = "menulink" . $i;
    $$varName = "";
}
if (file_exists("add4.php")){require("add4.php");}else{$add4=0;}
if ($add4==1){
	require("configmenuads.php");
}
require("menucolor.php");
$menutextcolor="100";
$iconstyle="";
$togglecolor="";
$menufontstyle="";
switch ($menucolorselect){
	case 0:
		$iconstyle="color:rgba(255,255,255,.5)";
		break;
	case 1:
		$iconstyle="color:rgba(255,255,255,.5)";
		break;
	case 2:
		$iconstyle="color:rgba(255,255,255,.6)";
		break;
	case 3:
		$iconstyle="color:rgba(255,255,255,.6)";
		break;	
	case 4:
		$iconstyle="color:rgba(255,255,255,.6)";
		$menufontstyle="font-weight:700;";
		break;
	case 5:
		$iconstyle="color:rgba(255,255,255,.7)";
		break;
	case 7:
		$menutextcolor="900";
		$iconstyle="color:rgba(0,0,0,.5)";
		$togglecolor="background-color:#b9b9b9;";
		break;
}	


 
 ?>
        <ul class="navbar-nav <?php echo $menucolor; ?> sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="d-flex align-items-center justify-content-center" style="text-align:center; margin-top:10px;" href="#">
                <div class="sidebar-brand-icon" >
					<img src="img/logo.png?<?php echo time(); ?>" style="max-width:90%; height:auto;">
					 
                </div>
				
                
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="projects.php">
					<i class="fas fa-clipboard-list" style="<?php echo $iconstyle; ?>"></i>
                    <span class="text-gray-<?php echo $menutextcolor; ?>" style="<?php echo $menufontstyle; ?>">Manage Projects</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="newproject.php?id=0">
                    <i class="fas fa-rocket" style="<?php echo $iconstyle; ?>"></i>
                    <span class="text-gray-<?php echo $menutextcolor; ?>" style="<?php echo $menufontstyle; ?>">New Project</span></a>
            </li>				
			<li class="nav-item">
                <a class="nav-link" href="categories.php">
                    <i class="fas fa-folder-plus" style="<?php echo $iconstyle; ?>"></i>
                    <span class="text-gray-<?php echo $menutextcolor; ?>" style="<?php echo $menufontstyle; ?>">Manage Categories</span></a>
            </li>			
			<li class="nav-item">
                <a class="nav-link" href="usersettings.php">
                    <i class="fas fa-cog" style="<?php echo $iconstyle; ?>"></i>
                    <span class="text-gray-<?php echo $menutextcolor; ?>" style="<?php echo $menufontstyle; ?>">Settings</span></a>
            </li>
			
<?php

for ($i = 1; $i <= 5; $i++) {
    $menutext = "menutext" . $i;
 	$menulink = "menulink" . $i;
    if ($$menutext!=""){
		$menuout='<li class="nav-item"><a class="nav-link" target="_BLANK" href="'.$$menulink.'"><i class="fas fa-rocket" style="'.$iconstyle.'"></i><span class="text-gray-'.$menutextcolor.'" style="'.$menufontstyle.'"> '.$$menutext.'</span></a></li>';
		echo $menuout;
	}
}

?>			
			<li class="nav-item" style="cursor:pointer;">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt" style="<?php echo $iconstyle; ?>"></i>
                    <span class="text-gray-<?php echo $menutextcolor; ?>" style="<?php echo $menufontstyle; ?>">Logout</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

        
        
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" style="<?php echo $togglecolor; ?>" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->