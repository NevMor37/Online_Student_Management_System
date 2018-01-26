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

$sql1 ="SELECT t1.StudentId FROM tbl_student_evaluation t1 left join tbl_checkin t2 on t1.StudentId=t2.PantherId WHERE t1.AdmissionDecision='Admit' and t1.AdmissionDecision is not null and  t2.PantherId is null;";
		
$result1 = $mysqli->query($sql1); 


$html="";
$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Admitted Students- Yet to Check-In';
//$html.= $sql1;
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-AdvisorPending">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>PantherID</th>';
$html .= '<th>First Name</th>';
$html .= '<th>Last Name</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
// $html .= '<th>GRE Total</th>';
// $html .= '<th>TOEFL Total</th>';
// $html .= '<th>Faculty Decision</th>';
// $html .= '<th>GPA</th>';
// $html .= '<th>Faculty</th>';
// if($user_role == "Admin")
	$html .= '<th>Check-In</th>';
// 	else
// 		$html .= '<th>Review</th>';
		$html.="</tr>";
		$html.="</thead>";
		$html.="<tbody>"; 
		if ($result1->num_rows > 0) {
			$count = 0;
			while($row1 = $result1->fetch_assoc()) {
				$StudentId = $row1["StudentId"];
				 
				$sql3 = "SELECT  * FROM tbl_excel_info ex1,tbl_student_evaluation te1 where te1.StudentId=ex1.PantherId and  ex1.PantherId= ".$StudentId;
				$result3 = $mysqli->query($sql3);
				 
				if($result3-> num_rows > 0)
				{
					while($row3 = $result3->fetch_assoc())
					{
						$PantherId = $row3["PantherId"];
						$FirstName=$row3["FirstName"];
						$LastName=$row3["LastName"];
						$Program=$row3["Program"];
						$Term=$row3["Term"];
					
					}

					$html.="<tr class='odd gradeX'>";
					$html .= '<td>00'.$PantherId.'</td>';
					$html .= '<td>'.$FirstName.'</td>';
					$html .= '<td>'.$LastName.'</td>';
					$html .= '<td>'.$Program.'</td>';
					$html .= '<td>'.$Term.'</td>';
// 					$html .= '<td><a href="./#.php?PantherId='.$StudentId.'&Source=AdminDB" class="btn btn-primary"">Check_In</a></td>';
					$html .= "<td><button type='button' class='btn btn-primary' onclick='addRecord(".$StudentId.",\"".$FirstName." ".$LastName."\")'>Check-In</button></td>";
					$html .= "</tr>";
				}
			}
		}

		$html.='</tbody>';
		$html.='</table>';
		$html.='</div>';
		$html.='</div>';
		$html.='</div>';
		$html.='</div>';

		$sql1 ="SELECT t1.StudentId FROM tbl_student_evaluation t1 inner join tbl_checkin t2 on t1.StudentId=t2.PantherId WHERE t1.AdmissionDecision='Admit' and t1.AdmissionDecision is not null";
		
		$result1 = $mysqli->query($sql1);
		
		$html.='<div class="row">';
		$html.='<div class="col-lg-12">';
		$html.='<div class="panel panel-default">';
		$html.='<div class="panel-heading">';
		$html.='Admitted Students- Already Checked-In';
		$html.='</div>';
		$html.='<div class="panel-body"  style="overflow:auto">';
		$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-AdvisorCheckIn">';
		$html.="<thead>";
		$html.="<tr>";
		$html .= '<th>PantherID</th>';
		$html .= '<th>First Name</th>';
		$html .= '<th>Last Name</th>';
		$html .= '<th>Program</th>';
		$html .= '<th>Term</th>';
			$html .= '<th>CheckIn-Date</th>';
			$html .= '<th>Delete</th>';
				$html.="</tr>";
				$html.="</thead>";
				$html.="<tbody>";
		
				if ($result1->num_rows > 0) {
					$count = 0;
					while($row1 = $result1->fetch_assoc()) {
						$StudentId = $row1["StudentId"];
		
						$sql3 = "SELECT  ex1.*,te1.InDate FROM tbl_excel_info ex1,tbl_checkin te1 where te1.PantherId=ex1.PantherId and  ex1.PantherId=".$StudentId;
						$result3 = $mysqli->query($sql3);
		
						if($result3-> num_rows > 0)
						{
							while($row3 = $result3->fetch_assoc())
							{
								$PantherId = $row3["PantherId"];
								$FirstName=$row3["FirstName"];
								$LastName=$row3["LastName"];								
								$Program=$row3["Program"];
								$Term=$row3["Term"];
								$InDate = $row3["InDate"];
								
							}
		
		
							$html.="<tr class='odd gradeX'>";
							$html .= '<td>00'.$PantherId.'</td>';
							$html .= '<td>'.$FirstName.'</td>'; 
							$html .= '<td>'.$LastName.'</td>';
							$html .= '<td>'.$Program.'</td>';
							$html .= '<td>'.$Term.'</td>';
							$html .= '<td>'.$InDate.'</td>';
							$html .= "<td><button type='button' class='btn btn-primary' onclick='deleteRecord(".$StudentId.",\"".$FirstName." ".$LastName."\")'>Delete</button></td>";
								
							$html .= "</tr>";
						}
					}
				}
		
				$html.='</tbody>';
				$html.='</table>';
				$html.='</div>';
				$html.='</div>';
				$html.='</div>';
				$html.='</div>';
		
		
		$mysqli->close();
		
		echo $html;		