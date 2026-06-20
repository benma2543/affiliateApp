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
<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#admin-navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand py-3 text-center">
            <img src="img/logo.png?<?php echo time(); ?>" class="navbar-brand-img" alt="Logo" style="max-width:140px; max-height:60px; width:auto; height:auto;">
        </div>
        <div class="collapse navbar-collapse" id="admin-navbar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="adminusers.php">
                        <span class="nav-link-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-link-title">Users</span>
                    </a>
                </li>
                <li class="nav-item" style="<?php echo $showcredits; ?>">
                    <a class="nav-link" href="adminsales.php">
                        <span class="nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        <span class="nav-link-title">Credit Sales</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adminsettings.php">
                        <span class="nav-link-icon"><i class="fas fa-cog"></i></span>
                        <span class="nav-link-title">Settings</span>
                    </a>
                </li>
                <li class="nav-item" style="<?php echo $showcredits; ?>">
                    <a class="nav-link" href="adminpacksettings.php">
                        <span class="nav-link-icon"><i class="fas fa-comment-dollar"></i></span>
                        <span class="nav-link-title">Pack Settings</span>
                    </a>
                </li>
                <li class="nav-item" style="<?php echo $showads; ?>">
                    <a class="nav-link" href="adminadsettings.php">
                        <span class="nav-link-icon"><i class="fas fa-ad"></i></span>
                        <span class="nav-link-title">Ad Settings</span>
                    </a>
                </li>
                <li class="nav-item" style="<?php echo $showar; ?>">
                    <a class="nav-link" href="adminarintegrations.php">
                        <span class="nav-link-icon"><i class="fas fa-cogs"></i></span>
                        <span class="nav-link-title">AR Integrations</span>
                    </a>
                </li>
                <li class="nav-item" style="<?php echo $showar; ?>">
                    <a class="nav-link" href="adminleadcapture.php">
                        <span class="nav-link-icon"><i class="fas fa-envelope"></i></span>
                        <span class="nav-link-title">Lead Capture</span>
                    </a>
                </li>
                <li class="nav-item" style="<?php echo $showpage; ?>">
                    <a class="nav-link" href="adminsalespage.php">
                        <span class="nav-link-icon"><i class="fab fa-html5"></i></span>
                        <span class="nav-link-title">Salespage</span>
                    </a>
                </li>
                <li class="nav-item" style="cursor:pointer;">
                    <a class="nav-link" id="adminLogout">
                        <span class="nav-link-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="nav-link-title">Logout Admin</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
