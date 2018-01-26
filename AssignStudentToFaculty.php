<?php
function sendEmail($name, $student_id,$mysqli,$multi = false) {
	 
	// $sql = "Select c.course, c.title, ta.name As ta_name, ta.email ta_email, tr.name as ins_name, tr.email As ins_email
			// From Courses c
				// Left Join Instructors tr On c.instructor_name = tr.name												
				// Inner Join CourseTAs ct On c.id = ct.course_id
				// Left Join TAs ta On ct.ta_name = ta.name
			// Where c.instructor_name = '{$name}'
			// Order By course Asc;";
	if($multi) {
		$sql1 = "SELECT * FROM tbl_excel_info WHERE PantherId in (".substr($student_id,0,-1).")";
		 
	}
	else {
		$sql1 = "SELECT * FROM tbl_excel_info WHERE PantherId ='{$student_id}'";
	}
	$sql2 = "SELECT FirstName FROM tbl_faculty_info WHERE email='{$name}'";
	
	
// 	echo $sql1;
// 	echo $sql2;
	 $results1 = $mysqli->query($sql2);
	 $results2 = $mysqli->query($sql1);
	

	  if ($results1->num_rows > 0) {
	  while($row2 = $results1->fetch_assoc()) {
	  	$student_name = "";
			  while($row1 = $results2->fetch_assoc()){
			  	
			  	$student_name = $student_name." ".$row1['FirstName'];
			  	$student_name = $student_name." ".$row1['LastName'];
			  	$student_name = $student_name."\r\n\t\t";
			  }
			  $Faculty_name = $row2['FirstName'];
				$subject = "OGMS Testing Please ignore incase if you receive this";
			  $headers = 'From: tdudley@gsu.edu' . "\r\n" .
			  'Reply-To: tdudley@gsu.edu' . "\r\n" .
			  'X-Mailer: PHP/' . phpversion();
			  
		  $message = "Dear Prof. {$Faculty_name}, \r\n\r\nThe Spring 2018 graduate admission cycle is underway! You have been assigned the following applications for evaluation for the term Spring 2018:\r\n\nStudent Names: {$student_name} ";
		 			
		  $message = $message . "\r\n Please login to OGMS (grid.cs.gsu.edu/~ogms/) using your Campus ID and password to submit your evaluation for the Assigned applications appearing on your Dashboard.\r\n\r Kindly submit your evaluation within one week to expedite the admission process.
								  \r\n\r GRE Verbal & Quantitative Percentiles : 35% or better
								   \r\n\r TOEFL Total Score = 80 or better
									\r\n\r Check transcript for (10) Foundation Courses; record grade for each course
									 \r\n\r Overall GPA = 3.0 or better
								  	  \r\n\r Please use the following credentials to access the Application material by clicking on the 'Applicant Name' hyperlink on your dashboard.\nUsername: ogms\nPassword: spring2017 
								  	   \r\n\r https://screenshots.firefox.com/r9Bmgkws9vAmOnjG/gradapply.gsu.edu
								  	    \r\n\r\nThank you,\r\n\r\nDirector of Graduate Studies";
		  //$message = wordwrap($message, 70, "\r\n");
			
		   if (strlen($name) > 0) {
		   	//$name = "dgunda1@student.gsu.edu";
			   $result = mail($name, $subject, $message, $headers);
			   echo $result ? "Successfully sent notification email to {$Faculty_name} at {$name}"
				   : "Failed to send notification email to {$Faculty_name} at {$name}";
		   } else {
			   echo "Email not sent. Faculty {$Faculty_name} doesn't have an email on file.";
		   }
	  }
	  }	else {
		  echo "Email not sent. There is no applicant assigned to Faculty {$Faculty_name} yet";
	  }
}

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
$Batch  = false;

if(isset($_POST['StudentId'])){$StudentId=$_POST['StudentId'];}
if(isset($_POST['FacultyId'])){$FacultyId=$_POST['FacultyId'];}
if(isset($_POST['Batch'])){if($_POST['FacultyId'] == true){$Batch= true;}}


include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$date = date("Y-m-d");
$sql4 = "SELECT * FROM tbl_faculty_info WHERE email='".$FacultyId."'";
$result4 = $mysqli->query($sql4);
if($result4->num_rows > 0)
{
  while($row = $result4->fetch_assoc()) {
	 $faculty = $row["FirstName"]." ".$row["LastName"];
  }
}

if($Batch){
$StuID = (explode(",",$StudentId));
$StCount = 0;
$test = "";
$arrlength = count($StuID);
for($x = 0; $x < $arrlength-1; $x++) {
// 	echo "Key=" . $x . ", Value=" . $x_value;
	
	$sql = "INSERT INTO tbl_student_evaluation (`StudentId`, `FacultyId`, `Status`, `FacultyName`,`AssignDate` )";
	$sql = $sql."VALUES (".($StuID[$x]).",'".($FacultyId)."', 'Pending', '".$faculty."','".$date."')";
	$test = $test." ".$StuID[$x];
	if($mysqli->query($sql) == true)
	{
		$StCount++;
	}
	else
	{
		echo "Error: ".$sql."<br>".$mysqli->error;
	}
}
		$StCount++;
	if($StCount == count($StuID))
	{
		sendEmail($FacultyId, $StudentId,$mysqli,true);
		
	}
}
else{
$sql = "INSERT INTO tbl_student_evaluation (`StudentId`, `FacultyId`, `Status`, `FacultyName`,`AssignDate` )";
$sql = $sql."VALUES (".($StudentId).",'".($FacultyId)."', 'Pending', '".$faculty."','".$date."')";

	if($mysqli->query($sql) == true)
	{				
		sendEmail($FacultyId, $StudentId,$mysqli);
		$sql5 = "UPDATE tbl_student_evaluation SET AssignDate='".$date."' WHERE StudentId ='".$StudentId."'";
		$result5 = $mysqli->query($sql5);
		//echo "Single ..Email";
	}
	else 
	{
		echo "Error: ".$sql."<br>".$mysqli->error;
	}
}
?>

