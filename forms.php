<?php

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
if(isset($_GET['PantherId'])) {
	$patherid = $_GET['PantherId'];
}
else
{
	$patherid = 110;
}
?>
<!DOCTYPE html>
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
		<?php 
		
        include $root.'/UI/staff/ViewSFInfo.php';
		
        ?>
		<!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<!-- FooterLinks -->
<?php 
include $root.'/links/footerLinks.php';
?>
<!-- /#FooterLinks -->

</body>

</html>
