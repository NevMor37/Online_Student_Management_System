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
                    <h1 class="page-header">Master Students Information</h1>
                </div>
            </div>
       <div id="studentInfoDiv" class="row">
       		<!-- #Master Student Information -->
					<?php
					
					$html="";
					 
					$html.='<div class="row">';
					$html.='<div class="col-lg-12">';
					$html.='<div class="panel panel-default">';
					$html.='<div class="panel-heading">';
					$html.='Student Information';
					$html.='</div>';
					$html.='<div class="panel-body"  style="overflow:auto">';
					$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-info">';
					
					include($root.'/osms.dbconfig.inc');
					$error_message = "";
					$counter = 0;
					
					$mysqli = new mysqli($hostname,$username, $password,$dbname);
					
					/* check connection */
					if (mysqli_connect_errno()) {
						printf("Connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					
					
					$sql = "SELECT * FROM tbl_excel_info t1,tbl_student_evaluation t2 where t1.PantherId = t2.StudentId and (t1.Program like '%Master%' or t1.Program='Program') order by PantherId;";
					$result = $mysqli->query($sql);
					$row_cnt = $result->num_rows;
					
					
					if ($result->num_rows > 0) {
						// output data of each row
						$count = 0;
						while($row = $result->fetch_assoc()) {
							
							$Status="";
							$count++;
							if($count)
							#echo "username: " . $row["userid"]. " - password : " . $row["password"]. " -email: " . $row["email"]. "<br>";
							$PantherId=$row["PantherId"];
							$FirstName=$row["FirstName"];
							$MiddleName=$row["MiddleName"];
							$LastName=$row["LastName"];
							$AlternativeLastName=$row["AlternativeLastName"];
							$Program=$row["Program"];
							$Term=$row["Term"];
							$Concentration=$row["Concentration"];
							$EMail=$row["EMail"];
							$GREDate=$row["GREDate"];
							$GREVerbalScore=$row["GREVerbalScore"];
							$GREVerbalPercent=$row["GREVerbalPercent"];
							$GREQuantScore=$row["GREQuantScore"];
							$GREQuantPercent=$row["GREQuantPercent"];
							$GREAnalyticalScore=$row["GREAnalyticalScore"];
							$GREAnalyticalPercent=$row["GREAnalyticalPercent"];
							$TOEFLDateofTest=$row["TOEFLDateofTest"];
							$TOEFLTestType=$row["TOEFLTestType"];
							$TOEFLTotal=$row["TOEFLTotal"];
							$TOEFLReading=$row["TOEFLReading"];
							$TOEFLListening=$row["TOEFLListening"];
							$TOEFLSpeaking=$row["TOEFLSpeaking"];
							$TOEFLWriting=$row["TOEFLWriting"];
							$UgGPAOverall=$row["UgGPAOverall"];
							$UgGPAMajor=$row["UgGPAMajor"];
							$GraduateGPA=$row["GraduateGPA"];
							$Faculty1=$row["Faculty1"];
							$Faculty2=$row["Faculty2"];
							$Faculty3=$row["Faculty3"];
							$Race=$row["Race"];
							$Ethnicity=$row["Ethnicity"];
							$Gender=$row["Gender"];
							$CitizenshipCountry=$row["CitizenshipCountry"];
							$CitizenshipStatus=$row["CitizenshipStatus"];
							$CollegeName1=$row["CollegeName1"];
							$DateAttendedFrom1=$row["DateAttendedFrom1"];
							$DateAttendedTo1=$row["DateAttendedTo1"];
							$Major1=$row["Major1"];
							$Degree1=$row["Degree1"];
							$CollegeName2=$row["CollegeName2"];
							$DateAttendedFrom2=$row["DateAttendedFrom2"];
							$DateAttendedTo2=$row["DateAttendedTo2"];
							$Major2=$row["Major2"];
							$Degree2=$row["Degree2"];
							$CollegeName3=$row["CollegeName3"];
							$DateAttendedFrom3=$row["DateAttendedFrom3"];
							$DateAttendedTo3=$row["DateAttendedTo3"];
							$Major3=$row["Major3"];
							$Degree3=$row["Degree3"];
							
							$sql1 = "SELECT  * FROM tbl_student_evaluation where StudentId='{$PantherId}'";
							//echo $sql1;
							$result1 = $mysqli->query($sql1);
							
							if ($result1->num_rows > 0) {
								$Status="Assigned";
							}
							if($count ==1)
							{
								$html.="<thead>";
								$html.="<tr>";
							
								$html .= '<th>'.'PantherID'.'</th>';
								$html .= '<th>'.$FirstName.'</th>';
								// $html .= '<th>'.$MiddleName.'</th>';
								$html .= '<th>'.$LastName.'</th>';
								// $html .= '<th>'.$AlternativeLastName.'</th>';
								$html .= '<th>'.'Program'.'</th>';
								$html .= '<th>'.$Term.'</th>';
								$html .= '<th>'.'Concentration'.'</th>';
								//$html .= '<th>'.$EMail.'</th>';
// 								$html .= '<th>Status</th>';
// 								$html .= '<th>Assign</th>';
								// $html .= '<th>'.$GREDate.'</th>';
								// $html .= '<th>'.$GREVerbalScore.'</th>';
								// $html .= '<th>'.$GREVerbalPercent.'</th>';
								// $html .= '<th>'.$GREQuantScore.'</th>';
								// $html .= '<th>'.$GREQuantPercent.'</th>';
								// $html .= '<th>'.$GREAnalyticalScore.'</th>';
								// $html .= '<th>'.$GREAnalyticalPercent.'</th>';
								// $html .= '<th>'.$TOEFLDateofTest.'</th>';
								// $html .= '<th>'.$TOEFLTestType.'</th>';
								// $html .= '<th>'.$TOEFLTotal.'</th>';
								// $html .= '<th>'.$TOEFLReading.'</th>';
								// $html .= '<th>'.$TOEFLListening.'</th>';
								// $html .= '<th>'.$TOEFLSpeaking.'</th>';
								// $html .= '<th>'.$TOEFLWriting.'</th>';
								// $html .= '<th>'.$UgGPAOverall.'</th>';
								// $html .= '<th>'.$UgGPAMajor.'</th>';
								// $html .= '<th>'.$GraduateGPA.'</th>';
								// $html .= '<th>'.$Faculty1.'</th>';
								// $html .= '<th>'.$Faculty2.'</th>';
								// $html .= '<th>'.$Faculty3.'</th>';
								// $html .= '<th>'.$Race.'</th>';
								// $html .= '<th>'.$Ethnicity.'</th>';
								// $html .= '<th>'.$Gender.'</th>';
								// $html .= '<th>'.$CitizenshipCountry.'</th>';
								// $html .= '<th>'.$CitizenshipStatus.'</th>';
								// $html .= '<th>'.$CollegeName1.'</th>';
								// $html .= '<th>'.$DateAttendedFrom1.'</th>';
								// $html .= '<th>'.$DateAttendedTo1.'</th>';
								// $html .= '<th>'.$Major1.'</th>';
								// $html .= '<th>'.$Degree1.'</th>';
								// $html .= '<th>'.$CollegeName2.'</th>';
								// $html .= '<th>'.$DateAttendedFrom2.'</th>';
								// $html .= '<th>'.$DateAttendedTo2.'</th>';
								// $html .= '<th>'.$Major2.'</th>';
								// $html .= '<th>'.$Degree2.'</th>';
								// $html .= '<th>'.$CollegeName3.'</th>';
								// $html .= '<th>'.$DateAttendedFrom3.'</th>';
								// $html .= '<th>'.$DateAttendedTo3.'</th>';
								// $html .= '<th>'.$Major3.'</th>';
								// $html .= '<th>'.$Degree3.'</th>';
					
								$html.="<//tr>";
								$html.="<//thead>";
								$html.="<tbody>";
							}
							else{
							
								$html.="<tr class='odd gradeX'>";
							
								$html .= '<td><a href="./forms.php?PantherId='.$PantherId.'">00'.$PantherId.'</a></td>';
								$html .= '<td>'.$FirstName.'</td>';
								// $html .= '<td>'.$MiddleName.'</td>';
								$html .= '<td>'.$LastName.'</td>';
								// $html .= '<td>'.$AlternativeLastName.'</td>';
								$html .= '<td>'.$Program.'</td>';
								$html .= '<td>'.$Term.'</td>';
								$html .= '<td>'.$Concentration.'</td>';
								//$html .= '<td>'.$EMail.'</td>';
// 								$html .= '<td>'.$Status.'</td>';
// 								$html .= '<td><a href="./StudentEvaluvation.php?PantherId='.$PantherId.'" class="btn btn-primary"">Assign</a></td>';
								// $html .= '<td>'.$GREDate.'</td>';
								// $html .= '<td>'.$GREVerbalScore.'</td>';
								// $html .= '<td>'.$GREVerbalPercent.'</td>';
								// $html .= '<td>'.$GREQuantScore.'</td>';
								// $html .= '<td>'.$GREQuantPercent.'</td>';
								// $html .= '<td>'.$GREAnalyticalScore.'</td>';
								// $html .= '<td>'.$GREAnalyticalPercent.'</td>';
								// $html .= '<td>'.$TOEFLDateofTest.'</td>';
								// $html .= '<td>'.$TOEFLTestType.'</td>';
								// $html .= '<td>'.$TOEFLTotal.'</td>';
								// $html .= '<td>'.$TOEFLReading.'</td>';
								// $html .= '<td>'.$TOEFLListening.'</td>';
								// $html .= '<td>'.$TOEFLSpeaking.'</td>';
								// $html .= '<td>'.$TOEFLWriting.'</td>';
								// $html .= '<td>'.$UgGPAOverall.'</td>';
								// $html .= '<td>'.$UgGPAMajor.'</td>';
								// $html .= '<td>'.$GraduateGPA.'</td>';
								// $html .= '<td>'.$Faculty1.'</td>';
								// $html .= '<td>'.$Faculty2.'</td>';
								// $html .= '<td>'.$Faculty3.'</td>';
								// $html .= '<td>'.$Race.'</td>';
								// $html .= '<td>'.$Ethnicity.'</td>';
								// $html .= '<td>'.$Gender.'</td>';
								// $html .= '<td>'.$CitizenshipCountry.'</td>';
								// $html .= '<td>'.$CitizenshipStatus.'</td>';
								// $html .= '<td>'.$CollegeName1.'</td>';
								// $html .= '<td>'.$DateAttendedFrom1.'</td>';
								// $html .= '<td>'.$DateAttendedTo1.'</td>';
								// $html .= '<td>'.$Major1.'</td>';
								// $html .= '<td>'.$Degree1.'</td>';
								// $html .= '<td>'.$CollegeName2.'</td>';
								// $html .= '<td>'.$DateAttendedFrom2.'</td>';
								// $html .= '<td>'.$DateAttendedTo2.'</td>';
								// $html .= '<td>'.$Major2.'</td>';
								// $html .= '<td>'.$Degree2.'</td>';
								// $html .= '<td>'.$CollegeName3.'</td>';
								// $html .= '<td>'.$DateAttendedFrom3.'</td>';
								// $html .= '<td>'.$DateAttendedTo3.'</td>';
								// $html .= '<td>'.$Major3.'</td>';
								// $html .= '<td>'.$Degree3.'</td>';
							
								$html .= "</tr>";
							}
							
						}
					} else {
						echo "0 results";
					}
					
					
					$mysqli->close();
					
					
					
					$html.='</tbody>';
					$html.='</table>';
					// $html.='<div class="well">';
					// $html.='<h4>DataTables Usage Information</h4>';
					// $html.='<p>DataTables is a very flexible, advanced tables plugin for jQuery. In SB Admin, we are using a specialized version of DataTables built for Bootstrap 3. We have also customized the table headings to use Font Awesome icons in place of images. For complete documentation on DataTables, visit their website at <a target="_blank" href="https://datatables.net/">https://datatables.net/</a>.</p>';
					// $html.='<a class="btn btn-default btn-lg btn-block" target="_blank" href="https://datatables.net/">View DataTables Documentation</a>';
					// $html.='</div>';
					$html.='</div>';
					$html.='</div>';
					$html.='</div>';
					$html.='</div>';
					
					echo $html;
					?>       			
       		<!-- /#Master Student Information -->
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
    

 <script>
    $(document).ready(function() {
        $('#studentTables-info').DataTable({
            responsive: true
        });
    });
    </script>
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
</body>

</html>
