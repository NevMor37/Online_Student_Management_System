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

if(isset($_POST['TermID'])){$TermID=$_POST['TermID'];}
if(isset($_POST['Status'])){$Status=$_POST['Status'];}


include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql = "Update tbl_gaapplication ";
$sql = $sql." Set IsPublish = '$Status'";
$sql = $sql." where TermID='$TermID'";
//echo $sql;
echo "change Publish status ";
if($mysqli->query($sql) == true)
{
	//echo "Single ..Email";
	echo "success!";
}
else
{
	echo "Error: ".$sql."<br>".$mysqli->error;
}
?>

