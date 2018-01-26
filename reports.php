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
if(isset($_POST['term'])) {
	$Term = $_POST['term'];
}
if(isset($_POST['program'])) {
	$Program = $_POST['program'];
}
if(isset($_POST['status'])) {
	$Status = $_POST['status'];
}

// echo $Term."->".$Program."->".$Status;

include($root.'/osms.dbconfig.inc');
 

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$error_message = "";
 

if($Term == '-1' && $Program != '-1' && $Status != '-1')
	$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId where Program LIKE '%".$Program."%' and AdmissionDecision='".$Status."'";	

else if($Term == '-1' && $Program == '-1' && $Status != '-1')
	$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId where AdmissionDecision='".$Status."'";	

else if($Term == '-1' && $Program != '-1' && $Status == '-1')
	$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId where Program LIKE '%".$Program."%'";	

else if($Term != '-1' && $Program == '-1' && $Status == '-1')
	$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId where Term ='".$Term."'";	

else if($Term != '-1' && $Program == '-1' && $Status != '-1')
	$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId where Term ='".$Term."' and AdmissionDecision='".$Status."'";

else if($Term != '-1' && $Program != '-1' && $Status == '-1')
	$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId where Term ='".$Term."' and Program LIKE '%".$Program."%'";

else if($Term == '-1' && $Program == '-1' && $Status == '-1')
	$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId";

else
$sql = "select PantherId, FirstName, LastName, Term, Program, AdmissionDecision from tbl_excel_info inner join tbl_student_evaluation on tbl_excel_info.PantherId = tbl_student_evaluation.StudentId where Term ='".$Term."' and Program LIKE '%".$Program."%' and AdmissionDecision='".$Status."'";

// echo $sql;
$result = $mysqli->query($sql);

if ($result->num_rows > 0)
{
$html="";
 
$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="report">';
$html.="<thead>";
$html.="<tr>";		
$html .= '<th>Panther ID</th>';
$html .= '<th>Student Name</th>';
$html .= '<th>Term</th>';
$html .= '<th>Program</th>';
$html .= '<th>Status</th>';
$html.="<//tr>";
$html.="<//thead>";
$html.="<tbody>";
}
if (!empty($_POST) && $result->num_rows == 0)
{
	$html="<h4> No results found</h4>";
}
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		//echo "entered";
			$PantherId = $row["PantherId"];
			$Name=$row["FirstName"].' '.$row["LastName"];
			$Term_s=$row["Term"];
			$Program_s=$row["Program"];
			$Status_s=$row["AdmissionDecision"];
			
			$html .="<tr class='odd gradeX'>";	
			$html .= '<td>'.$PantherId.'</td>';
			$html .= '<td>'.$Name.'</td>';
			$html .= '<td>'.$Term_s.'</td>';
			$html .= '<td>'.$Program_s.'</td>';
			$html .= '<td>'.$Status_s.'</td>';
			$html .= "</tr>";
		}
		}

$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$mysqli->close();

//echo $html;
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

<div style="width:100%; margin: 0 auto; border: 2px solid #000000; padding: 10px 10px 10px 10px" class="container">
	<h3 style="margin: 0; text-align:center">Student Reports</h3>
<form action="reports.php" method="post">
<div style="width:100%; margin: 0 auto;" class="form-group">
	<label for="selectterm">Select Term:</label>
	<select class="form-control" name="term" id="selectterm">
	<option value="-1" <?=$Term == '-1' ? ' selected="selected"' : '';?>>Select Term</option> 
	
	  <option value="Summer 2017" <?=$Term == 'Summer 2017' ? ' selected="selected"' : '';?>>Summer 2017</option>
	  <option value="Fall 2017" <?=$Term == 'Fall 2017' ? ' selected="selected"' : '';?>>Fall 2017</option>
	  <option value="Spring 2018" <?=$Term == 'Spring 2018' ? ' selected="selected"' : '';?>>Spring 2018</option>
	</select>
	<br />
	<label for="selectprogram">Select Program:</label>
	<select class="form-control" name="program" id="selectprogram">
	<option value="-1">Select Program</option>
	  <option value="MS" <?=$Program == 'MS' ? ' selected="selected"' : '';?>>Computer Science, Master of Science</option>
	  <option value="Phd" <?=$Program == 'Phd' ? ' selected="selected"' : '';?>>Computer Science, Doctor of Philosophy</option>
	</select>
	<br />
	<label for="selectstatus">Select Status:</label>
	<select class="form-control" name="status" id="selectstatus">
	<option value="-1">Select Admission Status</option>
	  <option value="Admit" <?=$Status == 'Admit' ? ' selected="selected"' : '';?>>Admit</option>
	  <option value="Reject" <?=$Status == 'Reject' ? ' selected="selected"' : '';?>>Reject</option>
	  <option value="Defer" <?=$Status == 'Defer' ? ' selected="selected"' : '';?>>Defer</option>
	</select>
</div>
<br />

		<div style="text-align: -webkit-center;">
			 <input type="submit" value="Submit" class="btn btn-secondary" />
			 <div id="response"></div>
		</div>
		<br />
		<div>
		<?= $html ?>
		</div>
</form>
</div>
 <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
<script type="text/javascript">
 
$( document ).ready(function() {
	 $(document).ready(function() {
	        $('#report').DataTable({
	            responsive: true
	        }); 
	    });
});
	    </script>
</body>

</html>