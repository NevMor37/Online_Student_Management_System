<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include $root.'/OSMS/authenticate.php';
?>
<html lang="en">
	<!-- Header -->
		<?php
		include $root.'/OSMS/links/header.php';
		?>
	<!-- /#Header -->
<body>

	<!-- wrapper -->
    <div id="wrapper">

        <!-- Navigation -->
        <?php  
        include $root.'/OSMS/UI/staff/staffmenu.php';
        ?>
        <!-- /#Navigation -->

		<!-- page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
       
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<!-- FooterLinks -->
<?php 
include $root.'/OSMS/links/footerLinks.php';
?>
<!-- /#FooterLinks -->
</body>

</html>
