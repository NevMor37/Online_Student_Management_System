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
include $root.'/links/header.php';
?>
<html lang="en">
<!-- Header -->
<head>
<style> 
input[type=checkbox] {
  display: none;
}
 
input[type=checkbox] + label {
  position: relative;
  //background: url(http://i.stack.imgur.com/ocgp1.jpg) no-repeat;
      background-color: #2f7b1c;
  height: 20px;
  width: 20px;
  display: block;
  border-radius: 50%;
  transition: box-shadow 0.4s, border 0.4s;
  border: solid 1px #FFF;
  box-shadow: 0 0 1px #FFF;/* Soften the jagged edge */
  cursor: pointer;
}
input[type=checkbox]:checked + label { 
  background-color: #da0505;
}
input[type=checkbox] + label:hover{
 background-color: #000000;
}
 
</style>
</head>
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
			<div class="row">
				<div class="col-lg-12">
					<h4>Welcome <?php echo $user_name?></h4>
<!-- 					<h1 class="page-header">Dashboard</h1> -->
                    
										<?php 
										include($root.'/osms.dbconfig.inc');
										
										$error_message = "";
										$counter = 0;
										
										$mysqli = new mysqli($hostname,$username, $password,$dbname);
										
										/* check connection */
										if (mysqli_connect_errno()) {
											printf("Connect failed: %s\n", mysqli_connect_error());
											exit();
										}
										
										$html="";
										
										
										$html.='<div class="row">';
										$html.='<div class="col-lg-12">';
										$html.='<div class="panel panel-default">';
										$html.='<div class="panel-heading">';
										$html.='Foundation Courses';
										$html.='</div>';
										$html.='<div class="panel-body"  style="overflow:auto">';
										$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-completed">';
										$html.="<thead>";
										$html.="<tr>";
                                        $html .= '<th style="font-size: 9px">Term</th>';
                                        $html .= '<th style="font-size: 9px">PantherID</th>';
                                        $html .= '<th style="font-size: 9px">Name</th>';
                                        //$html .= '<th style="font-size: 9px">FirstName</th>';
                                        //$html .= '<th style="font-size: 9px">LastName</th>';
                                        $html .= '<th style="font-size: 9px">Data Structures</th>';
                                        $html .= '<th style="font-size: 9px">Software Engineering</th>';
                                        $html .= '<th style="font-size: 9px">Algorithm Analysis</th>';
                                        $html .= '<th style="font-size: 9px">Operating Systems</th>';
                                        $html .= '<th style="font-size: 9px">Programming Languages</th>';
                                        $html .= '<th style="font-size: 9px">Computer Architecture</th>';
                                        $html .= '<th style="font-size: 9px">Automata</th>';
                                        $html .= '<th style="font-size: 9px">Calculus</th>';
                                        $html .= '<th style="font-size: 9px">Discrete Mathematics</th>';

//                                        $html .= '<th>Term</th>';
//                                        $html .= '<th>PantherID</th>';
//                                        $html .= '<th>FirstName</th>';
//                                        $html .= '<th>LastName</th>';
                                        //                                        $html .= '<th>DS</th>';
                                        //                                        $html .= '<th>SE</th>';
                                        //                                        $html .= '<th>Algorithm</th>';
                                        //                                        $html .= '<th>OS</th>';
                                        //                                        $html .= '<th>PLC</th>';
                                        //                                        $html .= '<th>CA</th>';
                                        //                                        $html .= '<th>Automata</th>';
                                        //                                        $html .= '<th>Calculus</th>';
                                        //                                        $html .= '<th>DM</th>';

                                        $html.="</tr>";
										$html.="</thead>";
										$html.="<tbody>";
// 										$sql = "SELECT * FROM tbl_faculty_info LEFT OUTER JOIN tbl_student_evaluation ON tbl_student_evaluation.FacultyId = tbl_faculty_info.email WHERE tbl_student_evaluation.Status='Complete' ";
// 										$result = $mysqli->query($sql);
										
// 										if ($result->num_rows > 0) {
// 											$count = 0;
// 											while($row = $result->fetch_assoc()) {
// 												$Status = "Pending";
// 												$count++;
// 												$StudentId = $row["StudentId"];
										
												$sql1 = "
                                                        SELECT t5.*,  IFNULL(t6.DS, 0) as DS,IFNULL(t6.OS, 0) as OS,IFNULL(t6.AA, 0) as AA,
                                                        IFNULL(t6.PL, 0) as PL,IFNULL(t6.SE, 0) as SE,IFNULL(t6.CA, 0) as CA,
                                                        IFNULL(t6.Automata, 0) as Automata,IFNULL(t6.Calculus, 0) as Calculus,IFNULL(t6.DM , 0) as DM
                                                        FROM 
                                                                (select t1.PantherId,t1.FirstName,t1.LastName,t1.Term 
                                                                 from tbl_student_evaluation t2, tbl_excel_info t1 
                                                                    where t1.PantherId = t2.StudentId and t2.AdmissionDecision = 'Admit')  as t5
                                                        LEFT JOIN tbl_foundation_courses t6 ON t5.PantherId = t6.StudentId
                                                        order by t5.FirstName
                                                  ";
												
												//$sql1 = "SELECT  * FROM tbl_excel_info where PantherId= ".$StudentId;
												$result1 = $mysqli->query($sql1);
										
												if($result1-> num_rows > 0)
												{
													while($row = $result1->fetch_assoc())
													{
														//$html.="<tr class='odd gradeX'><td>".$row["PantherId"]."</td><td>".$row["FirstName"]."</td><td>".$row["LastName"]."</td><td>".$row["DS"]." </td><td>".$row["SE"]."</td><td>".$row["AA"]."</td><td>".$row["OS"]."</td><td>".$row["PL"]."</td><td>".$row["CA"]."</td><td>".$row["Automata"]."</td><td>".$row["Calculus"]."</td><td>".$row["DM"]."</td></tr>";
														
														
														$html.="<tr class='odd gradeX'>";
                                                        $html.="<td  style='font-size: 12px'>".$row["Term"]."</td>";
 														$html.="<td><p class='btn btn-link'>".$row["PantherId"]."</p></td>";
 														//$html.="<td>".$row["FirstName"]."</td>";
 														//$html.="<td>".$row["LastName"]."</td>";
                                                        $html.="<td>".$row["FirstName"].' '.$row["LastName"]."</td>";
// 														$html.="<td>".$row["DS"]." </td>";
// 														$html.="<td>".$row["SE"]."</td>";
// 														$html.="<td>".$row["AA"]."</td>";
// 														$html.="<td>".$row["OS"]."</td>";
// 														$html.="<td>".$row["PL"]."</td>";
// 														$html.="<td>".$row["CA"]."</td>";
// 														$html.="<td>".$row["Automata"]."</td>";
// 														$html.="<td>".$row["Calculus"]."</td>";
// 														$html.="<td>".$row["DM"]."</td>";
$uncheck = "";
$checked = "checked";
//$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."1'".  (($row["PantherId"] == 1)?($checked):($uncheck))." /> <label for='".$row["PantherId"]."1'></label></td>";
//$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."2'".  (($row["FirstName"] == 1)?($checked):($uncheck))." /> <label for='".$row["PantherId"]."2'></label></td>";
//$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."3'".  (($row["LastName"] == 1)?($checked):($uncheck))." /> <label for='".$row["PantherId"]."3'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."4'".  (($row["DS"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."4'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."5'".  (($row["SE"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."5'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."6'".  (($row["AA"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."6'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."7'".  (($row["OS"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."7'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."8'".  (($row["PL"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."8'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."9'".  (($row["CA"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."9'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."10'".  (($row["Automata"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."10'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."11'".  (($row["Calculus"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."11'></label></td>";
$html.="<td><input type='checkbox' value='None' id='".$row["PantherId"]."12'".  (($row["DM"] == 1)?($checked):($uncheck))." disabled='disabled'/> <label for='".$row["PantherId"]."12'></label></td>";
														
														
														$html.="</tr>";
													}
												}	
												else
												{
													echo "No applications assigned";
												}
										
// 											}
// 										}
										
										$html.='</tbody>';
										$html.='</table>';
										$html.='</div>';
										$html.='</div>';
										$html.='</div>';
										$html.='</div>';
										echo $html;
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
<script type="text/javascript">
$( document ).ready(function() {
	 
	$('#studentTables-completed').DataTable({
        responsive: true,
        iDisplayLength : 100,

    });
	 
	 
});
</script>

<script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script
	src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script
	src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
</html>
