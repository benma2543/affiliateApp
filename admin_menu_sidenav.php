<?php
if (file_exists("add2.php")){require_once('add2.php');}else{$add2=0;}
if (file_exists("add3.php")){require_once('add3.php');}else{$add3=0;}
if (file_exists("add4.php")){require_once('add4.php');}else{$add4=0;}
if (file_exists("add5.php")){require_once('add5.php');}else{$add5=0;}

if ($add2==0){$showcredits="display:none;";}else{$showcredits="";}
if ($add3==0){$showar="display:none;";}else{$showar="";}
if ($add4==0){$showads="display:none;";}else{$showads="";}
if ($add5==0){$showpage="display:none;";}else{$showpage="";}

?>
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="d-flex align-items-center justify-content-center" style="text-align:center; margin-top:10px;" href="#">
                <div class="sidebar-brand-icon" >
					<img src="img/logo.png?<?php echo time(); ?>" style="max-width:90%; height:auto;">
                </div>
				
                
            </a>


            <hr class="sidebar-divider my-0">


            <li class="nav-item">
                <a class="nav-link" href="adminusers.php">
                    <i class="fas fa-users"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item" style="<?php echo $showcredits; ?>">
                <a class="nav-link" href="adminsales.php">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Credit Sales</span></a>
            </li>			
			<li class="nav-item">
                <a class="nav-link" href="adminsettings.php">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span></a>
            </li>
			<li class="nav-item" style="<?php echo $showcredits; ?>">
                <a class="nav-link" href="adminpacksettings.php">
                    <i class="fas fa-comment-dollar"></i>
                    <span>Pack Settings</span></a>
            </li>

			<li class="nav-item" style="<?php echo $showads; ?>">
                <a class="nav-link" href="adminadsettings.php">
                    <i class="fas fa-ad"></i>
                    <span>Ad Settings</span></a>
            </li>
            
			<li class="nav-item" style="<?php echo $showar; ?>">
                <a class="nav-link" href="adminarintegrations.php">
                    <i class="fas fa-cogs"></i>
                    <span>AR Integrations</span></a>
            </li>
			<li class="nav-item" style="<?php echo $showar; ?>">
                <a class="nav-link" href="adminleadcapture.php">
                    <i class="fas fa-envelope"></i>
                    <span>Lead Capture</span></a>            
			</li>
			<li class="nav-item" style="<?php echo $showpage; ?>">
                <a class="nav-link" href="adminsalespage.php">
                    <i class="fab fa-html5"></i>
                    <span>Salespage</span></a>            
			</li>

			
			<li class="nav-item" style="cursor:pointer;">
                <a class="nav-link" id="adminLogout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout Admin</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        