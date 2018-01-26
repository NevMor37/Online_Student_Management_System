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
$Batch  = false;

if(isset($_POST['ApplicantIDs'])){$ApplicantIDs=$_POST['ApplicantIDs'];}
if(isset($_POST['Status'])){$Status=$_POST['Status'];}
if(isset($_POST['Batch'])){$Batch=$_POST['Batch'];}


include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

if($Batch)
{
	$ApplicantID = (explode(",",$ApplicantIDs));
	$Count = 0;
	$test = "";
	$arrlength = count($ApplicantID);
	for($i = 0; $i < $arrlength-1; $i++) {
	// 	echo "Key=" . $x . ", Value=" . $x_value;

		$sql = "Update tbl_gaapplication ";
		$sql = $sql." Set Status = '$Status'";
        $sql = $sql." where GAApplicationID='$ApplicantID[$i]'";
		$test = $test." ".$ApplicantID[$i];
		echo $sql;
		if($mysqli->query($sql) == true)
		{
			$Count++;
			echo $Count.':success';
		}
		else
		{
			echo $Count." Error: ".$sql."<br>".$mysqli->error;
		}
	}
	$Count++;

}
else
{
$sql = "Update tbl_gaapplication ";
$sql = $sql." Set Status = '$Status'";
$sql = $sql." where GAApplicationID='$ApplicantIDs'";
echo $sql;
	if($mysqli->query($sql) == true)
	{
		//echo "Single ..Email";
		echo "success";
	}
	else 
	{
		echo "Error: ".$sql."<br>".$mysqli->error;
	}
}
?>

