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
                    <h1 class="page-header">Assign Students to Faculty</h1>
                </div>
            </div>
       <div id="studentInfoDiv" class="row">
       		<!-- #Master Student Information -->
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
					function filesize_formatted($path)
					{
						$size = filesize($path);
						$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
						$power = $size > 0 ? floor(log($size, 1024)) : 0;
						return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
					}
					
					function camelCase($str, array $noStrip = [])
					{
						// non-alpha and non-numeric characters become spaces
						$str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
						$str = trim($str);
						// uppercase the first character of each word
						$str = ucwords($str);
						$str = str_replace(" ", "", $str);
						// 	$str = lcfirst($str);
					
						return $str;
					}
						
					$html="";
					 
					$html.='<div class="row">';
					$html.='<div class="col-lg-12">';
					$html.='<div class="panel panel-default">';
					$html.='<div class="panel-heading">';
					$html.='Not Assigned';
					$html.='</div>';
					
					//faculty Assign Begin
					
					$html.='<div class="row">';
					$html.='<div class="col-lg-12">';
					$html.='<span id="response"></span>';
					$html.='<div id ="facselect" class="panel panel-default text-center" style="visibility: hidden;">';
					$html.='<h5 style="display:inline; margin-right:10px;">Select Faculty: </h5>';
					$html.='<select id="facultyNamedd" name="facultyNamedd">';
					$html.='<option value="-1">--  Faculty Name  --</option>';
						
					$sql12 = "SELECT * FROM tbl_faculty_info where FirstName != 'FirstName'";
					$sql13 = "SELECT * FROM tbl_student_evaluation where StudentId = '{$PantherId}'";
					
					//echo $sql1;
					$result12 = $mysqli->query($sql12);
					$result13 = $mysqli->query($sql13);
					$row_cnt = $result12->num_rows;
					$AssignedFaculty= "-1";
					if ($result13->num_rows > 0) {
						while($row1 = $result13->fetch_assoc()) {
							$AssignedFaculty = $row1["FacultyId"];
						}
					}
					
					if ($result12->num_rows > 0) {
						while($row = $result12->fetch_assoc()) {
							$html.="<option value=".$row['email'].">".$row['FirstName'].' '.$row['LastName']."</option>";
						}
					}
						
						
					
						
					$html.='</select>';
					$html.='<a href="#" style="margin-left: 20px" class="btn btn-primary" id="assignBtn">Assign</a>';
					
					
					$html.='</div>';
					$html.='</div>';
					
					//faculty Assign End
					
					
					$html.='<div class="panel-body"  style="overflow:auto">';
					$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-info">';
					
					
					
					$sql = "SELECT distinct * FROM tbl_excel_info  where PantherId not in (select StudentId from tbl_student_evaluation) order by PantherId;";
					$result = $mysqli->query($sql);
					$row_cnt = $result->num_rows;
					
					
					if ($result->num_rows > 0) {
						// output data of each row
						$count = 0;
						while($row = $result->fetch_assoc()) {
							
							$Status="Not Assigned";
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
                            $linkaddress=$row["linkaddress"];
					//File Access		
						$ApplicationID=$row["ApplicantID"];
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
								$fileSize = filesize_formatted($filenamelocation);
							}
							else 
							{ 
								$filenamelocation = null;
								$FullFileName ='./../../Applications/'.$FileName;
								$fileSize = filesize_formatted($FullFileName);
								$filename = basename($FullFileName);
							}
						}
						else
						{ 
							$filenamelocation = null;
							$FullFileName ='./../../Applications/'.$FileName;
							$fileSize = filesize_formatted($FullFileName);
							$filename = basename($FullFileName);
						} 
					//File Access End
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
// 								$html.='<th>Select</th>';
								$html .= '<th>'.'PantherID'.'</th>';
								//$html .= '<th>'.'Name'.'</th>';
 								$html .= '<th>'.$FirstName.'</th>';
								// $html .= '<th>'.$MiddleName.'</th>';
 								$html .= '<th>'.$LastName.'</th>';
								// $html .= '<th>'.$AlternativeLastName.'</th>';
								$html .= '<th>'.'Program'.'</th>';
								$html .= '<th>'.$Term.'</th>';
// 								$html .= '<th>'.'Concentration'.'</th>';
								//$html .= '<th>'.$EMail.'</th>';
								$html .= '<th>Status</th>';
								$html .= '<th>Assign</th>';
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
								$checkbox= ($Status!='Assigned')?("<div class='checkbox'><label><input type='checkbox' value='".$PantherId."'>&nbsp;</label>"):("<div>");
								$html .= '<td>'.$checkbox.'<a href="./forms.php?PantherId='.$PantherId.'">00'.$PantherId.'</a></div></td>';
// 								$html .= '<td>'.$FirstName." ".$LastName.'</td>';
	// 								if (file_exists($FullFileName)) {
	// 									$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
	// 								} else {
	// 									$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
	// 								}
//							if(file_exists($filenamelocation))
//							{
//								$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
//							}else if(file_exists($FullFileName))
//							{
//								$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
//							}
//							else
//                            {
//								$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
//								$filename = null;
//								$fileSize = null;
//							}
                                if(file_exists($filenamelocation))
                                {
                                    $html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.'</td>';
                                }else if(file_exists($FullFileName))
                                {
                                    $html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.'</a></td>';
                                }
                                else
                                {
                                    $html .= '<td>'.$FirstName.' '.'</td>';
                                    $filename = null;
                                    $fileSize = null;
                                }

                                if(!empty($linkaddress))
                                {
                                    $html .= '<td><a href="'.$linkaddress.'" target="_blank">'.$LastName.'</td>';
                                }
                                else
                                {
                                    $html .= '<td>'.$LastName.'</td>';
                                }

// 								$html .= '<td>'.$FirstName.'</td>';
								// $html .= '<td>'.$MiddleName.'</td>';
// 								$html .= '<td>'.$LastName.'</td>';
								// $html .= '<td>'.$AlternativeLastName.'</td>';
								$html .= '<td>'.$Program.'</td>';
								$html .= '<td>'.$Term.'</td>';
// 								$html .= '<td>'.$Concentration.'</td>';
								//$html .= '<td>'.$EMail.'</td>';
								$html .= '<td>'.$Status.'</td>';
								$html .= ($Status=='Assigned')?( '<td><a href="#" class="btn btn-primary disabled">Assign</a></td>'):('<td><a href="./StudentEvaluvation.php?PantherId='.$PantherId.'" class="btn btn-primary">Assign</a></td>');
// 								$html .= '<td><a href="./StudentEvaluvation.php?PantherId='.$PantherId.'" class="btn btn-primary">Assign</a></td>';
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
					$html.='Assigned Records';
					$html.='</div>';
												
					$html.='<div class="panel-body"  style="overflow:auto">';
					$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-info1">';
						
						
						
					$sql = "SELECT distinct * FROM tbl_excel_info  where PantherId in (select StudentId from tbl_student_evaluation)order by PantherId;";
					$result = $mysqli->query($sql);
					$row_cnt = $result->num_rows;

                    $html.="<thead>";
                    $html.="<tr>";
                    // 								$html.='<th>Select</th>';
                    $html .= '<th>'.'PantherID'.'</th>';
                    //$html .= '<th>'.'Name'.'</th>';
                    $html .= '<th>'.'FirstName'.'</th>';
                    // $html .= '<th>'.$MiddleName.'</th>';
                    $html .= '<th>'.'LastName'.'</th>';
                    // $html .= '<th>'.$AlternativeLastName.'</th>';
                    $html .= '<th>'.'Program'.'</th>';
                    $html .= '<th>'.'Term'.'</th>';
                    // 								$html .= '<th>'.'Concentration'.'</th>';
                    //$html .= '<th>'.$EMail.'</th>';
                    $html .= '<th>Status</th>';
                    $html .= '<th>Assign</th>';
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

					if ($result->num_rows > 0) {
						// output data of each row
						$count = 0;
						while($row = $result->fetch_assoc()) {
								
							$Status="Not Assigned";
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
                                $linkaddress=$row["linkaddress"];

								$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
								$FullFileName = './../../Applications/'.$FileName;
									
								$sql1 = "SELECT  * FROM tbl_student_evaluation where StudentId='{$PantherId}'";
								//echo $sql1;
								$result1 = $mysqli->query($sql1);
									
								if ($result1->num_rows > 0) {
									$Status="Assigned";
								}

										
									$html.="<tr class='odd gradeX'>";
									$checkbox= ($Status!='Assigned')?("<div class='checkbox'><label><input type='checkbox' value='".$PantherId."'>&nbsp;</label>"):("<div>");
									$html .= '<td>'.$checkbox.'<a href="./forms.php?PantherId='.$PantherId.'">00'.$PantherId.'</a></div></td>';
									// 								$html .= '<td>'.$FirstName." ".$LastName.'</td>';
//									if (file_exists($FullFileName)) {
//										$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
//									} else {
//										$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
//									}
                                    if (file_exists($FullFileName))
                                    {
                                        $html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.'</a></td>';
                                    }
                                    else
                                    {
                                        $html .= '<td>'.$FirstName.' '.'</td>';
                                    }

                                    if(!empty($linkaddress))
                                    {
                                        $html .= '<td><a href="'.$linkaddress.'" target="_blank">'.$LastName.'</td>';
                                    }
                                    else
                                    {
                                        $html .= '<td>'.$LastName.'</td>';
                                    }

                                    // 								$html .= '<td>'.$FirstName.'</td>';
									// $html .= '<td>'.$MiddleName.'</td>';
									// 								$html .= '<td>'.$LastName.'</td>';
									// $html .= '<td>'.$AlternativeLastName.'</td>';
									$html .= '<td>'.$Program.'</td>';
									$html .= '<td>'.$Term.'</td>';
									// 								$html .= '<td>'.$Concentration.'</td>';
									//$html .= '<td>'.$EMail.'</td>';
									$html .= '<td>'.$Status.'</td>';
									$html .= ($Status=='Assigned')?( '<td><a href="#" class="btn btn-primary disabled">Assign</a></td>'):('<td><a href="./StudentEvaluvation.php?PantherId='.$PantherId.'" class="btn btn-primary">Assign</a></td>');
									// 								$html .= '<td><a href="./StudentEvaluvation.php?PantherId='.$PantherId.'" class="btn btn-primary">Assign</a></td>';
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
					} else {
						echo "0 results";
					}
						
						
						
					$mysqli->close();
						
					$html.='</tbody>';
					$html.='</table>';
						
						
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
        $('#studentTables-info1').DataTable({
            responsive: true
        });
    });


    $(":checkbox").on('click', function () {
        var checkbox_value = "";
        var count = 0;
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                count += 1;
            	
                checkbox_value += $(this).val() + "|";
            }
        });
        if(count)
        {$("#facselect").attr("style", "visibility: visible");}
        else
        {$("#facselect").attr("style", "visibility: hidden");}
//         alert(checkbox_value);
    });	

    $(function () {
        $('#assignBtn').on('click', function (e) {
        	var count = 0;
        	var checkbox_value = "";
        	$(":checkbox").each(function () {
                var ischecked = $(this).is(":checked");
                if (ischecked) {
                    count += 1;
                	
                    checkbox_value += $(this).val() + ",";
                }
            });

        	var selvalue = $('select[name=facultyNamedd]').val();
          $.ajax({
            type: 'post',
            url: './AssignStudentToFaculty.php',
            data: { StudentId:checkbox_value, FacultyId: selvalue,Batch:true},
            success: function (php_script_response) {
              var responseText = php_script_response;
    		  if (/Successfully/i.test(responseText))
              {	  
    			   
//     			  $('#modal_content').html(responseText);
//     			  $('#myModal1').modal('show');
    			  $("#facselect").attr("style", "visibility: hidden");
    			$("#response").attr("Style", "color: green;");
    			$('#response').html(responseText);
              }
              else
              {
//             	  $('#modal_content').html(responseText);
//     			  $('#modal_content').html('Application is already assigned to the selected faculty for evaluation');
//     			  $('#myModal1').modal('show');
    			$("#response").attr("class", "color: red;");
    			$('#response').html(responseText);
              }
            }    
          });
        });
      });

  
    </script>
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
</body>

</html>
