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
$user_role = $_SESSION['user']['role'];
include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql1 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Complete' and AdmissionDecision is null";
$sql2 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Pending'";
$result1 = $mysqli->query($sql1);
$result2 = $mysqli->query($sql2);
//$sql = "SELECT * FROM tbl_faculty_info LEFT OUTER JOIN tbl_student_evaluation ON tbl_student_evaluation.FacultyId = tbl_faculty_info.email WHERE tbl_faculty_info.email='".$user_email."'";
//$result = $mysqli->query($sql);

$html="";
 
$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Pending at Director Of Graduate Studies and Completed by Faculty';
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
$html .= '<th>GRE Total</th>';
$html .= '<th>TOEFL Total</th>';
$html .= '<th>Faculty Decision</th>';
// $html .= '<th>Concentration</th>';
$html .= '<th>GPA</th>';
$html .= '<th>Faculty</th>';
// if($user_email != 'dgunda1@student.gsu.edu')
// if($user_email != 'cao@gsu.edu')
if($user_role == "Admin")
	$html .= '<th>View</th>';
else
	$html .= '<th>Review</th>';
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
			$email=$row3["EMail"];
			$Program=$row3["Program"];
			$Term=$row3["Term"];
			$Concentration=$row3["Concentration"];
			$FacEmail = $row3["FacultyName"];
			$GREVerbalScore=$row3["GREVerbalScore"];
			$GREQuantScore=$row3["GREQuantScore"];
			$TOEFLTotal=$row3["TOEFLTotal"];
			$FacultyD=$row3["Recommendation"];			
			$GRETotal = $GREVerbalScore + $GREQuantScore;
// 			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
// 			$FullFileName = './../../Applications/'.$FileName;
			$gpa = $row3["GraduateGPA"];
			if(!$gpa)
			{
				$gpa=$row3["UgGPAOverall"];
			}
			//File Access
			$ApplicationID=$row3["ApplicantID"];
			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
			$files = null;
			if(is_numeric($ApplicationID))
			{
				$files = glob($root."/Applications/*".$ApplicationID.".pdf");
			
// 				if(count($files) != 0)
// 				{
// 					$filename = basename($files[0]);
// 					$FullFileName = null;
// 					$filenamelocation='./../../Applications/'.$filename;
// 					$fileSize = filesize_formatted($filenamelocation);
// 				}
// 				else
// 				{
// 					$filenamelocation = null;
// 					$FullFileName ='./../../Applications/'.$FileName;
// 					$fileSize = filesize_formatted($FullFileName);
// 					$filename = basename($FullFileName);
// 				}
			}
			else
			{
// 				$filenamelocation = null;
// 				$FullFileName ='./../../Applications/'.$FileName;
// 				$fileSize = filesize_formatted($FullFileName);
// 				$filename = basename($FullFileName);
			}
			//File Access End
		}
		

			$html.="<tr class='odd gradeX'>";	
			$html .= '<td>'.$PantherId.'</td>';
// 			$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
			 //File Access 
							if(file_exists($filenamelocation)){	
								$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
							}else if(file_exists($FullFileName)){
								$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
							}
							else {
								$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
								$filename = null;
								$fileSize = null;
							}
			 //File Access End
			$html .= '<td>'.$LastName.'</td>';
			$html .= '<td>'.$Program.'</td>';
			$html .= '<td>'.$Term.'</td>';
			$html .= '<td>'.$GRETotal.'</td>';
			$html .= '<td>'.$TOEFLTotal.'</td>';
			
			$html .= '<td>'.$FacultyD.'</td>';
// 			$html .= '<td>'.$Concentration.'</td>';
			$html .= '<td>'.$gpa.'</td>';
			$html .= '<td>'.$FacEmail.'</td>';
// 			if($user_email != 'dgunda1@student.gsu.edu')
// 			if($user_email != 'cao@gsu.edu')
if($user_role != "Admin")
				$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$StudentId.'&Source=AdminDB" class="btn btn-primary"">View</a></td>';
			else
				$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$StudentId.'&Source=AdvisorDB" class="btn btn-primary"">Review</a></td>';
			$html .= "</tr>";
		}
}
}
// if($user_email != 'dgunda1@student.gsu.edu')
// // if($user_email != 'cao@gsu.edu')
// if($user_role == "Admin")
// {
$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
// }

$sql12 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Complete' and AdmissionDecision is not null";
// $sql2 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Pending'";
$result12 = $mysqli->query($sql12);
// $result2 = $mysqli->query($sql2);


$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Completed by both Director Of Graduate Studies and Faculty';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-Completed">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
//$html .= '<th>LastName</th>';
$html .= '<th>Program</th>';
// $html .= '<th>Term</th>';
$html .= '<th>GRE Total</th>';
$html .= '<th>TOEFL Total</th>';
$html .= '<th>Faculty Decision</th>';
$html .= '<th>Advisor Decision</th>';
// $html .= '<th>Concentration</th>';
$html .= '<th>Faculty</th>';
// if($user_email != 'dgunda1@student.gsu.edu')
// if($user_email != 'cao@gsu.edu')
if($user_role == "Admin")
	$html .= '<th>View</th>';
	else
		$html .= '<th>Review</th>';
		$html.="</tr>";
		$html.="</thead>";
		$html.="<tbody>";

		if ($result12->num_rows > 0) {
			$count = 0;
			while($row12 = $result12->fetch_assoc()) {
				$StudentId = $row12["StudentId"];
				$sql3 = "SELECT  * FROM tbl_excel_info ex1,tbl_student_evaluation te1 where te1.StudentId=ex1.PantherId and  ex1.PantherId= ".$StudentId;
				$result3 = $mysqli->query($sql3);

				if($result3-> num_rows > 0)
				{
					while($row3 = $result3->fetch_assoc())
					{
						$PantherId = $row3["PantherId"];
						$FirstName=$row3["FirstName"];
						$LastName=$row3["LastName"];
						$email=$row3["EMail"];
						$Program=$row3["Program"];
						$Term=$row3["Term"];
						$Concentration=$row3["Concentration"];
						$FacEmail = $row3["FacultyName"];
						$GREVerbalScore=$row3["GREVerbalScore"];
						$GREQuantScore=$row3["GREQuantScore"];
						$TOEFLTotal=$row3["TOEFLTotal"];
						$FacultyD=$row3["Recommendation"];
						$AdvisorD = $row3["AdmissionDecision"];
						$GRETotal = $GREVerbalScore + $GREQuantScore;
						$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
						$FullFileName = './../../Applications/'.$FileName;
					}

					$html.="<tr class='odd gradeX'>";
					$html .= '<td>'.$PantherId.'</td>';
					// 			$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
					if (file_exists($FullFileName)) {
						$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
					} else {
						$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
					}
					//$html .= '<td>'.$LastName.'</td>';
					$html .= '<td>'.$Program.'</td>';
// 					$html .= '<td>'.$Term.'</td>';
					$html .= '<td>'.$GRETotal.'</td>';
					$html .= '<td>'.$TOEFLTotal.'</td>';
						
					$html .= '<td>'.$FacultyD.'</td>';
					$html .= '<td>'.$AdvisorD.'</td>';
// 					$html .= '<td>'.$Concentration.'</td>';
					$html .= '<td>'.$FacEmail.'</td>';
// 								if($user_email != 'dgunda1@student.gsu.edu')
// 					if($user_email != 'cao@gsu.edu')
// 					if($user_role == "Admin")
						$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$StudentId.'&Source=FinalDB" class="btn btn-primary"">View</a></td>';
// 						else
// 							$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$StudentId.'&Source=AdvisorDB" class="btn btn-primary"">Review</a></td>';
							$html .= "</tr>";
				}
			}
		}
// 			if($user_email != 'dgunda1@student.gsu.edu')
// // 			if($user_email != 'cao@gsu.edu')
// if($user_role == "Admin")
// 			{
				$html.='</tbody>';
				$html.='</table>';
				$html.='</div>';
				$html.='</div>';
				$html.='</div>';
				$html.='</div>';
// 			}
				
				
// if($user_email != 'dgunda1@student.gsu.edu')
// // 			if($user_email != 'cao@gsu.edu')
// if($user_role == "Admin")
// 				{			

$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Pending at Faculty';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-info">';
$html.="<thead>";
$html.="<tr>";		
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
//$html .= '<th>LastName</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
$html .= '<th>Assigned On</th>';
$html .= '<th>Reminded On</th>';
$html .= '<th>Faculty</th>';
if($user_role != "Admin")
$html .= '<th>Remind</th>';
$html .= '<th>View</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";

if ($result2->num_rows > 0) {
$count = 0;
while($row2 = $result2->fetch_assoc()) {	
		$StudentId = $row2["StudentId"];
		$sql4 = "SELECT  * FROM tbl_excel_info  ex1,tbl_student_evaluation te1 where te1.StudentId=ex1.PantherId and  ex1. PantherId= ".$StudentId;
		$result4 = $mysqli->query($sql4);
		
		if($result4-> num_rows > 0)
		{
		while($row4 = $result4->fetch_assoc()) 
		{
			$PantherId = $row4["PantherId"];
			$FirstName=$row4["FirstName"];
			$LastName=$row4["LastName"];
			$email=$row4["EMail"];
			$Program=$row4["Program"];
			$Term=$row4["Term"];
			$Concentration=$row4["Concentration"];
			$FacEmail = $row4["FacultyName"];
			$assignDate = $row4["AssignDate"];
			$remaindedOn = $row4["RemindDate"];
			
			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
			$FullFileName = './../../Applications/'.$FileName;
			
		}

			$html.="<tr class='odd gradeX'>";	
			$html .= '<td>'.$PantherId.'</td>';
// 			$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
			if (file_exists($FullFileName)) {
				$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
			} else {
				$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
			}
			//$html .= '<td>'.$LastName.'</td>';
			$html .= '<td>'.$Program.'</td>';
			$html .= '<td>'.$Term.'</td>';
			
// 			$html .= '<td>'.$Concentration.'</td>';
			$html .= '<td>'.$assignDate.'</td>';
			$html .= '<td>'.$remaindedOn.'</td>';
			$html .= '<td>'.$FacEmail.'</td>';
			if($user_role != "Admin")
			$html .= '<td><a href="#" id="'.$PantherId.'" class="btn btn-primary" onclick="remindFaculty(this.id);">Remind</a></td>';
// 			else
// 			$html .= '<td><a href="#" id="'.$PantherId.'" class="btn btn-primary" onclick="remindFaculty(this.id);">Remind</a></td>';
			$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$StudentId.'&Source=FinalDB" class="btn btn-primary"">View</a></td>';
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
// }
$mysqli->close();

echo $html;
?>

