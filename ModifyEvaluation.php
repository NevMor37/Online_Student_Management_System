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
	//echo "setting1:".$patherid;
}
else
{
	$patherid = 110;
	//echo "setting2:".$patherid;
}

include('./../../osms.dbconfig.inc');
$error_message = "";

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$Nsql = "select * from tbl_excel_info where PantherId not in(select PantherId from tbl_excel_ext_info ) and PantherId = (select min(PantherId) from tbl_excel_info where PantherId not in(select PantherId from tbl_excel_ext_info ) and PantherId > ".$patherid.")";
$Psql = "select * from tbl_excel_info where PantherId not in(select PantherId from tbl_excel_ext_info ) and PantherId = (select max(PantherId) from tbl_excel_info where PantherId not in(select PantherId from tbl_excel_ext_info ) and PantherId < ".$patherid.")";


//echo $sql2;
$Nresult = $mysqli->query($Nsql);
$Presult = $mysqli->query($Psql);

$NpantherID=$patherid;
$PPantherID = $pantherId;
 
$row_cnt = $Nresult->num_rows;

if ($Nresult->num_rows > 0) {
	// output data of each row

	while($row = $Nresult->fetch_assoc()) {

		$NpantherID=$row["PantherId"];
	}
}
if ($Presult->num_rows > 0) {
	// output data of each row

	while($row = $Presult->fetch_assoc()) {

		$PPantherID=$row["PantherId"];
	}
}
?>
<html lang="en">
	<!-- Header -->
		<?php
		include $root.'/links/header.php';
		include $root.'/links/footerLinks.php';
		?>
		<style>
		input[type=text], select {
			text-align: center;
		}
		</style>
<!-- /#Header -->
<body>

	<!-- wrapper -->
    <div id="wrapper">

        <!-- Navigation -->
        <?php  
        include $root.'/UI/staff/staffmenu.php';
        ?>
        <!-- /#Navigation -->

		<!-- page-wrapper -->
		
		<div id="page-wrapper">
		<div style="float: left; margin-top:20px;" > <a href="./ModifyEvaluation.php?PantherId=<?php echo $PPantherID  ?>" class="btn btn-primary" <?php if($PPantherID==0){echo 'style="visibility : hidden;"';}?>>previous</a></div>
		<div style="float: right; margin-top:20px;" > <a href="./ModifyEvaluation.php?PantherId=<?php echo  $NpantherID ?>" class="btn btn-primary" <?php if($NpantherID==$patherid){echo 'style="visibility : hidden;"';}?>>Next</a></div>
    <button style="float: right; margin-top:20px;" type="button" class="btn btn-default" onclick="printDiv('printableArea')">
      <span class="glyphicon glyphicon-print"></span> Print
    </button>
    
         <div class="row" id="printableArea">
        <?php 
        include $root.'/UI/AppEvaluation.php';
        ?>
                <!-- /.col-lg-12 -->
            </div>
			 <br/>
			<br/>
			
			
			 <br/>
			<br/>
        </div>
        <!-- /#page-wrapper -->

    </div>
<div class="modal fade" id="myModal1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">OSMS - Admin</h4>
      </div>
      <div class="modal-body" id="modal_content">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <!-- /#wrapper -->

<!-- FooterLinks -->
<?php 
include $root.'/links/footerLinks.php';
?>
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

</script>

<!-- /#FooterLinks -->
</body>

</html>
