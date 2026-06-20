<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");

if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

try {
    $st = $db->prepare("SELECT * FROM sales");
    $st->execute();
    $results = $st->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$tableout="";

foreach ($results as $result) {
	if ($result["type"]==0){$trans_type="Sale";}else{$trans_type="Subscription";}
	
	$tableout.="<tr>";
	$tableout.="<td>".$result["id"]."</td>";
	$tableout.="<td>".$result["pack_name"]."</td>";
	$tableout.="<td>".$result["name"]."</td>";
	$tableout.="<td>".$result["email"]."</td>";
	$tableout.="<td>".$result["buyer_ip"]."</td>";
	$tableout.="<td>".$result["purchase_date"]."</td>";
	$tableout.="<td>".$result["cancel_date"]."</td>";
	$tableout.="<td>".$result["txid"]."</td>";
	$tableout.="<td>".$trans_type."</td>";
	$tableout.="<td>".$result["credits"]."</td>";
	$tableout.="<td>".$result["sale_amount"]."</td>";
	$tableout.="</tr>";
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

    <title><?php echo $sitename; ?></title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="libs/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css" integrity="sha512-PT0RvABaDhDQugEbpNMwgYBCnGCiTZMh9yOzUsJHDgl/dMhD9yjHAwoumnUk3JydV3QTcIkNDuN40CJxik5+WQ==" crossorigin=	"anonymous" referrerpolicy="no-referrer" />

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

       <?php require_once("admin_menu_sidenav.php"); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid" style="margin-top:50px;"	>
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Sales</h1>
                    <p class="mb-4">You can manage view your credit sales here.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
			
                        </div>
                        <div class="card-body">
		                    <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pack</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>IP Address</th>
                                            <th>Purchase Date</th>
                                            <th>Cancel Date</th>
                                            <th>TXID</th>
                                            <th>Type</th>
                                            <th>Credits</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pack</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>IP Address</th>
                                            <th>Purchase Date</th>
                                            <th>Cancel Date</th>
                                            <th>TXID</th>
                                            <th>Type</th>
                                            <th>Credits</th>
                                            <th>Amount</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                          
										
										<?php echo $tableout; ?>
									                                        
                                            
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <?php echo $footer; ?>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="adminlogout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>


	<div class="toast bg-gray-200" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; top: 1rem; right: 1rem;" id="banToast">
	  <div class="toast-header bg-gray-400">
		<strong class="mr-auto"><span id="banToastHeader"></span></strong>

		<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="toast-body">
		<span id="banToastBody"></span>
	 </div>
	</div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js" integrity="sha512-0QbL0ph8Tc8g5bLhfVzSqxe9GERORsKhIn1IrpxDAgUsbBGz/V7iSav2zzW325XGd1OMLdL4UiqRJj702IeqnQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Custom scripts for all pages-->
    <script src="libs/sbadmin2/js/sb-admin-2.min.js"></script>

		<!-- Page level plugins -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js" integrity="sha512-OQlawZneA7zzfI6B1n1tjUuo3C5mtYuAWpQdg+iI9mkDoo7iFzTqnQHf+K5ThOWNJ9AbXL4+ZDwH7ykySPQc+A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
	$('#dataTable').DataTable({

	});

});

$('#adminLogout').click(function(){
	$('#logoutModal').modal('show');

});


</script>

</body>

</html>