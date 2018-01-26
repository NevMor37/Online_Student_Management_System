<?php
//echo "Entered";
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


if(isset($_POST['studentNumber'])){$studentNumber=$_POST['studentNumber'];}
if(isset($_POST['workExp'])){$WorkExp=$_POST['workExp'];}
if(isset($_POST['researchExp'])){$ResearchExp=$_POST['researchExp'];}
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
if(isset($_POST['other1'])){$OtherGrade1=$_POST['other1'];}
if(isset($_POST['other2'])){$OtherGrade2=$_POST['other2'];}
if(isset($_POST['other3'])){$OtherGrade3=$_POST['other3'];}
if(isset($_POST['other4'])){$OtherGrade4=$_POST['other4'];}

if(isset($_POST['degree'])){$Degree=$_POST['degree'];}
if(isset($_POST['concentration'])){$Concentration=$_POST['concentration'];}
if(isset($_POST['datefrom1'])){$DateFrom1=$_POST['datefrom1'];}
if(isset($_POST['degree1'])){$Degree1=$_POST['degree1'];}
if(isset($_POST['college1'])){$College1=$_POST['college1'];}
if(isset($_POST['gpa1'])){$GPA1=$_POST['gpa1'];}
if(isset($_POST['datefrom2'])){$DateFrom2=$_POST['datefrom2'];}
if(isset($_POST['degree2'])){$Degree2=$_POST['degree2'];}
if(isset($_POST['college2'])){$College2=$_POST['college2'];}
if(isset($_POST['gpa2'])){$GPA2=$_POST['gpa2'];}
if(isset($_POST['datefrom3'])){$DateFrom3=$_POST['datefrom3'];}
if(isset($_POST['degree3'])){$Degree3=$_POST['degree3'];}
if(isset($_POST['college3'])){$College3=$_POST['college3'];}
if(isset($_POST['gpa3'])){$GPA3=$_POST['gpa3'];}

if(isset($_POST['greV'])){$GREV=$_POST['greV'];}
if(isset($_POST['greVP'])){$GREVP=$_POST['greVP'];}
if(isset($_POST['greQ'])){$GREQ=$_POST['greQ'];}
if(isset($_POST['greQP'])){$GREQP=$_POST['greQP'];}
if(isset($_POST['greA'])){$GREA=$_POST['greA'];}
if(isset($_POST['greAP'])){$GREAP=$_POST['greAP'];}
if(isset($_POST['greTotal'])){$GRETot=$_POST['greTotal'];}
if(isset($_POST['toeflTotal'])){$TOEFLTot=$_POST['toeflTotal'];}

include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$date = date("Y-m-d H:i:s");
$LastUpdatedOn=$date;

$sql = "INSERT INTO  tbl_excel_ext_info (`PantherID`, `WExp`, `RExp`, `ds`, `se`, `automata`, `algo`, `os`, `proLan`, `ca`, `calculus`, `discrete`, `other`,`other1`,`other2`,`other3`,`other4`,`LastUpdatedOn`)";
$sql =$sql."VALUES (".($studentNumber!=''?$studentNumber:NULL).",'".($WorkExp!=''?$WorkExp:NULL)."', '".($ResearchExp!=''?$ResearchExp:NULL)."','" .($DsGrade!=''?$DsGrade:NULL)."','".($SeGrade!=''?$SeGrade:NULL)."','".($AutomataGrade!=''?$AutomataGrade:NULL)."','" .($AlgoGrade!=''?$AlgoGrade:NULL)."','" .($OsGrade!=''?$OsGrade:NULL)."','" .($PLgrade!=''?$PLgrade:NULL)."','" .($CaGrade!=''?$CaGrade:NULL)."','" .($CalculusGrade!=''?$CalculusGrade:NULL)."','" .($DiscreteGrade!=''?$DiscreteGrade:NULL)."', '" .($OtherGrade!=''?$OtherGrade:NULL)."','" .($OtherGrade1!=''?$OtherGrade1:NULL)."','" .($OtherGrade2!=''?$OtherGrade2:NULL)."','" .($OtherGrade3!=''?$OtherGrade3:NULL)."','" .($OtherGrade4!=''?$OtherGrade4:NULL)."','" .($LastUpdatedOn!=''?$LastUpdatedOn:NULL)."')";
//echo $sql;
$sql1 = "UPDATE tbl_excel_info SET Program='{$Degree}', Concentration='{$Concentration}', GREVerbalScore='{$GREV}', GREVerbalPercent='{$GREVP}', GREQuantScore='{$GREQ}', GREQuantPercent='{$GREQP}',GREAnalyticalScore='{$GREA}', GREAnalyticalPercent='{$GREAP}', GRETotal='{$GRETot}', TOEFLTotal='{$TOEFLTot}', DateAttendedFrom1='{$DateFrom1}', CollegeName1='{$College1}', Degree1='{$Degree1}', UgGPAOverall='{$GPA1}', DateAttendedFrom2='{$DateFrom2}', CollegeName2='{$College2}', Degree2='{$Degree2}', GraduateGPA='{$GPA2}', DateAttendedFrom3='{$DateFrom3}', CollegeName3='{$College3}', Degree3='{$Degree3}' WHERE PantherId='{$studentNumber}'"; 
//VALUES ('{$Degree}', '{$Concentration}', '{$GREV}', '{$GREVP}', '{$GREQ}', '{$GREQP}', '{$GREA}', '{$GREAP}', '{$GRETot}', '{$TOEFLTot}', '{$DateFrom1}', '{$College1}', '{$Degree1}', '{$GPA1}', '{$DateFrom2}', '{$College2}', '{$Degree2}', '{$GPA2}', '{$DateFrom3}', '{$College3}', '{$Degree3}')";

if($mysqli->query($sql1) == false)
{
	echo "Error: ".$sql1."<br>".$mysqli->error;
}

if($mysqli->query($sql) == true)
{	echo "Data saved";
}
else
{
	$sql = "UPDATE  tbl_excel_ext_info SET `PantherID`='".$studentNumber."'";
	$sql = $sql.($WorkExp==''?'':',`WExp`='."'".$WorkExp."'");
	$sql = $sql.($ResearchExp==''?'':',`RExp`='."'".$ResearchExp."'");
	$sql = $sql.($DsGrade==''?',`ds`=NULL':',`ds`='."'".$DsGrade."'");
	$sql = $sql.($SeGrade==''?',`se`=NULL':',`se`='."'".$SeGrade."'");
	$sql = $sql.($AutomataGrade==''?',`automata`=NULL':',`automata`='."'".$AutomataGrade."'");
	$sql = $sql.($AlgoGrade==''?',`algo`=NULL':',`algo`='."'".$AlgoGrade."'");
	$sql = $sql.($OsGrade==''?',`os`=NULL':',`os`='."'".$OsGrade."'");
	$sql = $sql.($PLgrade==''?',`proLan`=NULL':',`proLan`='."'".$PLgrade."'");
	$sql = $sql.($CaGrade==''?',`ca`=NULL':',`ca`='."'".$CaGrade."'");
	$sql = $sql.($CalculusGrade==''?',`calculus`=NULL':',`calculus`='."'".$CalculusGrade."'");
	$sql = $sql.($DiscreteGrade==''?',`discrete`=NULL':',`discrete`='."'".$DiscreteGrade."'");
	$sql = $sql.($OtherGrade==''?',`other`=NULL':',`other`='."'".$OtherGrade."'");
	$sql = $sql.($OtherGrade1==''?',`other1`=NULL':',`other1`='."'".$OtherGrade1."'");
	$sql = $sql.($OtherGrade2==''?',`other2`=NULL':',`other2`='."'".$OtherGrade2."'");
	$sql = $sql.($OtherGrade3==''?',`other3`=NULL':',`other3`='."'".$OtherGrade3."'");
	$sql = $sql.($OtherGrade4==''?',`other4`=NULL':',`other4`='."'".$OtherGrade4."'");
	$sql = $sql.($LastUpdatedOn==''?',`LastUpdatedOn`=NULL':',`LastUpdatedOn`='."'".$LastUpdatedOn."'");
	
	
	$sql = $sql.' WHERE `PantherID`='.$studentNumber;
	//echo $sql;
	if($mysqli->query($sql) == true)
	{
		echo "Data Updated";
	}
	else 
	{
		echo "Error: ".$sql."<br>".$mysqli->error;
	}
}
?>

