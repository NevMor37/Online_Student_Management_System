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
// $user_email = "ywu28@gsu.edu";
$html="";
 
$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Pending Applications';
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
$html .= '<th>Concentration</th>';
//$html .= '<th>Email</th>';
$html .= '<th>Status</th>';
$html .= '<th>Evaluate</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";
include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
// $user_email = "cao@gsu.edu";
// $sql = "SELECT * FROM tbl_faculty_info LEFT OUTER JOIN tbl_student_evaluation ON tbl_student_evaluation.FacultyId = tbl_faculty_info.email WHERE tbl_student_evaluation.Status='Pending' and tbl_faculty_info.email='".$user_email."'";
$sql = "select * from tbl_faculty_info t1, tbl_student_evaluation t2, tbl_excel_info t3 where t1.email = t2.FacultyId and t2.Status='Pending' and t2.StudentId = t3.PantherId";
$result = $mysqli->query($sql);
// echo $sql;
if ($result->num_rows > 0) {
$count = 0;
while($row = $result->fetch_assoc()) {	
		$Status = "Pending";
		$count++;
		$StudentId = $row["StudentId"];
		$ApplicationID=$row["ApplicantID"];
// 		$sql1 = "SELECT  * FROM tbl_excel_info where PantherId= ".$StudentId;
// 		$result1 = $mysqli->query($sql1);
		
// 		if($result1-> num_rows > 0)
// 		{
// 		while($row1 = $result1->fetch_assoc()) 
// 		{
			$PantherId = $row["PantherId"];
			$FirstName=$row["FirstName"];
			$LastName=$row["LastName"];
			$email=$row["EMail"];
			$Program=$row["Program"];
			$Term=$row["Term"];
			$Concentration=$row["Concentration"];
    		$linkaddress = $row["linkaddress"];
    		//echo '$linkaddress:';
    		//echo $linkaddress;
// 			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
// 			$FullFileName = './../../Applications/'.$FileName;
			//File Access
			
			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
			$files = null;
			try{
				
			if(is_numeric($ApplicationID))
			{
				$files = glob($root."/Applications/*".$ApplicationID.".pdf");
				if(count($files) != 0)
				{
					try{
					$filename = basename($files[0]);
					$FullFileName = null;
					$filenamelocation='./../../Applications/'.$filename;
					
					}
					catch (Exception $e1)
					{
						echo 'Message: ' .$e1->getMessage();
					}
				}
				else
				{
					$filenamelocation = null;
					$FullFileName ='./../../Applications/'.$FileName;
					$filename = basename($FullFileName);
				}
			}
			else
			{
				$filenamelocation = null;
				$FullFileName ='./../../Applications/'.$FileName;
				$filename = basename($FullFileName);
			}
			}
			catch (Exception $e)
			{
				echo 'Message: ' .$e->getMessage();
			}
			//File Access End
// 		}

// 		$sql2 = "SELECT  * FROM tbl_student_evaluation where StudentId= '{$StudentId}'";
// 		$result2 = $mysqli->query($sql2);
		
// 		if($result2-> num_rows > 0)
// 		{
// 			while($row2 = $result2->fetch_assoc()) 
				$Status = $row["Status"];
// 		}
			$html.="<tr class='odd gradeX'>";	
			$html .= '<td>'.$PantherId.'</td>';
			
// 			if (file_exists($FullFileName)) {
// 				$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
// 			} else {
// 				$html .= '<td><a href="#">'.$FirstName.' '.$LastName.'</a></td>';
// 			}
			//File Access
//			if(file_exists($filenamelocation)){
//				$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
//			}else if(file_exists($FullFileName)){
//				$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
//			}
//			else {
//				$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
//				$filename = null;
//				$fileSize = null;
//			}
			//File Access End

			if (empty($linkaddress))
			{
				$html .= '<td>' .$FirstName.' '.$LastName. '</td>';
			}
			else
			{

				$html .= '<td><a href="' . $linkaddress . '" target="_blank">' .$FirstName.' '.$LastName.'</td>';
			}
			//$html .= '<td><a href="./../../Applications/AlamMustafa.pdf">'.$FirstName.' '.$LastName.'</a></td>';
			//$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
			//$html .= '<td>'.$LastName.'</td>';
			$html .= '<td>'.$Program.'</td>';
			$html .= '<td>'.$Term.'</td>';
			$html .= '<td>'.$Concentration.'</td>';
			//$html .= '<td>'.$email.'</td>';
			$html .= '<td>'.$Status.'</td>';
			if($Status == 'Complete')
				$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$PantherId.'&Source=AdminDB" class="btn btn-primary" disabled>Evaluate</a></td>';
			else
				$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$PantherId.'&Source=AdminDB" class="btn btn-primary">Evaluate</a></td>';
			$html .= "</tr>";
		}
// 	}
}
else
{
    echo "No applications assigned";
}
$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';

$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Completed Applications';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-completed">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
//$html .= '<th>LastName</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
$html .= '<th>Concentration</th>';
//$html .= '<th>Email</th>';
$html .= '<th>Status</th>';
$html .= '<th>Evaluate</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";
$sql = "SELECT * FROM tbl_faculty_info LEFT OUTER JOIN tbl_student_evaluation ON tbl_student_evaluation.FacultyId = tbl_faculty_info.email WHERE tbl_student_evaluation.Status='Complete' ";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
	$count = 0;
	while($row = $result->fetch_assoc()) {
		$Status = "Pending";
		$count++;
		$StudentId = $row["StudentId"];

		$sql1 = "SELECT  * FROM tbl_excel_info where PantherId= ".$StudentId;
		$result1 = $mysqli->query($sql1);

		if($result1-> num_rows > 0)
		{
			while($row1 = $result1->fetch_assoc())
			{
				$PantherId = $row1["PantherId"];
				$FirstName=$row1["FirstName"];
				$LastName=$row1["LastName"];
				$email=$row1["EMail"];
				$Program=$row1["Program"];
				$Term=$row1["Term"];
				$Concentration=$row1["Concentration"];
				//File Access
			$ApplicationID=$row1["ApplicantID"];
			$linkaddress = $row["linkaddress"];
			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
			$files = null;
			if(is_numeric($ApplicationID))
			{
				$files = glob($root."/Applications/*".$ApplicationID.".pdf");
					
				if(count($files) != 0)
				{
					$filename = basename($files[0]);
					$FullFileName = null;
					$filenamelocation='./../../Applications/'.$filename;
						
				}
				else
				{
					$filenamelocation = null;
					$FullFileName ='./../../Applications/'.$FileName;
						
					$filename = basename($FullFileName);
				}
			}
			else
			{
				$filenamelocation = null;
				$FullFileName ='./../../Applications/'.$FileName;
			
				$filename = basename($FullFileName);
			}
			//File Access End
			}

			$sql2 = "SELECT  * FROM tbl_student_evaluation where StudentId= '{$StudentId}'";
			$result2 = $mysqli->query($sql2);

			if($result2-> num_rows > 0)
			{
				while($row2 = $result2->fetch_assoc())
					$Status = $row2["Status"];
			}
			$html.="<tr class='odd gradeX'>";
			$html .= '<td>'.$PantherId.'</td>';
				
//		if(file_exists($filenamelocation)){
//								$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
//							}else if(file_exists($FullFileName)){
//								$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
//							}
//							else {
//								$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
//								$filename = null;
//								$fileSize = null;
//							}

            if (empty($linkaddress))
            {
                $html .= '<td>' .$FirstName.' '.$LastName. '</td>';
            }
            else
            {

                $html .= '<td><a href="' . $linkaddress . '" target="_blank">' .$FirstName.' '.$LastName.'</td>';
            }
			//$html .= '<td><a href="./../../Applications/AlamMustafa.pdf">'.$FirstName.' '.$LastName.'</a></td>';
			//$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
			//$html .= '<td>'.$LastName.'</td>';
			$html .= '<td>'.$Program.'</td>';
			$html .= '<td>'.$Term.'</td>';
			$html .= '<td>'.$Concentration.'</td>';
			//$html .= '<td>'.$email.'</td>';
			$html .= '<td>'.$Status.'</td>';
			if($Status == 'Complete')
				$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$PantherId.'&Source=AdminDB" class="btn btn-primary" >View</a></td>';
				else
					$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$PantherId.'&Source=AdminDB" class="btn btn-primary">Evaluate</a></td>';
					$html .= "</tr>";
		}
	}
}
else
{
	echo "No applications assigned";
}



$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';



//completed Applications


$sql12 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Complete' and AdmissionDecision is not null";
$result12 = $mysqli->query($sql12);


$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Admitted PHD Students';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-Completed123">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
$html .= '<th>Program</th>';
$html .= '<th>GRE Total</th>';
$html .= '<th>TOEFL Total</th>';
$html .= '<th>Faculty Decision</th>';
$html .= '<th>Advisor Decision</th>';
$html .= '<th>Faculty</th>';
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
				$skip = false;
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
						if($Program == "MS")
						{
							$skip = true;
							continue;
						}
						$Term=$row3["Term"];
						$Concentration=$row3["Concentration"];
						$FacEmail = $row3["FacultyName"];
						$GREVerbalScore=$row3["GREVerbalScore"];
						$GREQuantScore=$row3["GREQuantScore"];
						$TOEFLTotal=$row3["TOEFLTotal"];
						$FacultyD=$row3["Recommendation"];
						$AdvisorD = $row3["AdmissionDecision"];
						$GRETotal = $GREVerbalScore + $GREQuantScore;
						//File Access
						$ApplicationID=$row3["ApplicantID"];
						$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
						$files = null;
						if(is_numeric($ApplicationID))
						{
							$files = glob($root."/Applications/*".$ApplicationID.".pdf");
								
							if(count($files) != 0)
							{
								$filename = basename($files[0]);
								$FullFileName = null;
								$filenamelocation='./../../Applications/'.$filename;
									
							}
							else
							{
								$filenamelocation = null;
								$FullFileName ='./../../Applications/'.$FileName;
									
								$filename = basename($FullFileName);
							}
						}
						else
						{
							$filenamelocation = null;
							$FullFileName ='./../../Applications/'.$FileName;

							$filename = basename($FullFileName);
						}
						//File Access End
					}
					if($skip)
						continue;
					$html.="<tr class='odd gradeX'>";
					$html .= '<td>'.$PantherId.'</td>';
                    $linkaddress = $row3["linkaddress"];
//					if(file_exists($filenamelocation)){
//						$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
//					}else if(file_exists($FullFileName)){
//						$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
//					}
//					else {
//						$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
//						$filename = null;
//						$fileSize = null;
//					}
                    if (empty($linkaddress))
                    {
                        $html .= '<td>' .$FirstName.' '.$LastName. '</td>';
                    }
                    else
                    {

                        $html .= '<td><a href="' . $linkaddress . '" target="_blank">' .$FirstName.' '.$LastName.'</td>';
                    }
					$html .= '<td>'.$Program.'</td>';
					$html .= '<td>'.$GRETotal.'</td>';
					$html .= '<td>'.$TOEFLTotal.'</td>';

					$html .= '<td>'.$FacultyD.'</td>';
					$html .= '<td>'.$AdvisorD.'</td>';
					$html .= '<td>'.$FacEmail.'</td>';

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
		

		$mysqli->close();
		
echo $html;
?>

