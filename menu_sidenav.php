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

// Map the old SB Admin 2 color select to a Tabler sidebar theme class
$sidebarTheme = "navbar-dark";
$sidebarBg    = "bg-blue-lt";
switch ($menucolorselect){
    case 0: $sidebarBg = "bg-blue";    break;
    case 1: $sidebarBg = "bg-secondary"; break;
    case 2: $sidebarBg = "bg-green";   break;
    case 3: $sidebarBg = "bg-cyan";    break;
    case 4: $sidebarBg = "bg-yellow";  $sidebarTheme = "navbar-light"; break;
    case 5: $sidebarBg = "bg-red";     break;
    case 6: $sidebarBg = "bg-dark";    break;
    case 7: $sidebarBg = "bg-white";   $sidebarTheme = "navbar-light"; break;
}
?>
<aside class="navbar navbar-vertical navbar-expand-lg <?php echo $sidebarTheme; ?> <?php echo $sidebarBg; ?>">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand py-3 text-center">
            <img src="img/logo.png?<?php echo time(); ?>" class="navbar-brand-img" alt="Logo" style="max-width:140px; max-height:60px; width:auto; height:auto;">
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="projects.php">
                        <span class="nav-link-icon"><i class="fas fa-clipboard-list"></i></span>
                        <span class="nav-link-title">Manage Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="newproject.php?id=0">
                        <span class="nav-link-icon"><i class="fas fa-rocket"></i></span>
                        <span class="nav-link-title">New Project</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php">
                        <span class="nav-link-icon"><i class="fas fa-folder-plus"></i></span>
                        <span class="nav-link-title">Manage Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usersettings.php">
                        <span class="nav-link-icon"><i class="fas fa-cog"></i></span>
                        <span class="nav-link-title">Settings</span>
                    </a>
                </li>
<?php
for ($i = 1; $i <= 5; $i++) {
    $menutext = "menutext" . $i;
    $menulink = "menulink" . $i;
    if ($$menutext != "") {
        echo '<li class="nav-item"><a class="nav-link" target="_BLANK" href="'.$$menulink.'"><span class="nav-link-icon"><i class="fas fa-rocket"></i></span><span class="nav-link-title">'.$$menutext.'</span></a></li>';
    }
}
?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <span class="nav-link-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="nav-link-title">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
