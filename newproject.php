<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");
$add2=0;
if (file_exists("add2.php")){require_once("add2.php");}
if ($add2==1){$showcredits="";}else{$showcredits="display:none;";}
$id=-1;

if (isset($_GET["id"])){$id=intval($_GET["id"]);}


if ($id===-1){
	header("location:projects.php");
}

	
$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

$st = $db->prepare("SELECT cat_id,cat_name FROM categories WHERE cat_userid=:userid");
$st->bindParam(':userid', $userid);
$st->execute();
$user=[];
$results = $st->fetchAll();
$catout="";
if ($results){
	
	foreach ($results as $key => $value) {
		$catout.='<option value="'.$value["cat_id"].'">'.$value["cat_name"].'</option>';
	}
} else {
	$catout="";
}
$project_name="";
$audience="";
$tone=10;
$category=0;
$language=3;
$p1_input="";
$numdays=1;
$product_name="";
$isposted=0;      // ← ADD THIS
$p1_content="";   // ← ADD THIS

$baseNames = ['bonusnum', 'bonusname', 'bonusdesc', 'bonusurl', 'bonusthumb'];

foreach ($baseNames as $baseName) {
    for ($i = 1; $i <= 5; $i++) {
        if ($baseName == 'bonusnum') {
            ${$baseName . $i} = 0;
        } else {
            ${$baseName . $i} = "";
        }
    }
}

for ($i = 1; $i <= 9; $i++) {
    $variableName = 's' . $i;
    $$variableName = "";
}
$p1_wordcount=0;
if($id > 0){
	$query = $db->prepare("SELECT * FROM projects WHERE id = :id AND user_id = :user_id");
	$query->execute(['id' => $id, 'user_id' => $userid]);
	$result = $query->fetch(PDO::FETCH_ASSOC);
	if($result === false){
		header('Location: projects.php');
		exit();
	}
	extract($result);
	$p1_wordcount=str_word_count($p1_input);
}

$usercredits=10000000;
// Check for available credits if not editmode and credits system installed
if ($add2==1){
	$usercredits=0;
		try {
			$query = "SELECT user_credits FROM users WHERE user_id = :userid";
			$stmt = $db->prepare($query);
			$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if ($result) {
				$usercredits = $result['user_credits'];
			} else {
				echo "No user found with the given user_id.";
			}

		} catch(PDOException $e) {
			echo "Error getting words left";
		}
		if ($usercredits<200) {
			header("location:projects.php?w=1");
		}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
    <title><?php echo $sitename; ?></title>

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="css/tabler-compat.css">
	
	<style>
		.topgap {
			margin-top: 15px;
		}
		.topgap2 {
			margin-top: 20px;
		}
		#overlay {
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			width: 100vw;
			background-color: rgba(0, 0, 0, 0.6);
			display: none;
			align-items: center;
			justify-content: center;
			z-index: 9999;
		}

		#overlay.display-flex {
			display: flex;
		}

		#overlay-text {
			color: white;
			font-family:Nunito;
			font-size: 24px;
			pointer-events: none;
			user-select: none;
		}
		
		.nav-item .nav-link.active {
			background-color: #9f9f9f !important;
			color:#fff !important;
			font-weight:bold !important;
			border-bottom:0px !important;
			border-right:0px !important;
		}
		
		.link-text { color:#555 !important; }
		
		.tab-back { background-color:#f2f2f2; !important; border-right:1px solid #dfdfdf !important; }
		
		.card-flat-top { border-radius:0px 0px 5px 5px; !important; }
		
		#postButton .dropdown-menu .disabled {
			pointer-events: none;
			opacity: 0.65;  /* Optional: gives a visual indication of being disabled */
		}
		
		#postedmessage:hover, #postedmessage:focus {
			background-color: #717384 !important;
			border-color: #6b6d7d !important;
			color: #fff !important;
			box-shadow: none !important;
			opacity: 1 !important;
		}
		
		option.generated {
		  background-color: #FFFFBB;
		}
		.fast-spin {
			-webkit-animation: fa-spin 1s infinite linear;
			animation: fa-spin 1s infinite linear;
		}		

		
	</style>	

</head>

<body class="antialiased">

    <div class="wrapper">

       <?php require_once("menu_sidenav.php"); ?>
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl py-4">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Project Creation / Editing</h1>
                    <p class="mb-4">You can create and edit your projects here.</p>

                    <div class="card shadow mb-4" id="outerWrapper" style="display:none;">
                        <div class="card-header py-3">
							<span id="outerHeader">

							</span>
							<input id="add2" style="display:none;" value="<?php echo($add2); ?>">
							<input id="project_id" style="display:none;" value="<?php echo($id); ?>">
							<input id="isposted" style="display:none;" value="<?php echo($isposted); ?>">
							<input id="tone_store" style="display:none;" value="<?php echo($tone); ?>">
							<input id="language_store" style="display:none;" value="<?php echo($language); ?>">
							<input id="category_store" style="display:none;" value="<?php echo($category); ?>">
							<textarea style="display:none;" name="p1_input" id="p1_input" ><?php echo $p1_input; ?></textarea>
							

							<?php
							for ($i = 1; $i <= 9; $i++) {
								$variableName = 's' . $i;
								$value = isset($$variableName) ? $$variableName : '';  // Dynamically get the variable's value
								echo "<textarea style='display:none!important;' id='$variableName'>$value</textarea>";
							}
							?>
							
							<?php
							for ($i = 1; $i <= 5; $i++) {
								echo '<input id="bonusnum' . $i . '" style="display:none;" name="bonusnum' . $i . '" value ="' . ${'bonusnum' . $i} . '">';
								echo '<input id="bonusname' . $i . '" style="display:none;" name="bonusname' . $i . '" value ="' . ${'bonusname' . $i} . '">';
								echo '<input id="bonusdesc' . $i . '" style="display:none;" name="bonusdesc' . $i . '" value ="' . ${'bonusdesc' . $i} . '">';
								echo '<input id="bonusurl' . $i . '" style="display:none;" name="bonusurl' . $i . '" value ="' . ${'bonusurl' . $i} . '">';
								echo '<input id="bonusthumb' . $i . '" style="display:none;" name="bonusthumb' . $i . '" value ="' . ${'bonusthumb' . $i} . '">';
							}
							?>
							
							
							<textarea id="p1_content" style="display:none;" ><?php echo($p1_content); ?></textarea>
							
							<button id="saveProject" class="btn btn-primary btn-icon-split float-end" style="margin-right:5px;" <?php if ($id==0){echo 'disabled="disabled"';} ?>><span class="icon text-white-50"><i class="fas fa-save"></i></span><span class="text">Save All</span></button>

							<a href="projects.php" id="cancelButton" class="btn btn-danger btn-icon-split float-end" style="margin-right:5px;"><span class="icon text-white-50"><i class="fas fa-window-close"></i></span><span class="text">Cancel</span></a>
							<span class="float-start form-inline" style="<?php echo $showcredits; ?>">Credits Left:&nbsp;&nbsp;<div class="form-control fake-input" id="usercredits" readonly ><?php echo $usercredits; ?></div><input id="userid" type="text" style="display:none;" value="<?php echo $userid; ?>"></span>							
                        </div>
                        <div class="card-body">
							<span id="step-1" style="<?php if($id!=0){echo 'display:none;';} ?>">
							<div class="row">
								<div class="col-12">
									<div class="alert alert-primary">Paste in your source or scrape the URL.  Please edit (or use the cut button) until the content is less than 6000 words and the "Lock" button is enabled.  Once you are happy you can "Lock" in your source and continue.</div>
								</div>
							</div>
							
							<div class="row topgap">
								<div class="col-12">
									<textarea id="scrapeContent" class="form-control" style="height:400px; border-width:3px;"></textarea>
								</div>
							</div>
							<div class="row topgap">
								<div class="col-3">
									<label for="">Product Name</label>
									<input type="text" class="form-control" placeholder="Optional but recommended" maxlength="500" name="product_name" id="product_name" value="<?php echo $product_name; ?>">
								</div>
								<div class="col-4">
									<label for="">URL to Scrape</label>
									<div class="input-group">
										<input type="text" class="form-control" placeholder="https://urltoscrape.com" maxlength="300" name="urlToScrape" id="urlToScrape" value="">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary" type="button" id="btnScrapeUrl"><i class="far fa-file-code"></i> Scrape URL</button>
										</div>
									</div>
								</div>
								<div class="col-1">
									<label for="">Word #</label>
									<input type="text" readonly class="form-control" placeholder="" maxlength="300" name="" id="wordCount" value="<?php echo $p1_wordcount; ?>">
								</div>
								
								<div class="col-4" style="">
									<label for="">&nbsp;</label><br>
									<button id="btnCut" class="btn btn-info btn-icon-split " style="margin-right:5px;"><span class="icon text-white-50"><i class="fas fa-cut"></i></span><span class="text">Auto Cut</span></button>
									<button id="btnLock" disabled="disabled" class="btn btn-success btn-icon-split float-end" style="margin-right:5px;"><span class="icon text-white-50"><i class="fas fa-forward"></i></span><span class="text">Lock</span></button>
									<button id="btnClear" class="btn btn-danger btn-icon-split " style="margin-right:5px;"><span class="icon text-white-50"><i class="fas fa-trash-alt"></i></span><span class="text">Clear</span></button>
								</div>
							</div>
							</span>	
							<span id="step-2" style="<?php if($id==0){echo 'display:none;';} ?>">
							<div class="row">
								<div class="col-2">
									<label>Project Name</label>
									<input type="text" class="form-control validate" placeholder="Name (internal use - 50 chars)" maxlength="50" name="projectname" id="projectname" value="<?php echo $project_name; ?>">
									<label class="form-check-label text-danger" style="display:none;" for="projectname">You must provide a project name!</label>
								</div>
								<div class="col-2">
									<label>Target Audience</label>
									<input type="text" class="form-control validate" placeholder="(Optional)" maxlength="250" name="audience" id="audience" value="<?php echo $audience; ?>">
									<label class="form-check-label text-danger" style="display:none;" for="projectname">You must provide a target audience!</label>
								</div>
								<div class="col-2">
								<label>Type</label><br>
									<select name="stype" id="stype" class="form-control custom-select" style="">
										<option value="1">Summary</option>
										<option value="2">Email Sequence</option>
										<option value="3">Bonus Page Brief</option>
										<option value="4">Facebook Posts</option>
										<option value="5">Tik Tok Script</option>
 										<option value="6">Video Script</option>
										<option value="7">Hashtags</option>
										<option value="8">Campaign Strategy</option>
										
									</select>
								</div>								
								<div class="col-2">
									<label for="category">Category</label>
									<div class="input-group">
										<select name="category" id="category" class="form-control custom-select">
											<option value="0">No Category</option>
											<?php echo $catout; ?>
										</select>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary addcatbutton" type="button"><i class="fas fa-plus-circle"></i></button>
										</div>
									</div>
								</div>
								<div class="col-2">
									<label>Language</label><br>
									<select name="language" id="language" class="form-control custom-select" style="">
										<option value="1">Chinese</option>
										<option value="2">Dutch</option>
										<option value="3" selected="selected">English</option>
										<option value="4">French</option>
										<option value="5">German</option>
										<option value="6">Italian</option>
										<option value="7">Japanese</option>
										<option value="8">Korean</option>
										<option value="9">Portuguese</option>
										<option value="10">Russian</option>
										<option value="11">Spanish</option>
										<option value="12">Vietnamese</option>
									</select>
								</div>
								<div class="col-2">
									<label>Tone</label>
									<select name="tone" id="tone" class="form-control custom-select" style="">
										<option value="1">Authoritative</option>
										<option value="2">Casual</option>
										<option value="3">Confident</option>
										<option value="4">Conversational</option>
										<option value="5">Educational</option>
										<option value="6">Empathetic</option>
										<option value="7">Encouraging</option>
										<option value="8">Enthusiastic</option>
										<option value="9">Friendly</option>
										<option value="10" selected="selected">Informative</option>
										<option value="11">Lighthearted</option>
										<option value="12">Optimistic</option>
										<option value="13">Persuasive</option>
										<option value="14">Professional</option>
										<option value="15">Straightforward</option>
										<option value="16">Technical</option>
									</select>
								</div>
								<div class="" style="display:none;">
									<label>Days</label>
									<input type="number" min="1" max="14" class="form-control validate" placeholder="" maxlength="50" name="numdays" id="numdays" value="<?php echo $numdays; ?>">
								</div>
							</div>
							
							<div class="row topgap">
								<div class="col-2">
									<label for="">Bonus 1</label>
									<div class="input-group">
										<input type="text" class="form-control" readonly  name="bonus1" id="bonus1" value="<?php echo $bonusname1; ?>">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary" type="button" onclick="selectBonus(1);"><i class="fas fa-file-archive"></i> Select</button>
										</div>
									</div>									
								</div>
								<div class="col-2">
									<label for="">Bonus 2</label>
									<div class="input-group">
										<input type="text" class="form-control" readonly name="bonus2" id="bonus2" value="<?php echo $bonusname2; ?>">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary" type="button" onclick="selectBonus(2);"><i class="fas fa-file-archive"></i> Select</button>
										</div>
									</div>									
								</div>
								<div class="col-2">
									<label for="">Bonus 3</label>
									<div class="input-group">
										<input type="text" class="form-control" readonly name="bonus3" id="bonus3" value="<?php echo $bonusname3; ?>">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary" type="button" onclick="selectBonus(3);"><i class="fas fa-file-archive"></i> Select</button>
										</div>
									</div>									
								</div>
								<div class="col-2">
									<label for="">Bonus 4</label>
									<div class="input-group">
										<input type="text" class="form-control" readonly name="bonus4" id="bonus4" value="<?php echo $bonusname4; ?>">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary" type="button" onclick="selectBonus(4);" ><i class="fas fa-file-archive"></i> Select</button>
										</div>
									</div>									
								</div>
								<div class="col-2">
									<label for="">Bonus 5</label>
									<div class="input-group">
										<input type="text" class="form-control" readonly name="bonus5" id="bonus5" value="<?php echo $bonusname5; ?>">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary" type="button" onclick="selectBonus(5);"><i class="fas fa-file-archive"></i> Select</button>
										</div>
									</div>									
								</div>								
								<div class="col-2">
									<label>&nbsp;</label><br>
									<button id="p1-generate" class="btn btn-success btn-icon-split float-end generate" style="margin-right:5px;"><span class="icon text-white-50"><i class="fas fa-sync-alt"></i></span><span class="text">Generate</span></button>										
								</div>
								
							</div>							


							<div id="outputarea" class="topgap">  <!-- Start Output Area -->
							
								

					
								
								<div id="panel_tab1" class="card card-flat-top" style="display:block;">
									<div class="card-body">
										<div class="row topgap">

											<div class="col-12">
												<div id="p1_editorarea"></div>
											</div>
										</div>
										<div class="row topgap">
											<div class="col-12">
												<div id="p1_docHtml" class="googoose-wrapper" style="display:none;"></div>
	
												<div class="btn-group float-end" style="margin-right:10px">
													<button type="button" class="btn btn-primary btn-icon-split float-end"><span class="icon text-white-50"><i class="fas fa-file-export"></i></span><span class="text">Export</span></button>
													<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
													<div class="dropdown-menu">
														<a class="dropdown-item exportbutton" data-id="doc" data-section="p1" href="#">DOC File</a>
														<a class="dropdown-item exportbutton" data-id="txt" data-section="p1" href="#">Text File</a>
														<a class="dropdown-item exportbutton" data-id="pdf" data-section="p1" href="#">PDF File</a>
													</div>
												</div>
												<button id="p1" class="btn btn-info btn-icon-split float-end copybutton" style="margin-right:5px;"><span class="icon text-white-50"><i class="fas fa-copy"></i></span><span class="text">Copy</span></button>
											</div>
										</div>
			
									</div>
								</div>
								

							</div> <!-- End Output Area -->
						</span>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <footer class="footer sticky-footer bg-white">
                <div class="container text-center py-2">
                    <?php echo $footer; ?>
                </div>
            </footer>

        </div>
    </div>

    <a class="scroll-to-top" href="#">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bonus Select Modal -->
    <div class="modal fade" id="bonusModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Bonus Number <span id="dispBonusNum"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="row">
						<div class="col-6">
							<label>Select Bonus Type</label>
							<select name="bonus_cat" class="form-control custom-select" id="bonus_cat">
								<option value="0">All</option>
								<option value="1">Traffic</option>
								<option value="2">Video</option>
								<option value="3">Ads</option>
								<option value="4">Stock</option>
								<option value="5">Email</option>
								<option value="6">Wordpress</option>
								<option value="7">Funnels</option>
								<option value="8">Affiliate</option>
								<option value="9">NON IM</option>
								<option value="10">Ecommerce</option>
								<option value="11">Training</option>
								<option value="12">Social</option>
								<option value="13">PLR</option>
							</select>						
						</div>
						<div class="col-6">
							<label>Select Bonus</label>
							<select class="form-control custom-select" id="bonusData">
								
							</select>						
						</div>
					</div>
					<div class="row topgap">
						<div class="col-12">
							<label>Description of bonus</label>
							<input class="form-control" id="bonusDesc" readonly>
						</div>
					</div>
					<div class="row" style="margin-top:20px;">
						<div class="col-12" style="text-align:center;">
							<img id="bonusImg">
						</div>
					</div>
				
				</div>
                <div class="modal-footer">
					<button class="btn btn-danger btn-icon-split" style="margin-right:5px;" data-bs-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-window-close"></i></span><span class="text">Cancel</span></button>
					<button id="btnSelectBonus" class="btn btn-primary btn-icon-split" style="margin-right:5px;" data-bs-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-check-square"></i></span><span class="text">Select</span></button>
                </div>
            </div>
        </div>
    </div>


    <!-- AI Error Modal -->
    <div class="modal fade" id="s2errorModal" tabindex="-1" role="dialog" aria-labelledby="s2ErrorLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="s2ErrorLabel">AI Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">There has been a problem connecting to the AI. This could be because the service is currently too busy.  Please try again.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>


	<!-- API Key Error Modal -->
    <div class="modal fade" id="userkeyModal" tabindex="-1" role="dialog" aria-labelledby="userkeyLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userkeyLabel">API Key Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">There has been a problem connecting to the AI. Your OpenAI API key is either missing or invalid - Please set it correctly in your Settings dashboard.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>	

	<!-- Add Category Modal -->
    <div class="modal fade" id="addcatModal" tabindex="-1" role="dialog" aria-labelledby="addcatLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addcatLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="row">
						<div class="col-12">
							<label>New Category</label>
							<input type="text" class="form-control" placeholder="New Category" maxlength="50" name="addcat" id="addcat" value="">
						</div>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-icon-split" data-bs-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-window-close"></i></span><span class="text">Cancel</span></button>
					<button type="button" class="btn btn-primary btn-icon-split" onclick="addNewCat();" data-bs-dismiss="modal"><span class="icon text-white-50"><i class="fas fa-plus-circle"></i></span><span class="text">Add Category</span></button>
                </div>
            </div>
        </div>
    </div>	


	<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1090;">
	<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="generalToast">
	  <div class="toast-header">
		<strong class="me-auto" id="generalToastHeader"></strong>
		<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
	  </div>
	  <div class="toast-body" id="generalToastBody"></div>
	</div>
	</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

	<!-- Page level plugins -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/aadel112/googoose@master/jquery.googoose.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js" integrity="sha512-sWydClczl0KPyMWlARx1JaxJo2upoMYb9oh5IHwudGfICJ/8qaCyqhNTP5aa9Xx0aCRBwh71eZchgz0a4unoyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>

function selectBonus(bonusnumber){
	$('#dispBonusNum').text(bonusnumber);
	new bootstrap.Modal(document.getElementById('bonusModal')).show();
	$('#bonus_cat').trigger('change');
}

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;  
    }
}

function createBonusInfo(){
	var bonusinfo='<p><h3 style="text-align:center; font-family: Verdana;">Here are your bonus downloads and graphics.</h3><br>';
	var pbreak=0;
	for (let i = 1; i < 6; i++) {
	  var bonusname=$('#bonusname'+i).val();
	  var bonusdesc=$('#bonusdesc'+i).val();
	  var bonusurl=$('#bonusurl'+i).val();
	  var bonusthumb=$('#bonusthumb'+i).val();
	  if (bonusname!=''){
		  bonusinfo+='<b>Bonus Name:</b><br>'+bonusname+'<br><br><b>Description:</b><br>'+bonusdesc+'<br><br><b>Download URL:</b><br><a href="'+bonusurl+'" target="_BLANK">'+bonusurl+'</a><br><br><b>Graphic Download:</b><br><a href="'+bonusthumb+'" target="_BLANK">'+bonusthumb+'</a><br><br><hr><br>';
		  pbreak++;
		  if (pbreak==2){
			  pbreak=0;
			  bonusinfo+='<div style="page-break-before: always;"></div>';
			  
		  }
		  $('#stype option[value="9"]').addClass('generated');
	  }
	}
	bonusinfo+='</p>';
	$('#s9').val(bonusinfo);
	$('#stype').trigger('change');
	
}

$('#numdays').change(function(){
	var numdays=$('#numdays').val();
	if (numdays<1){$('#numdays').val('1');}
	if (numdays>14){$('#numdays').val('14');}
});

$('#btnSelectBonus').click(function(){
	var bonusnum=$('#dispBonusNum').text();
	var bonusid=$('#bonusData').val();
	var bonusname = $('#bonusData option:selected').text();
		
	var bonusdesc=$('#bonusData option:selected').data('description');
	var bonusurl=$('#bonusData option:selected').data('delivery');
	var bonusthumb=$('#bonusData option:selected').data('thumbnail');
	
	$('#bonusnum'+bonusnum).val(bonusid);
	$('#bonusname'+bonusnum).val(bonusname);
	$('#bonusdesc'+bonusnum).val(bonusdesc);
	$('#bonusurl'+bonusnum).val(bonusurl);
	$('#bonusthumb'+bonusnum).val(bonusthumb);
	$('#bonus'+bonusnum).val(bonusname);
	
	createBonusInfo();
});

$('#bonus_cat').change(function() {
	var selectedCategory = $(this).val();

	$.ajax({
		url: 'AJAX_searchtags.php',
		type: 'GET',
		data: { category: selectedCategory },
		success: function(response) {
			var bonuses = JSON.parse(response);
			var bonusDataSelect = $('#bonusData');
			bonusDataSelect.empty(); 

			bonuses.forEach(function(bonus, index) {
				bonusDataSelect.append($('<option>', {
					value: bonus.bonus_id,
					text: bonus.bonus_name,
					'data-description': bonus.bonus_description,
					'data-thumbnail': bonus.bonus_thumbnail,
					'data-delivery': bonus.bonus_delivery
				}));
				
				if (index === 0) { 
					$('#bonusDesc').val(bonus.bonus_description);
					$('#bonusImg').attr('src', bonus.bonus_thumbnail);
				}
			});
		},
		error: function(xhr, status, error) {
			console.error("An error occurred: " + error);
		}
	});
});

$('#bonusData').change(function() {
	var selectedOption = $(this).find('option:selected');
	$('#bonusDesc').val(selectedOption.data('description'));
	$('#bonusImg').attr('src', selectedOption.data('thumbnail'));
});


//$('#btnLock').click(async function(){
//	$('#stype').val('1');
//	var scrapeContent=$('#scrapeContent').val();
//	$('#p1_input').val(scrapeContent);
//	$('#saveProject').prop('disabled',false);
//	await callAi();
//	$('#step-1').hide();
//	$('#step-2').show();
//});

$('#btnLock').click(async function(){
    $('#stype').val('1');
    var scrapeContent = $('#scrapeContent').val();
    $('#p1_input').val(scrapeContent);
    $('#saveProject').prop('disabled', false);
    $('#step-1').hide();
    $('#step-2').show();   // ← Show FIRST so TinyMCE is visible
    await callAi();
});

$('#btnCut').click(function(){
    var content = $("#scrapeContent").val();
    var words = content.split(/\s+/);

    if (words.length > 6000) {
        var trimmedContent = words.slice(0, 6000).join(' ');
        $("#scrapeContent").val(trimmedContent);
		$("#scrapeContent").trigger('input');
    }
});

$('#scrapeContent').on('input', function() {
	var textContent = $(this).val();
	var charCount = textContent.length;
	var wordCount = textContent.split(/\s+/).filter(function(word) {
		return word.length > 0;
	}).length;
	$('#wordCount').val(wordCount);
	if (wordCount>6000){
		$('#scrapeContent').css('border-color','red');
		$('#btnLock').prop('disabled',true);
	}else{
		$('#scrapeContent').css('border-color','green')
		$('#btnLock').prop('disabled',false);
	}
});

$('#btnClear').click(function(){
	$('#scrapeContent').val('');
	$('#wordCount').val('0');
	$('#btnLock').prop('disabled',true);
});

$("#btnScrapeUrl").click(function() {
	var url = $("#urlToScrape").val();
	if (isValidUrl(url)) {
		$('#overlay-message').text('Scraping content - Please wait...');
		showOverlay(true);
		$.ajax({
			url: 'AJAX_scrape.php',
			type: 'POST',
			data: { url: url },
			success: function(response) {
				$("#scrapeContent").val(response).trigger('input');
				showOverlay(false);
			},
			error: function() {
				alert("An error occurred while processing the request.");
				showOverlay(false);
			}
		});
	} else {
		alert("Please enter a valid URL.");
	}
});

$('#stype').change(function(){
    var stype = $(this).val();
    var txtext = $('#s' + stype).val();
    var editor = tinymce.get('p1_editorarea');
    if(editor) {
        editor.setContent(txtext);
    }
});


$('.generate').click(async function(){
	if(enoughCredits()==false){
		generalToast('Cannot Proceed','Sorry you do not have enough credits left.');
	} else {
		$('#saveProject').prop('disabled',false);
		await callAi();
	}
});



$('.copybutton').click(function() {
	var id=this.id;
	var editor = tinymce.get(id+'_editorarea');
	editor.execCommand('selectAll', true, 'texteditor');
	editor.execCommand( "Copy" );
	editor.selection.collapse();
	generalToast('Success!','The content has been copied to your clipboard.');
});


function callAi(){
	return new Promise((resolve, reject) => {	
		var stype=$('#stype').val();
		var panel_input=$('#p1_input').val();
		var language=$('#language').val();
		var tone=$('#tone').val();
		var audience=$('#audience').val();
		var numdays=$('#numdays').val();
		var product_name=$('#product_name').val();
		panel_input=panel_input.trim();
		var bonusname1=$('#bonusname1').val();
		var bonusdesc1=$('#bonusdesc1').val();
		var bonusname2=$('#bonusname2').val();
		var bonusdesc2=$('#bonusdesc2').val();
		var bonusname3=$('#bonusname3').val();
		var bonusdesc3=$('#bonusdesc3').val();
		var bonusname4=$('#bonusname4').val();
		var bonusdesc4=$('#bonusdesc4').val();
		var bonusname5=$('#bonusname5').val();
		var bonusdesc5=$('#bonusdesc5').val();
		if (panel_input!==""){
			var data = {
			  language: language,
			  tone: tone,
			  audience: audience,
			  panel_input: panel_input,
			  stype: stype,
			  bonusname1: bonusname1,
			  bonusdesc1: bonusdesc1,
			  bonusname2: bonusname2,
			  bonusdesc2: bonusdesc2,
			  bonusname3: bonusname3,
			  bonusdesc3: bonusdesc3,
			  bonusname4: bonusname4,
			  bonusdesc4: bonusdesc4,
			  bonusname5: bonusname5,
			  bonusdesc5: bonusdesc5,
			  numdays: numdays,
			  product_name: product_name
			};
			showOverlay(true);
			$('#overlay-message').text('Asking AI to generate content - Please wait...');	
			$.ajax({
			  url: 'AJAX_aiengine.php',
			  type: 'POST',
			  data: data,
			  success: function(response) {
                let errortest;
                try {
                    errortest = JSON.parse(response);
                } catch (error) {
                    console.error("The response is not valid JSON");
                    showOverlay(false);
                    new bootstrap.Modal(document.getElementById('s2errorModal')).show();
                    resolve();
                    return;  // ← STOP here, don't fall through
                }
            
                if (errortest == "NOUSERKEY") {
                    showOverlay(false);
                    new bootstrap.Modal(document.getElementById('userkeyModal')).show();
                    resolve();
                    return;  // ← STOP here
                }
            
                // ← NEW: guard against missing .choices
                if (!errortest.choices || !errortest.choices[0]) {
                    console.log("Unexpected API response:", response);
                    showOverlay(false);
                    new bootstrap.Modal(document.getElementById('s2errorModal')).show();
                    resolve();
                    return;
                }
            
                var content = errortest.choices[0].message.content;  // reuse errortest, don't re-parse
                content = content.replace(/\n/g, '<br>');
                content = content.replaceAll('(To be continued...)', '');
                content = content.replaceAll('To be continued', '');
                content = content.replaceAll('TO BE CONTINUED', '');
                content = content.replace(/\[.*?\]/g, '');
            
                let parts = content.split('<br><br>');
                for (let i = 0; i < parts.length; i++) {
                    parts[i] = '<p>' + parts[i] + '</p>';
                }
                let newText = parts.join('');
				if (stype == 3) {
					let bonusInfo = '';

					for (let i = 1; i <= 5; i++) {
						let bonusname = $('#bonusname' + i).val();
						let bonusdesc = $('#bonusdesc' + i).val();
						let bonusurl = $('#bonusurl' + i).val();
						let bonusthumb = $('#bonusthumb' + i).val();

						if (bonusname !== '') {
							bonusInfo += `
								<hr>
								<h3>${bonusname}</h3>
								<p>${bonusdesc}</p>
								<p><strong>Download URL:</strong><br>
								<a href="${bonusurl}" target="_blank">${bonusurl}</a></p>

								<p><strong>Thumbnail:</strong><br>
								<img src="${bonusthumb}" style="max-width:300px;"></p>
							`;
						}
					}

					newText += bonusInfo;
				} 
 
                var editor = tinymce.get('p1_editorarea');
                if (editor) {
                    editor.setContent(newText);
                }
                $('#s' + stype).val(newText);
                $('#stype option[value="' + stype + '"]').addClass('generated');
                showOverlay(false);
            
                var wordCount = getCount();
                wordCount = wordCount + parseInt($('#wordCount').val());
                var add2 = $('#add2').val();
                if (add2 == 1) {
                    $.ajax({
                        url: "AJAX_creditchange.php",
                        type: "POST",
                        data: { wordCount: wordCount },
                        success: function(response) { $('#usercredits').text(response); },
                        error: function(error) { console.log("Error:", error); }
                    });
                }
                resolve();
            },
			  error: function(xhr, status, error) {
					showOverlay(false);
					$('#s2errorModal').modal('show');
					resolve();
			  }
			});		
			

		} else {
			generalToast('ERROR: Field is empty!','The Subject field must NOT be empty!');
		}
		
	});
}


function generalToast(header,body){
			$('#generalToastHeader').text(header);
			$('#generalToastBody').text(body);
			var toast = new bootstrap.Toast(document.getElementById('generalToast'), {delay: 5000});
			toast.show();
}

$('.addcatbutton').click(function(){
	new bootstrap.Modal(document.getElementById('addcatModal')).show();
});

function addNewCat(whichsection){
	var newcat=$('#addcat').val().trim();
	if (newcat!=""){
		$('#addcat').val('');
		$.ajax({
			url: "AJAX_addnewcat.php",
			data: {newcatname:newcat},
			type: 'POST',
			success: function (response) {
				if (response!="ERROR"){
					$('#tablediv').show();
					var data = JSON.parse(response);
					var catId = data.cat_id;
					var catName = data.cat_name;
					var option = new Option(catName, catId);
					$('#category,#categoryEdit').append(option);
					$('#category,#categoryEdit').val(catId);
					$('#category,#categoryEdit').change();
					generalToast('Category Added','This Category has been added');
					
					$('#categoryEdit').change();					
				} else {
					generalToast('ERROR!','This Category has NOT been added');
				}
			}
		});
	}
	
}

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {type: contentType});
    return blob;
}

$('#saveProject').click(function(){
	var projectname=$('#projectname').val();
	projectname=projectname.trim();
	if (projectname===''){
		generalToast("ERROR!","You MUST enter a Project Name");
	} else {

		var p1_input = $('#p1_input').val();
		var audience = $('#audience').val();
		var language = $('#language').val();
		var tone=$('#tone').val();
		var category=$('#category').val();
		var project_id=$('#project_id').val();
		var projectname=$('#projectname').val();
		var numdays=$('#numdays').val();
		var product_name=$('#product_name').val();
		for (let i = 1; i <= 5; i++) {
			window['bonusnum' + i] = $('#bonusnum' + i).val();
			window['bonusname' + i] = $('#bonusname' + i).val();
			window['bonusdesc' + i] = $('#bonusdesc' + i).val();
			window['bonusurl' + i] = $('#bonusurl' + i).val();
			window['bonusthumb' + i] = $('#bonusthumb' + i).val();
		}		
		let sdata = {};

		for (let i = 1; i <= 9; i++) {
			let id = 's' + i;
			sdata[id] = btoa(unescape(encodeURIComponent($(`#${id}`).val())));
		}
	
		var data = {
		  projectname: projectname,
		  category: category,
		  id: project_id,
		  p1_input: p1_input,
		  audience: audience,
		  language: language,
		  tone: tone,
		  numdays: numdays,
		  product_name: product_name,
		  bonusnum1: bonusnum1,
		  bonusname1: bonusname1,
		  bonusdesc1: bonusdesc1,
		  bonusurl1: bonusurl1,
		  bonusthumb1: bonusthumb1,
		  bonusnum2: bonusnum2,
		  bonusname2: bonusname2,
		  bonusdesc2: bonusdesc2,
		  bonusurl2: bonusurl2,
		  bonusthumb2: bonusthumb2,
		  bonusnum3: bonusnum3,
		  bonusname3: bonusname3,
		  bonusdesc3: bonusdesc3,
		  bonusurl3: bonusurl3,
		  bonusthumb3: bonusthumb3,
		  bonusnum4: bonusnum4,
		  bonusname4: bonusname4,
		  bonusdesc4: bonusdesc4,
		  bonusurl4: bonusurl4,
		  bonusthumb4: bonusthumb4,
		  bonusnum5: bonusnum5,
		  bonusname5: bonusname5,
		  bonusdesc5: bonusdesc5,
		  bonusurl5: bonusurl5,
		  bonusthumb5: bonusthumb5,
		  ...sdata

		};	
		
		$('#saveProject').html('<span class="icon text-white-50"><i class="fas fa-sync-alt fa-spin fast-spin"></i></span><span class="text">Saving</span>').prop('disabled',true);
			
		$.ajax({
		  url: 'AJAX_save.php',
		  type: 'POST',
		  data: data,
		  success: function(response) {
			$('#saveProject').html('<span class="icon text-white-50"><i class="fas fa-save"></i></span><span class="text">Save All</span>').prop('disabled',false);
			generalToast('Project Saved!','Your project has been saved.');
			$('#project_id').val(response);
		  },
		  error: function(xhr, status, error) {
				console.log("ERROR WITH SAVE");
		  }
		});	
	}
});



$('.exportbutton').click(function(){
	let whichbutton=$(this).data('id');
	let stype=$('#stype').val();
	var grabdoc = tinymce.get("p1_editorarea").getContent();
	$('#p1_docHtml').html('<style>p { color: black; font-size: 12pt; font-family: Verdana; }</style>'+grabdoc);
	var filename=$('#projectname').val();
	if(filename==''){filename='myfile';}
	
	var filenameModifier='';
	var stypeModifiers = {
		"1" : "-SUMMARY",
		"2": "-EMAIL-SEQUENCE",
		"3": "-BONUS-PAGE-BRIEF",
		"4": "-FB-POSTS",
		"5": "-TIKTOK-SCRIPT",
		"6": "-VIDEO-SCRIPT",
		"7": "-HASHTAGS",
		"8": "-STRATEGY",
		"9": "-BONUS-INFO"

	};



	var filenameModifier = stypeModifiers[stype];
	filename = filename.replace(/\W+/g, " ");
	filename = filename.replace(/\s+/g, "-");
	filename = filename+filenameModifier;
	
	switch(whichbutton){
		case "txt":
			var grabtext = tinymce.get("p1_editorarea").getContent({ format: "text" });
			var blob = new Blob([grabtext], {type: "text/plain"});
			var url = URL.createObjectURL(blob);
			var a = document.createElement('a');
			a.href = url;
			a.download = filename+'.txt';
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			generalToast('Text File Created','Text File Created : '+filename+'.txt');
		break;
		
		case "doc":
			var o = {
				filename: filename+'.doc'
			};
			$('#p1_docHtml').show();
			$(document).googoose(o);
			$('#p1_docHtml').hide();
			generalToast('Doc File Created','DOC File Created : '+filename+'.doc');
		break;
		
		case "pdf":
			grabdoc = grabdoc.replace(/[\u{1f300}-\u{1f5ff}\u{1f600}-\u{1f64f}\u{1f680}-\u{1f6ff}\u{1f900}-\u{1f9ff}]/gu, '');
			grabdoc='<style>p { color: black; font-size: 12pt; font-family: Verdana; } img { max-width:100%; height:auto; }</style>'+grabdoc;
			
			$.ajax({
				url: 'AJAX_dopdf.php',
				type: 'POST',
				data: { 
					content: encodeURIComponent(grabdoc)
				},
				 success: function(response) {
						var blob = b64toBlob(response, "application/pdf");
						var blobUrl = URL.createObjectURL(blob);

						var link = $('<a>');
						link.attr('href', blobUrl);
						link.attr('download', filename+'.pdf');

						$('body').append(link);
						link[0].click();
						link.remove();
					},
					error: function(xhr, status, error) {

						console.log("Error Creating PDF");
					}
			});	
			generalToast('PDF File Created','PDF File Created : '+filename+'.pdf');
		break;
		
	}
	
});

function getResponseAI(language, tone, panel_input, mode){
    return new Promise((resolve, reject) => {
        $('#overlay-message').html('Asking AI to generate content. Please wait...');
        showOverlay(true);
		var data = {
		  language: language,
		  tone: tone,
		  panel_input: panel_input,
		  mode: mode
		};
        $.ajax({
            url: 'AJAX_aiengine.php',
            type: 'POST',
            data: data,
			cache: false,
            success: function(response) {
				
				try {
				  errortest = JSON.parse(response);
				} catch (error) {
					console.error("The response is not valid JSON in getResponseAI"); 
					
					showOverlay(false);
					$('#s2errorModal').modal('show');					  
				    errortest=response;
				    return;  // ← ADD THIS
				}
				if (errortest.error){
					console.log("ERROR WITH AJAX CALL CHAPTER - errortest!!");
					showOverlay(false);
					$('#s2errorModal').modal('show');
					reject("API error");  // ← ADD
                    return;               // ← ADD
				} else {
                if (!errortest.choices || !errortest.choices[0]) {
                    console.log("Unexpected API response:", response);
                    showOverlay(false);
                    new bootstrap.Modal(document.getElementById('s2errorModal')).show();
                    reject("No choices in response");
                    return;
                }
                showOverlay(false);
                var content = errortest.choices[0].message.content;  // reuse errortest
                content = content.replace(/\n/g, '<br>');
                content = content.replaceAll('(To be continued...)', '');
                content = content.replaceAll('To be continued', '');
                content = content.replaceAll('TO BE CONTINUED', '');
                content = content.replace(/\[.*?\]/g, '');
                resolve(content);
            }
            },
            error: function(xhr, status, error) {
                //console.error(error);
                showOverlay(false);
                $('#s2errorModal').modal('show');
                reject(error); // Reject the Promise with the error
            }
        });
    });
}

function showOverlay(show) {
    if(show) {
        $('#overlay').addClass('display-flex');
    } else {
        $('#overlay').removeClass('display-flex');
    }
}


$(document).ready(function() {


    $('#stype option').each(function() {
        var optionValue = $(this).val();
        var correspondingTextarea = $('#s' + optionValue);
        if (correspondingTextarea.length === 0) {
            return true;
        }
        if (correspondingTextarea.val().trim() !== '') {
            $(this).addClass('generated');
        }
    });

		
	$('#category').val($('#category_store').val());
	$('#tone').val($('#tone_store').val());
	$('#language').val($('#language_store').val());
	
	$('#outerWrapper').show();
	
	tinymce.init({
		selector: '#p1_editorarea',
		promotion: false,
		branding: false,
		inline_styles : true,
		content_style: "p { color: black; font-size: 12pt; font-family: Verdana; margin: 1rem auto; max-width: 1400px; }",	
		toolbar: "aligncenter alignjustify alignleft alignnone alignright| blockquote styles | image | link | bold italic underline strikethrough | copy | cut | fontfamily fontsize backcolor forecolor hr indent | language | lineheight | newdocument | outdent | paste pastetext | redo | selectall | searchreplace | subscript superscript | undo | fullscreen | code",
	    plugins: "link image wordcount fullscreen code searchreplace emoticons",
		setup: function (editor1) {
		 editor1.on('change', function (e) {
			var content = editor1.getContent();
			var stype=$('#stype').val();
			$('#s'+stype).val(content);
		});			
		  editor1.on('init', function (e) {
            var firstContent = getFirstContent();
            if (firstContent != null) {
                var content = $('#s' + firstContent).val();
                editor1.setContent(content);              // use editor1 directly
                $('#stype')[0].value = firstContent;      // set silently, no change event
            }
        });
		}
	});	


		
	$('input:not(#step4 input,#urlToScrape), #projectnameEdit').on('input', function() {
	  var inputValue = $(this).val();
	  var sanitizedValue = inputValue.replace(/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_\-=\,\.À-ÖØ-öø-ÿ]/g, '');
	  $(this).val(sanitizedValue);
	});


	
});

function getFirstContent() {
    for (let i = 1; i <= 9; i++) {
        let id = 's' + i;
        let value = $("#" + id).val().trim();

        if (value !== "") {
            return id.substring(1);
        }
    }
    return null; 
}

function getCount(){
	tinyeditor = tinymce.activeEditor;
	wordCount = tinyeditor.plugins.wordcount.getCount();
	return wordCount;
}


function enoughCredits(){
	if ($('#add2').val()==1){
		var mincreds = parseInt($('#wordCount').val())+1000;
		if(parseInt($('#usercredits').text())< mincreds) {
			return false;
		} else {
			return true;
		}
	}
}
</script>
<div id="overlay">
    <div id="overlay-text"><i class="fas fa-sync-alt fa-spin fast-spin" style="margin-right:15px"></i><span id="overlay-message">Communicating with AI - Please Wait...</span></div>
</div>

</body>

</html>