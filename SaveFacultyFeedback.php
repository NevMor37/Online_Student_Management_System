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


if(isset($_POST['ds'])){$DsGrade=$_POST['ds'];}
if(isset($_POST['se'])){$SeGrade=$_POST['se'];}
if(isset($_POST['automata'])){$AutomataGrade=$_POST['automata'];}
if(isset($_POST['algo'])){$AlgoGrade=$_POST['algo'];}
if(isset($_POST['os'])){$OsGrade=$_POST['os'];}
if(isset($_POST['proLan'])){$PLgrade=$_POST['proLan'];}
if(isset($_POST['ca'])){$CaGrade=$_POST['ca'];}
if(isset($_POST['calculus'])){$CalculusGrade=$_POST['calculus'];}
if(isset($_POST['discrete'])){$DiscreteGrade=$_POST['discrete'];}
if(isset($_POST['other'])){$OtherGrade=$_POST['other'];}

if(isset($_POST['workExp'])){$WorkExp=$_POST['workExp'];}
if(isset($_POST['researchExp'])){$ResearchExp=$_POST['researchExp'];}

if(isset($_POST['studentNumber1'])){
	$StudentNumber=$_POST['studentNumber1'];
	$StudentNumber = substr($StudentNumber, 2);
	}
if(isset($_POST['lor1'])){$LOR1=$_POST['lor1'];}
if(isset($_POST['lor2'])){$LOR2=$_POST['lor2'];}
if(isset($_POST['lor3'])){$LOR3=$_POST['lor3'];}
if(isset($_POST['sop'])){$SOP=$_POST['sop'];}
if(isset($_POST['admit'])){$Admit=$_POST['admit'];}
if(isset($_POST['aid'])){$Aid=$_POST['aid'];}
if(isset($_POST['aaid'])){$AAid=$_POST['aaid'];}
if(isset($_POST['comments'])){$Comments=$_POST['comments'];}
if(isset($_POST['advisorcomments'])){$AdvisorComments=$_POST['advisorcomments'];}
if(isset($_POST['advdec'])){$AdvisorDecision=$_POST['advdec'];}
if(isset($_POST['facultyName'])){$FacultyName=$_POST['facultyName'];}
if(isset($_POST['date'])){$Date=$_POST['date'];}
if(isset($_POST['foundationCourses']))
{
	$DS = 0; $SE = 0; $AA = 0; $OS = 0; $PL = 0; $CA = 0; $Automata = 0; $DM = 0; $Calculus = 0;
	foreach ($_POST['foundationCourses'] as $selectedOption)
	{
		if($selectedOption == 'ds')
			$DS = 1; 
		if($selectedOption == 'se')
			$SE = 1;
		if($selectedOption == 'aa')
			$AA = 1; 
		if($selectedOption == 'os')
			$OS = 1; 
		if($selectedOption == 'pl')
			$PL = 1; 
		if($selectedOption == 'ca')
			$CA = 1; 
		if($selectedOption == 'automata')
			$Automata = 1; 
		if($selectedOption == 'dm')
			$DM = 1; 
		if($selectedOption == 'calculus')
			$Calculus = 1; 		
	}
    $searchsql  = "select * from tbl_foundation_courses where StudentId=".$StudentNumber;
    $searchresult=$mysqli->query($searchsql);
    if ($searchresult->num_rows > 0)
	{
        $sqlfc = "update tbl_foundation_courses 
        			set DS='$DS',SE='$SE',AA='$AA',OS='$OS',PL='$PL',CA='$CA',Automata='$Automata',DM='$DM',
        			Calculus='$Calculus'
        			where StudentId = '$StudentNumber'
        			";

    }
    else {
        $sqlfc = "INSERT INTO tbl_foundation_courses (StudentId, DS, SE, AA, OS, PL, CA, Automata, DM, Calculus)";
        $sqlfc = $sqlfc . "VALUES (" . ($StudentNumber) . "," . $DS . "," . $SE . "," . $AA . "," . $OS . "," . $PL . "," . $CA . "," . $Automata . "," . $DM . "," . $Calculus . ")";
    }
    echo $sqlfc;
    if($mysqli->query($sqlfc) == true)
    {
        echo "Data Updated";
    }
    else
    {
        echo "Update Error: ".$sqlfc."<br>".$mysqli->error;
    }

}

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$date = date("Y-m-d H:i:s");
$LastUpdatedOn=$date;

$searchsql  = "select * from tbl_excel_ext_info where PantherID=".$StudentNumber;
$searchresult=$mysqli->query($searchsql);
if ($searchresult->num_rows > 0)
{
    $sql = "UPDATE  tbl_excel_ext_info SET ";
    $sql = $sql.($DsGrade==''?'`ds`=NULL':'`ds`='."'".$DsGrade."'");
    $sql = $sql.($SeGrade==''?',`se`=NULL':',`se`='."'".$SeGrade."'");
    $sql = $sql.($AutomataGrade==''?',`automata`=NULL':',`automata`='."'".$AutomataGrade."'");
    $sql = $sql.($AlgoGrade==''?',`algo`=NULL':',`algo`='."'".$AlgoGrade."'");
    $sql = $sql.($OsGrade==''?',`os`=NULL':',`os`='."'".$OsGrade."'");
    $sql = $sql.($PLgrade==''?',`proLan`=NULL':',`proLan`='."'".$PLgrade."'");
    $sql = $sql.($CaGrade==''?',`ca`=NULL':',`ca`='."'".$CaGrade."'");
    $sql = $sql.($CalculusGrade==''?',`calculus`=NULL':',`calculus`='."'".$CalculusGrade."'");
    $sql = $sql.($DiscreteGrade==''?',`discrete`=NULL':',`discrete`='."'".$DiscreteGrade."'");
    $sql = $sql.($OtherGrade==''?',`other`=NULL':',`other`='."'".$OtherGrade."'");
    $sql = $sql.($LastUpdatedOn==''?',`LastUpdatedOn`=NULL':',`LastUpdatedOn`='."'".$LastUpdatedOn."'");
    $sql = $sql.' WHERE `PantherID`='.$StudentNumber;
echo $sql;
    if($mysqli->query($sql) == true)
    {
        echo "Data Updated";
    }
    else
    {
        echo "Update Error: ".$sql."<br>".$mysqli->error;
    }
}
else
{
    $sql = "INSERT INTO  tbl_excel_ext_info (`PantherID`, `WExp`, `RExp`, `ds`, `se`, `automata`, `algo`, `os`, 
										`proLan`, `ca`, `calculus`, `discrete`, `other`,`other1`,`other2`,
										`other3`,`other4`,`LastUpdatedOn`)";
    $sql = $sql . "VALUES (" . ($StudentNumber != '' ? $StudentNumber : NULL) . ",'" . ($WorkExp != '' ? $WorkExp : NULL) . "', '" . ($ResearchExp != '' ? $ResearchExp : NULL) . "','" . ($DsGrade != '' ? $DsGrade : NULL) . "','" . ($SeGrade != '' ? $SeGrade : NULL) . "','" . ($AutomataGrade != '' ? $AutomataGrade : NULL) . "','" . ($AlgoGrade != '' ? $AlgoGrade : NULL) . "','" . ($OsGrade != '' ? $OsGrade : NULL) . "','" . ($PLgrade != '' ? $PLgrade : NULL) . "','" . ($CaGrade != '' ? $CaGrade : NULL) . "','" . ($CalculusGrade != '' ? $CalculusGrade : NULL) . "','" . ($DiscreteGrade != '' ? $DiscreteGrade : NULL) . "', '" . ($OtherGrade != '' ? $OtherGrade : NULL) . "','" . ($OtherGrade1 != '' ? $OtherGrade1 : NULL) . "','" . ($OtherGrade2 != '' ? $OtherGrade2 : NULL) . "','" . ($OtherGrade3 != '' ? $OtherGrade3 : NULL) . "','" . ($OtherGrade4 != '' ? $OtherGrade4 : NULL) . "','" . ($LastUpdatedOn != '' ? $LastUpdatedOn : NULL) . "')";
echo $sql;
    if($mysqli->query($sql1) == false)
    {
        echo "Insert Error: ".$sql1."<br>".$mysqli->error;
    }

    if($mysqli->query($sql) == true)
    {	echo "Data saved";
    }

}

$AdvisorComments= $mysqli->escape_string($AdvisorComments);
$Comments = $mysqli->escape_string($Comments);
// 	if($user_email != 'dgunda1@student.gsu.edu')
// 	if($user_email != 'cao@gsu.edu')
	if($user_role != "Admin" and $user_role != "Staff")
		$sql = "UPDATE tbl_student_evaluation SET LOR1='".$LOR1."',LOR2='".$LOR2."',LOR3='".$LOR3."',SOP='".$SOP."',Recommendation='".$Admit."',FinAid='".$Aid."',AFinAid='".$AAid."',Comments='".$Comments."',FacultyName='".$FacultyName."',Date='".$Date."' WHERE StudentId='".$StudentNumber."' AND FacultyId='".$user_email."'";
	else
	{
		$sql = "UPDATE tbl_student_evaluation SET LOR1='".$LOR1."',LOR2='".$LOR2."',LOR3='".$LOR3."',SOP='".$SOP."',Recommendation='".$Admit."',FinAid='".$Aid."',AFinAid='".$AAid."',Comments='".$Comments."', AdvisorComments='".$AdvisorComments."', FacultyName='".$FacultyName."',Date='".$Date."', AdmissionDecision='".$AdvisorDecision."' WHERE StudentId='".$StudentNumber."'";
		//$res_fc = $mysqli->query($sql);
	}
	echo $sql;
	if($mysqli->query($sql) == true)
	{
		echo "Feedback Submitted";
	}
	else 
	{
		echo "Error: ".$sql."<br>".$mysqli->error;
	}
	if($user_role != "Admin" and $user_role != "Staff") {
        $sql1 = "SELECT * FROM tbl_student_evaluation WHERE StudentId='{$StudentNumber}' AND FacultyId='{$user_email}'";
    }
    else
	{
        $sql1 = "SELECT * FROM tbl_student_evaluation WHERE StudentId='{$StudentNumber}'";
    }
	echo $sql1;
	echo $user_role == "Admin";
	$result1 = $mysqli->query($sql1);
if($result1-> num_rows > 0)
{

	while($row2 = $result1->fetch_assoc()) 
	{
        echo '$row2["LOR1"]:'.$row2["LOR1"].
            '$row2["LOR2"]'.$row2["LOR2"].
            '$row2["LOR3"]'.$row2["LOR3"].
            '$row2["SOP"]'.$row2["SOP"].
            '$row2["Recommendation"]'.$row2["Recommendation"].
            '$row2["FacultyName"]'.$row2["FacultyName"].
            '$row2["Date"]'.$row2["Date"]
        ;

		if($row2["Recommendation"]!= ''  && $row2["FacultyName"]!= '' && $row2["Date"]!= '')
			$Status = "Complete";
		else
			$Status = "Pending";
	}
	if($user_role != "Admin" and $user_role != "Staff") {
		$sql2 = "UPDATE tbl_student_evaluation SET Status='{$Status}' WHERE StudentId='" . $StudentNumber . "' AND FacultyId='" . $user_email . "'";
	}//$sql2 = "UPDATE tbl_student_evaluation SET Status='{$Status}' WHERE StudentId='".$StudentNumber."'";
    else
	{
        $sql2 = "UPDATE tbl_student_evaluation SET Status='{$Status}' WHERE StudentId='" . $StudentNumber . "' ";
    }
	echo $sql2;
    echo 'Status'.$Status;
	if($mysqli->query($sql2) != true)
	{
		echo "Error: ".$sql2."<br>".$mysqli->error;
	}
}
?>

