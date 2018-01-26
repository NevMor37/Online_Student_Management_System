<?php
header("Access-Control-Allow-Origin: *");
if (isset($_SERVER['HTTP_HOST']))
{
	if($_SERVER['HTTP_HOST'] == "localhost")
	{
		$root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
		define("ROOT",$root."/student/ogms/public_html");
		$root = ROOT;
	}
	else 
	{
		$root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
	}
}
else 
{
	$root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
}
session_start();
$user_name = $_SESSION['user']['name'] ;
$user_email = $_SESSION['user']['mail'] ;
$user_pantherid = $_SESSION['user']['pid'] ;
include $root.'/authenticate.php';
?>
<html lang="en">
	<!-- Header -->
		<?php 
		include $root.'/links/header.php';
		?>
	<!-- Header -->
<body>

    <div id="wrapper">

        <!-- Navigation -->
         <?php 
        include $root.'/UI/staff/staffmenu.php';
        ?>
        <!-- Navigation -->

		<!-- page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add/Remove  Admin Information</h1>
                </div>
                <div>

                </div>
            </div>
       <div id="adminInfoDiv" class="row">
       <?php 
       include '../Admin/index.php';
       ?>
       </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<!-- FooterLinks -->
<?php 
include $root.'/links/footerLinks.php';
?>
<!-- /#FooterLinks -->
 
<!-- <script> -->
<!-- //     $(document).ready(function() { -->
<!-- //         $('#facultyTables-info').DataTable({ -->
<!-- //             responsive: true -->
<!-- //         }); -->
<!-- //     }); -->
<!--     </script> -->
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
	<script type="text/javascript" src="../Admin/script.js"></script>
</body>

</html>
