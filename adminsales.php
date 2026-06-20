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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="css/tabler-compat.css">

    <!-- Custom styles for this page -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

</head>

<body class="antialiased">

    <!-- Page Wrapper -->
    <div class="wrapper">

       <?php require_once("admin_menu_sidenav.php"); ?>
        <!-- Content Wrapper -->
        <div class="page-wrapper">
            <!-- Main Content -->
            <div class="page-body">
                <!-- Begin Page Content -->
                <div class="container-xl py-4">
                    <!-- Page Heading -->
                    <h2 class="page-title">Sales</h2>
                    <div class="text-muted mb-3">You can manage view your credit sales here.</div>

                    <div class="card shadow-sm">
                        <div class="card-header">
			
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
            <footer class="footer sticky-footer bg-white">
                <div class="container text-center py-2">
                    <?php echo $footer; ?>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top" href="#">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="adminlogout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>


	<div class="toast bg-gray-200" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; top: 1rem; right: 1rem;" id="banToast">
	  <div class="toast-header bg-gray-400">
		<strong class="me-auto"><span id="banToastHeader"></span></strong>

		<button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
	  </div>
	  <div class="toast-body">
		<span id="banToastBody"></span>
	 </div>
	</div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <!-- Core plugin JavaScript-->

    <!-- Custom scripts for all pages-->

	<!-- Page level plugins -->
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
	$('#dataTable').DataTable({

	});

});

$('#adminLogout').click(function(){
	new bootstrap.Modal(document.getElementById('logoutModal')).show();

});


</script>

</body>

</html>