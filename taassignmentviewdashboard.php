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
//include $root.'/authenticate.php';
?>
<html lang="en">
<!-- Header -->
<?php
include $root.'/links/header.php';
?>
<!-- /#Header -->
<body>

<!-- Navigation -->
<?php
include $root.'/UI/staff/staffmenu.php';
?>
<!-- /#Navigation -->

<!-- wrapper -->
<div id="wrapper">


    <!-- page-wrapper -->
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h4>Welcome <?php echo $user_name?></h4>
                <h1 class="page-header">Dashboard</h1>
                <span id="response"></span>
                <?php
                include 'taassignmentview.php';
                ?>
            </div>
            <!-- /.col-lg-12 -->
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
</body>
<script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
<script type="text/javascript">

    $( document ).ready(function() {
        $(document).ready(function() {
            $('#taassignment-view').DataTable({
                responsive: true,
                iDisplayLength : 100,
            });

        });
    });


</script>

</html>
